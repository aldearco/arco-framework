<?php

namespace Arco\Validation;

use Arco\Validation\Rules\Email;
use Arco\Validation\Rules\Required;
use Arco\Validation\Rules\RequiredWith;
use Arco\Validation\Rules\ValidationRule;

class Rule {
    public static function email(): ValidationRule {
        return new Email();
    }

    public static function required(): ValidationRule {
        return new Required();
    }

    public static function requiredWith(string $withField): ValidationRule {
        return new RequiredWith($withField);
    }
}
