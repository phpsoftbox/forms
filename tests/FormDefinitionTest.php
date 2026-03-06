<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms\Tests;

use InvalidArgumentException;
use PhpSoftBox\Forms\DTO\FormDefinition;
use PhpSoftBox\Forms\DTO\FormFieldDefinition;
use PhpSoftBox\Forms\FormFieldTypesEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(FormDefinition::class)]
final class FormDefinitionTest extends TestCase
{
    /**
     * Проверяет, что форма не может быть создана с пустым id.
     */
    #[Test]
    public function throwsWhenIdIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Form id must not be empty.');

        new FormDefinition(
            id: '',
            title: 'Company requisites',
            fields: [],
        );
    }

    /**
     * Проверяет экспорт DTO формы в массив с сериализацией полей.
     */
    #[Test]
    public function exportsDefinitionToArray(): void
    {
        $definition = new FormDefinition(
            id: 'company.requisites',
            title: 'Company requisites',
            fields: [
                new FormFieldDefinition(
                    key: 'country_code',
                    label: 'Country',
                    fieldType: FormFieldTypesEnum::SELECT,
                    required: true,
                ),
            ],
            meta: ['country' => 'RU'],
        );

        self::assertSame([
            'id'     => 'company.requisites',
            'title'  => 'Company requisites',
            'fields' => [
                [
                    'key'       => 'country_code',
                    'label'     => 'Country',
                    'fieldType' => 'select',
                    'required'  => true,
                    'meta'      => [],
                ],
            ],
            'meta' => ['country' => 'RU'],
        ], $definition->toArray());
    }
}
