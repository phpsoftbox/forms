<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms\Renderer;

use PhpSoftBox\Forms\Contract\FormRendererInterface;
use PhpSoftBox\Forms\DTO\FormDefinition;

final readonly class JsonFormRenderer implements FormRendererInterface
{
    public function driver(): string
    {
        return 'json';
    }

    /**
     * @return array<string, mixed>
     */
    public function render(FormDefinition $form): array
    {
        return $form->toArray();
    }
}
