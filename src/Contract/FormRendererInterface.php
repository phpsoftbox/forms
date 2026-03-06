<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms\Contract;

use PhpSoftBox\Forms\DTO\FormDefinition;

interface FormRendererInterface
{
    public function driver(): string;

    public function render(FormDefinition $form): mixed;
}
