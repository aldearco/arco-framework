<?php

namespace Arco\Validation;

use Arco\Validation\Exceptions\RuleParseException;
use Arco\Validation\Exceptions\UnknownRuleException;
use Arco\Validation\Rules\Min;
use Arco\Validation\Rules\Email;
use Arco\Validation\Rules\Number;
use Arco\Validation\Rules\LessThan;
use Arco\Validation\Rules\Required;
use Arco\Validation\Rules\Confirmed;
use Arco\Validation\Rules\GreaterThan;
use Arco\Validation\Rules\Max;
use Arco\Validation\Rules\RequiredWhen;
use Arco\Validation\Rules\RequiredWith;
use Arco\Validation\Rules\ValidationRule;
use ReflectionClass;

class Rule {
    private static array $rules = [];

    private static array $defaultRules = [
        Confirmed::class,
        Email::class,
        GreaterThan::class,
        LessThan::class,
        Max::class,
        Min::class,
        Number::class,
        Required::class,
        RequiredWhen::class,
        RequiredWith::class
    ];

    public static function loadDefaultRules() {
        self::load(self::$defaultRules);
    }

    public static function load(array $rules) {
        foreach ($rules as $class) {
            $className = array_slice(explode("\\", $class), -1)[0];
            $ruleName = snake_case($className);
            self::$rules[$ruleName] = $class;
        }
    }

    public static function nameOf(ValidationRule $rule): string {
        $class = new ReflectionClass($rule);

        return snake_case($class->getShortName());
    }

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

    public static function max(int|float $value): ValidationRule {
        return new Max($value);
    }

    public static function requiredWhen(
        string $otherField,
        string $operator,
        int|float $value
    ): ValidationRule {
        return new RequiredWhen($otherField, $operator, $value);
    }

    public static function parseBasicRule(string $ruleName): ValidationRule {
        $class = new ReflectionClass(self::$rules[$ruleName]);

        if (count($class->getConstructor()?->getParameters() ?? []) > 0) {
            throw new RuleParseException("Rule $ruleName requires parameters, but none have been passed");
        }

        return $class->newInstance();
    }

    public static function parseRuleWithParameters(string $ruleName, string $params): ValidationRule {
        $class = new ReflectionClass(self::$rules[$ruleName]);
        $constructorParameters = $class->getConstructor()?->getParameters() ?? [];
        $givenParameters = array_filter(explode(",", $params), fn ($p) => !empty($p));

        if (count($givenParameters) !== count($constructorParameters)) {
            throw new RuleParseException(sprintf(
                "Rule %s requires %d parameters, but %d where given: %s",
                $ruleName,
                count($constructorParameters),
                count($givenParameters),
                $params
            ));
        }

        return $class->newInstance(...$givenParameters);
    }

    public static function from(string $str): ValidationRule {
        if (strlen($str) == 0) {
            throw new RuleParseException("Can't parse empty string to rule");
        }

        $ruleParts = explode(":", $str);

        if (!array_key_exists($ruleParts[0], self::$rules)) {
            throw new UnknownRuleException("Rule {$ruleParts[0]} not found");
        }

        if (count($ruleParts) == 1) {
            return self::parseBasicRule($ruleParts[0]);
        }

        [$ruleName, $params] = $ruleParts;

        return self::parseRuleWithParameters($ruleName, $params);
    }
}
