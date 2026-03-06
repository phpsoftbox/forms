<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms\Validation;

use PhpSoftBox\Forms\DTO\FormDefinition;
use PhpSoftBox\Validator\AbstractFormValidation;
use PhpSoftBox\Validator\Exception\ValidationException;
use PhpSoftBox\Validator\ValidationError;
use PhpSoftBox\Validator\ValidationOptions;
use PhpSoftBox\Validator\ValidationResult;
use PhpSoftBox\Validator\Validator;
use PhpSoftBox\Validator\ValidatorInterface;

use function array_replace_recursive;

abstract class AbstractFormDefinitionValidation extends AbstractFormValidation
{
    /**
     * @var array<string, list<string>>
     */
    private array $filterErrors = [];

    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        protected array $payload,
        private readonly ValidatorInterface $validator = new Validator(),
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function validate(?ValidationOptions $options = null): array
    {
        $result = $this->validationResult($options);
        if ($result->hasErrors()) {
            throw new ValidationException($result);
        }

        return $result->filteredData();
    }

    public function validationResult(?ValidationOptions $options = null): ValidationResult
    {
        $this->filterErrors = [];
        $this->beforeValidation();
        $this->applyFormFilters();

        if ($this->filterErrors !== []) {
            $result = new ValidationResult($this->toValidationErrors($this->filterErrors), $this->payload);

            $this->setValidationResult($result);

            return $result;
        }

        $rules = $this->rules();
        if ($rules === []) {
            $result = new ValidationResult([], $this->payload);

            $this->setValidationResult($result);

            return $result;
        }

        $result = $this->validator->validate(
            $this->payload,
            $rules,
            $this->messages(),
            $this->attributes(),
            $options,
            $this->payload,
        );

        $this->setValidationResult($result);

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [];
        foreach ($this->form()->fields as $field) {
            if ($field->server === null || $field->server->rules === []) {
                continue;
            }

            $rules[$field->key] = $field->server->rules;
        }

        return $rules;
    }

    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function payload(): array
    {
        return $this->payload;
    }

    /**
     * @param array<string, mixed> $payload
     */
    protected function replacePayload(array $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * @param array<string, mixed> $patch
     */
    protected function mergePayload(array $patch): void
    {
        $this->payload = array_replace_recursive($this->payload, $patch);
    }

    /**
     * @param array<string, callable(mixed): mixed|list<callable(mixed): mixed>> $filters
     */
    protected function applyFilters(array $filters): void
    {
        $result = $this->applyPayloadFilters($this->payload, $filters);

        $this->payload = $result->payload;

        if ($result->errors !== []) {
            foreach ($result->errors as $path => $messages) {
                foreach ($messages as $message) {
                    $this->filterErrors[$path] ??= [];
                    $this->filterErrors[$path][] = $message;
                }
            }
        }
    }

    abstract protected function form(): FormDefinition;

    private function applyFormFilters(): void
    {
        $filters = [];

        foreach ($this->form()->fields as $field) {
            if ($field->server === null || $field->server->filters === []) {
                continue;
            }

            $filters[$field->key] = $field->server->filters;
        }

        if ($filters !== []) {
            $this->applyFilters($filters);
        }
    }

    /**
     * @param array<string, list<string>> $errors
     * @return array<string, list<ValidationError>>
     */
    private function toValidationErrors(array $errors): array
    {
        $prepared = [];

        foreach ($errors as $field => $messages) {
            foreach ($messages as $message) {
                $prepared[$field] ??= [];
                $prepared[$field][] = new ValidationError($field, 'filter', $message);
            }
        }

        return $prepared;
    }
}
