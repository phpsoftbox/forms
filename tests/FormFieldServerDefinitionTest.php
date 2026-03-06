<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms\Tests;

use InvalidArgumentException;
use PhpSoftBox\Forms\DTO\FormFieldServerDefinition;
use PhpSoftBox\Validator\Rule\StringValidation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function is_string;
use function trim;

#[CoversClass(FormFieldServerDefinition::class)]
final class FormFieldServerDefinitionTest extends TestCase
{
    /**
     * Проверяет хранение server-конфига и резолв целевого property.
     */
    #[Test]
    public function storesServerConfigAndResolvesPropertyName(): void
    {
        $definition = new FormFieldServerDefinition(
            default: 'UTC',
            property: 'timezone',
            rules: [new StringValidation()],
            filters: [static fn (mixed $value): mixed => is_string($value) ? trim($value) : $value],
        );

        self::assertSame('UTC', $definition->default);
        self::assertSame('timezone', $definition->resolveProperty('settings.timezone'));
        self::assertCount(1, $definition->rules);
        self::assertCount(1, $definition->filters);
    }

    /**
     * Проверяет валидацию запрета пустого property в server-конфиге.
     */
    #[Test]
    public function throwsForEmptyProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Server property must not be empty.');

        new FormFieldServerDefinition(property: '');
    }

    /**
     * Проверяет валидацию типа правил в server-конфиге.
     */
    #[Test]
    public function throwsForInvalidRule(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Each server rule must implement ValidationRuleInterface.');

        new FormFieldServerDefinition(rules: ['invalid']);
    }

    /**
     * Проверяет валидацию типа фильтров в server-конфиге.
     */
    #[Test]
    public function throwsForInvalidFilter(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Each server filter must be callable.');

        new FormFieldServerDefinition(filters: ['invalid']);
    }
}
