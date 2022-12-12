<?php

namespace Arco\Validation;

use Arco\Validation\Rules\Email;
use Arco\Validation\Rules\LessThan;
use Arco\Validation\Rules\Number;
use Arco\Validation\Rules\Required;
use Arco\Validation\Rules\RequiredWhen;
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

    public static function number(): ValidationRule {
        return new Number();
    }

    public static function lessThan(int|float $value): ValidationRule {
        return new LessThan($value);
    }

    public static function requiredWhen(
        string $otherField,
        string $operator,
        int|float $value
    ): ValidationRule {
        return new RequiredWhen($otherField, $operator, $value);
    }
}
