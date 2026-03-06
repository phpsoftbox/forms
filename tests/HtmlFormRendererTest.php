<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms\Tests;

use PhpSoftBox\Forms\DTO\FormDefinition;
use PhpSoftBox\Forms\Renderer\HtmlFormRenderer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(HtmlFormRenderer::class)]
final class HtmlFormRendererTest extends TestCase
{
    /**
     * Проверяет текущий stub-режим html-рендерера.
     */
    #[Test]
    public function returnsStubOutputForNow(): void
    {
        $renderer = new HtmlFormRenderer();
        $form     = new FormDefinition(
            id: 'stub.form',
            title: 'Stub form',
        );

        self::assertSame('html', $renderer->driver());
        self::assertSame('', $renderer->render($form));
    }
}
