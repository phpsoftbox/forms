<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms\DTO;

use InvalidArgumentException;
use PhpSoftBox\Validator\Rule\ValidationRuleInterface;

use function is_callable;

final readonly class FormFieldServerDefinition
{
    /**
     * @param list<ValidationRuleInterface> $rules
     * @param list<callable(mixed):mixed> $filters
     */
    public function __construct(
        public mixed $default = null,
        public ?string $property = null,
        public array $rules = [],
        public array $filters = [],
    ) {
        if ($this->property === '') {
            throw new InvalidArgumentException('Server property must not be empty.');
        }

        foreach ($this->rules as $rule) {
            if (!$rule instanceof ValidationRuleInterface) {
                throw new InvalidArgumentException('Each server rule must implement ValidationRuleInterface.');
            }
        }

        foreach ($this->filters as $filter) {
            if (!is_callable($filter)) {
                throw new InvalidArgumentException('Each server filter must be callable.');
            }
        }
    }

    public function resolveProperty(string $fieldKey): string
    {
        return $this->property ?? $fieldKey;
    }
}
