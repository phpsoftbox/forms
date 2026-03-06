<?php

declare(strict_types=1);

namespace PhpSoftBox\Forms;

enum FormValueTypesEnum: string
{
    case STRING   = 'string';
    case INT      = 'int';
    case FLOAT    = 'float';
    case BOOL     = 'bool';
    case ARRAY    = 'array';
    case JSON     = 'json';
    case DATE     = 'date';
    case DATETIME = 'datetime';
}
