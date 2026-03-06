<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms\Tests;

use InvalidArgumentException;
use PhpSoftBox\Forms\DTO\FormDefinition;
use PhpSoftBox\Forms\DTO\FormFieldDefinition;
use PhpSoftBox\Forms\DTO\FormFieldServerDefinition;
use PhpSoftBox\Forms\FormFieldTypesEnum;
use PhpSoftBox\Forms\Validation\AbstractFormDefinitionValidation;
use PhpSoftBox\Validator\Exception\ValidationException;
use PhpSoftBox\Validator\Rule\StringValidation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function is_string;
use function trim;

#[CoversClass(AbstractFormDefinitionValidation::class)]
final class AbstractFormDefinitionValidationTest extends TestCase
{
    /**
     * Проверяет, что серверные фильтры применяются до запуска правил валидации.
     */
    #[Test]
    public function appliesServerFiltersBeforeValidation(): void
    {
        $validation = new class (['name' => '  Anton  ']) extends AbstractFormDefinitionValidation {
            protected function form(): FormDefinition
            {
                return new FormDefinition(
                    id: 'test.form',
                    title: 'Test form',
                    fields: [
                        new FormFieldDefinition(
                            key: 'name',
                            label: 'Name',
                            fieldType: FormFieldTypesEnum::TEXT,
                            server: new FormFieldServerDefinition(
                                rules: [new StringValidation()->required()->min(3)],
                                filters: [static fn (mixed $value): mixed => is_string($value) ? trim($value) : $value],
                            ),
                        ),
                    ],
                );
            }
        };

        $result = $validation->validationResult();

        self::assertFalse($result->hasErrors());
        self::assertSame('Anton', $result->filteredData()['name'] ?? null);
    }

    /**
     * Проверяет перенос ошибок фильтров в ValidationResult.
     */
    #[Test]
    public function returnsFilterErrorsWhenFilterFails(): void
    {
        $validation = new class (['name' => 'x']) extends AbstractFormDefinitionValidation {
            protected function form(): FormDefinition
            {
                return new FormDefinition(
                    id: 'test.form',
                    title: 'Test form',
                    fields: [
                        new FormFieldDefinition(
                            key: 'name',
                            label: 'Name',
                            fieldType: FormFieldTypesEnum::TEXT,
                            server: new FormFieldServerDefinition(
                                filters: [
                                    static function (mixed $value): mixed {
                                        throw new InvalidArgumentException('filter failed');
                                    },
                                ],
                            ),
                        ),
                    ],
                );
            }
        };

        $result = $validation->validationResult();

        self::assertTrue($result->hasErrors());
        self::assertSame('filter failed', $result->errors()['name'][0] ?? null);
    }

    /**
     * Проверяет, что validate() бросает исключение при невалидном payload.
     */
    #[Test]
    public function validateThrowsValidationExceptionForInvalidPayload(): void
    {
        $validation = new class (['name' => 'ab']) extends AbstractFormDefinitionValidation {
            protected function form(): FormDefinition
            {
                return new FormDefinition(
                    id: 'test.form',
                    title: 'Test form',
                    fields: [
                        new FormFieldDefinition(
                            key: 'name',
                            label: 'Name',
                            fieldType: FormFieldTypesEnum::TEXT,
                            server: new FormFieldServerDefinition(
                                rules: [new StringValidation()->required()->min(3)],
                            ),
                        ),
                    ],
                );
            }
        };

        $this->expectException(ValidationException::class);

        $validation->validate();
    }
}
