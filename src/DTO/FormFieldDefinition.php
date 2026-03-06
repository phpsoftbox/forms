<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms\DTO;

use PhpSoftBox\Forms\FormFieldTypesEnum;
use PhpSoftBox\Forms\FormIntervalTypesEnum;
use PhpSoftBox\Forms\FormValueTypesEnum;

use function is_string;
use function trim;

final readonly class FormFieldDefinition
{
    /**
     * @param list<array<string, mixed>> $options
     * @param array<string, mixed> $meta
     * @param list<array<string, mixed>> $requiredWhen
     * @param list<array<string, mixed>> $visibleWhen
     */
    public function __construct(
        public string $key,
        public string $label,
        public FormFieldTypesEnum $fieldType = FormFieldTypesEnum::TEXT,
        public bool $required = false,
        public ?string $description = null,
        public bool $multiple = false,
        public bool $searchable = false,
        public ?FormValueTypesEnum $valueType = null,
        public ?FormIntervalTypesEnum $intervalType = null,
        public ?string $format = null,
        public array $options = [],
        public array $meta = [],
        public array $requiredWhen = [],
        public array $visibleWhen = [],
        public ?FormSuggestDefinition $suggest = null,
        public ?FormFieldServerDefinition $server = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = [
            'key'       => $this->key,
            'label'     => $this->label,
            'fieldType' => $this->fieldType->value,
            'required'  => $this->required,
        ];

        if ($this->description !== null && $this->description !== '') {
            $result['description'] = $this->description;
        }

        if ($this->multiple) {
            $result['multiple'] = true;
        }

        if ($this->searchable) {
            $result['searchable'] = true;
        }

        if ($this->valueType !== null) {
            $result['valueType'] = $this->valueType->value;
        }

        if ($this->intervalType !== null) {
            $result['intervalType'] = $this->intervalType->value;
        }

        if ($this->format !== null && $this->format !== '') {
            $result['format'] = $this->format;
        }

        if ($this->options !== []) {
            $result['options'] = $this->options;
        }

        $meta = $this->meta;
        if ($this->description !== null && $this->description !== '') {
            $tooltip = $meta['tooltip'] ?? null;
            if ($tooltip === null || (is_string($tooltip) && trim($tooltip) === '')) {
                $meta['tooltip'] = $this->description;
            }
        }

        $result['meta'] = $meta;

        if ($this->requiredWhen !== []) {
            $result['requiredWhen'] = $this->requiredWhen;
        }

        if ($this->visibleWhen !== []) {
            $result['visibleWhen'] = $this->visibleWhen;
        }

        if ($this->suggest !== null) {
            $result['suggest'] = $this->suggest->toArray();
        }

        return $result;
    }
}
