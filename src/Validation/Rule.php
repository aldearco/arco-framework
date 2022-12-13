<?php

namespace Arco\Validation;

use Arco\Validation\Rules\Min;
use Arco\Validation\Rules\Email;
use Arco\Validation\Rules\Number;
use Arco\Validation\Rules\LessThan;
use Arco\Validation\Rules\Required;
use Arco\Validation\Rules\Confirmed;
use Arco\Validation\Rules\GreaterThan;
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

    public static function greaterThan(int|float $value): ValidationRule {
        return new GreaterThan($value);
    }

    public static function confirmed(): ValidationRule {
        return new Confirmed();
    }

    public static function min(int|float $value): ValidationRule {
        return new Min($value);
    }

    public static function requiredWhen(
        string $otherField,
        string $operator,
        int|float $value
    ): ValidationRule {
        return new RequiredWhen($otherField, $operator, $value);
    }
}
