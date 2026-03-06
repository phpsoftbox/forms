<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms\Tests;

use PhpSoftBox\Forms\DTO\FormDefinition;
use PhpSoftBox\Forms\DTO\FormFieldDefinition;
use PhpSoftBox\Forms\FormFieldTypesEnum;
use PhpSoftBox\Forms\Renderer\JsonFormRenderer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(JsonFormRenderer::class)]
final class JsonFormRendererTest extends TestCase
{
    /**
     * Проверяет рендер DTO формы в JSON-совместимый массив.
     */
    #[Test]
    public function rendersFormToArrayPayload(): void
    {
        $renderer = new JsonFormRenderer();
        $form     = new FormDefinition(
            id: 'auth.login',
            title: 'Login',
            fields: [
                new FormFieldDefinition('phone', 'Phone', FormFieldTypesEnum::TEL, true),
            ],
            meta: ['area' => 'cabinet'],
        );

        self::assertSame('json', $renderer->driver());
        self::assertSame([
            'id'     => 'auth.login',
            'title'  => 'Login',
            'fields' => [
                [
                    'key'       => 'phone',
                    'label'     => 'Phone',
                    'fieldType' => 'tel',
                    'required'  => true,
                    'meta'      => [],
                ],
            ],
            'meta' => ['area' => 'cabinet'],
        ], $renderer->render($form));
    }
}
