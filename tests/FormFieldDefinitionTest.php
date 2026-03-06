<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms\Tests;

use PhpSoftBox\Forms\DTO\FormFieldDefinition;
use PhpSoftBox\Forms\DTO\FormSuggestDefinition;
use PhpSoftBox\Forms\FormFieldTypesEnum;
use PhpSoftBox\Forms\FormIntervalTypesEnum;
use PhpSoftBox\Forms\FormValueTypesEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(FormFieldDefinition::class)]
final class FormFieldDefinitionTest extends TestCase
{
    /**
     * Проверяет экспорт поля с meta, requiredWhen и visibleWhen.
     */
    #[Test]
    public function exportsFieldWithConditionsAndMeta(): void
    {
        $field = new FormFieldDefinition(
            key: 'organization_inn',
            label: 'ИНН',
            fieldType: FormFieldTypesEnum::TEXT,
            required: true,
            meta: ['section' => 'organization'],
            requiredWhen: [
                ['field' => 'organization_type', 'operator' => '=', 'value' => 'legal'],
            ],
            visibleWhen: [
                ['field' => 'country_code', 'operator' => '=', 'value' => 'RU'],
            ],
        );

        self::assertSame([
            'key'          => 'organization_inn',
            'label'        => 'ИНН',
            'fieldType'    => 'text',
            'required'     => true,
            'meta'         => ['section' => 'organization'],
            'requiredWhen' => [
                ['field' => 'organization_type', 'operator' => '=', 'value' => 'legal'],
            ],
            'visibleWhen' => [
                ['field' => 'country_code', 'operator' => '=', 'value' => 'RU'],
            ],
        ], $field->toArray());
    }

    /**
     * Проверяет экспорт расширенных свойств поля и блока suggest.
     */
    #[Test]
    public function exportsFieldWithSuggestAndExtendedProps(): void
    {
        $field = new FormFieldDefinition(
            key: 'bank_name',
            label: 'Банк',
            fieldType: FormFieldTypesEnum::TEXT,
            required: true,
            description: 'Начните вводить название банка',
            multiple: false,
            searchable: true,
            valueType: FormValueTypesEnum::STRING,
            intervalType: FormIntervalTypesEnum::DATE,
            format: 'Y-m-d',
            options: [
                ['label' => 'Т-Банк', 'value' => 'tbank'],
            ],
            suggest: new FormSuggestDefinition(
                endpoint: '/api/requisites/suggest',
                queryParam: 'query',
                payloadKey: 'fields',
                minLength: 2,
            ),
        );

        self::assertSame([
            'key'          => 'bank_name',
            'label'        => 'Банк',
            'fieldType'    => 'text',
            'required'     => true,
            'description'  => 'Начните вводить название банка',
            'searchable'   => true,
            'valueType'    => 'string',
            'intervalType' => 'date',
            'format'       => 'Y-m-d',
            'options'      => [
                ['label' => 'Т-Банк', 'value' => 'tbank'],
            ],
            'meta'    => ['tooltip' => 'Начните вводить название банка'],
            'suggest' => [
                'endpoint'   => '/api/requisites/suggest',
                'method'     => 'GET',
                'queryParam' => 'query',
                'valueKey'   => 'value',
                'labelKey'   => 'label',
                'minLength'  => 2,
                'debounceMs' => 250,
                'payloadKey' => 'fields',
            ],
        ], $field->toArray());
    }

    /**
     * Проверяет, что пустые условные блоки не попадают в экспорт.
     */
    #[Test]
    public function skipsEmptyConditionsInExport(): void
    {
        $field = new FormFieldDefinition(
            key: 'name',
            label: 'Name',
        );

        self::assertSame([
            'key'       => 'name',
            'label'     => 'Name',
            'fieldType' => 'text',
            'required'  => false,
            'meta'      => [],
        ], $field->toArray());
    }
}
