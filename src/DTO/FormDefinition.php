<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms\DTO;

use InvalidArgumentException;

final readonly class FormDefinition
{
    /**
     * @param list<FormFieldDefinition> $fields
     * @param array<string, mixed> $meta
     */
    public function __construct(
        public string $id,
        public string $title,
        public array $fields = [],
        public array $meta = [],
    ) {
        if ($this->id === '') {
            throw new InvalidArgumentException('Form id must not be empty.');
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id'     => $this->id,
            'title'  => $this->title,
            'fields' => $this->fieldsToArray(),
            'meta'   => $this->meta,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function fieldsToArray(): array
    {
        $result = [];

        foreach ($this->fields as $field) {
            if (!$field instanceof FormFieldDefinition) {
                continue;
            }

            $result[] = $field->toArray();
        }

        return $result;
    }
}
