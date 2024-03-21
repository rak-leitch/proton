<?php declare(strict_types = 1);

namespace Adepta\Proton\Field;

enum FrontendType : string
{
    case TEXT = 'text';
    case TEXTAREA = 'textarea';
    case SELECT = 'select';
    case NONE = '';
}
