<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms\Renderer;

use PhpSoftBox\Forms\Contract\FormRendererInterface;
use PhpSoftBox\Forms\DTO\FormDefinition;

final readonly class HtmlFormRenderer implements FormRendererInterface
{
    public function driver(): string
    {
        return 'html';
    }

    /**
     * HTML-драйвер пока оставлен как заглушка до полноценного серверного рендера.
     */
    public function render(FormDefinition $form): string
    {
        return '';
    }
}
