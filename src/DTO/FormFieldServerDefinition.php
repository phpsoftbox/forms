<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms\DTO;

use Closure;
use InvalidArgumentException;
use PhpSoftBox\Validator\Rule\ValidationRuleInterface;

use function is_callable;
use function is_object;

final readonly class FormFieldServerDefinition
{
    /**
     * @param list<ValidationRuleInterface> $rules
     * @param list<(Closure(mixed): mixed)|object> $filters Closure или invokable-объект (например, `FilterInterface`)
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
            // Как в Validator: строки-функции и массивы [$object, 'method'] не исполняются как фильтры payload.
            if (!$filter instanceof Closure && !(is_object($filter) && is_callable($filter))) {
                throw new InvalidArgumentException('Each server filter must be a Closure or an invokable object.');
            }
        }
    }

    public function resolveProperty(string $fieldKey): string
    {
        return $this->property ?? $fieldKey;
    }
}
