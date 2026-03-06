<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms\Tests;

use InvalidArgumentException;
use PhpSoftBox\Forms\DTO\FormSuggestDefinition;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(FormSuggestDefinition::class)]
final class FormSuggestDefinitionTest extends TestCase
{
    /**
     * Проверяет валидацию обязательного endpoint в suggest-описании.
     */
    #[Test]
    public function throwsWhenEndpointIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Suggest endpoint must not be empty.');

        new FormSuggestDefinition('');
    }

    /**
     * Проверяет создание suggest из массива и корректный экспорт в payload.
     */
    #[Test]
    public function buildsFromArrayAndExportsPayload(): void
    {
        $suggest = FormSuggestDefinition::fromArray([
            'endpoint'   => '/api/suggest',
            'method'     => 'post',
            'queryParam' => 'query',
            'valueKey'   => 'id',
            'labelKey'   => 'name',
            'payloadKey' => 'fields',
            'minLength'  => 2,
            'debounceMs' => 300,
            'params'     => ['country' => 'RU'],
            'headers'    => ['X-Requested-With' => 'XMLHttpRequest'],
            'meta'       => ['provider' => 'dadata'],
        ]);

        self::assertInstanceOf(FormSuggestDefinition::class, $suggest);
        self::assertSame([
            'endpoint'   => '/api/suggest',
            'method'     => 'POST',
            'queryParam' => 'query',
            'valueKey'   => 'id',
            'labelKey'   => 'name',
            'minLength'  => 2,
            'debounceMs' => 300,
            'payloadKey' => 'fields',
            'params'     => ['country' => 'RU'],
            'headers'    => ['X-Requested-With' => 'XMLHttpRequest'],
            'meta'       => ['provider' => 'dadata'],
        ], $suggest->toArray());
    }

    /**
     * Проверяет, что fromArray возвращает null без endpoint.
     */
    #[Test]
    public function fromArrayReturnsNullWhenEndpointMissing(): void
    {
        self::assertNull(FormSuggestDefinition::fromArray([]));
    }
}
