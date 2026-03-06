<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms;

enum FormFieldTypesEnum: string
{
    case TEXT     = 'text';
    case TEXTAREA = 'textarea';
    case EMAIL    = 'email';
    case TEL      = 'tel';
    case PASSWORD = 'password';
    case CHECKBOX = 'checkbox';
    case SELECT   = 'select';
    case RADIO    = 'radio';
    case NUMBER   = 'number';
    case DATE     = 'date';
    case INTERVAL = 'interval';
    case HIDDEN   = 'hidden';
}
