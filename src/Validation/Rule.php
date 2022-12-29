<?php

namespace Arco\Validation;

use ReflectionClass;
use Arco\Validation\Rules\Max;
use Arco\Validation\Rules\Min;
use Arco\Validation\Rules\Email;
use Arco\Validation\Rules\Number;
use Arco\Validation\Rules\Unique;
use Arco\Validation\Rules\LessThan;
use Arco\Validation\Rules\Required;
use Arco\Validation\Rules\Confirmed;
use Arco\Validation\Rules\GreaterThan;
use Arco\Validation\Rules\RequiredWhen;
use Arco\Validation\Rules\RequiredWith;
use Arco\Validation\Rules\ValidationRule;
use Arco\Validation\Exceptions\RuleParseException;
use Arco\Validation\Exceptions\UnknownRuleException;

/**
 * Rule class manage all validation rules of this framework
 */
class Rule {
    /**
     * This array contains all rules for validation, including custom rules
     *
     * @var ValidationRule[]
     */
    private static array $rules = [];

    /**
     * List of Fefault validation rules of this framework
     *
     * @var ValidationRule[]
     */
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
        RequiredWith::class,
        Unique::class,
    ];

    /**
     * Load default rules into `$rules` private array
     *
     * @return void
     */
    public static function loadDefaultRules() {
        self::load(self::$defaultRules);
    }

    /**
     * Load rules into `$rules` private array, including custom validation rules
     *
     * @param array $rules
     * @return void
     */
    public static function load(array $rules) {
        foreach ($rules as $class) {
            $className = array_slice(explode("\\", $class), -1)[0];
            $ruleName = snake_case($className);
            self::$rules[$ruleName] = $class;
        }
    }

    /**
     * Obtain name of the rule in `snake_case` format
     *
     * @param ValidationRule $rule
     * @return string
     */
    public static function nameOf(ValidationRule $rule): string {
        $class = new ReflectionClass($rule);

        return snake_case($class->getShortName());
    }

    /**
     * Create a new `Email()` validation rule
     *
     * @return ValidationRule
     */
    public static function email(): ValidationRule {
        return new Email();
    }

    /**
     * Create a new `Required()` validation rule
     *
     * @return ValidationRule
     */
    public static function required(): ValidationRule {
        return new Required();
    }

    /**
     * Create a new `RequiredWith()` validation rule
     *
     * @param string $withField The `name` value of complementary field
     * @return ValidationRule
     */
    public static function requiredWith(string $withField): ValidationRule {
        return new RequiredWith($withField);
    }

    /**
     * Create a new `Number()` validation rule
     *
     * @return ValidationRule
     */
    public static function number(): ValidationRule {
        return new Number();
    }

    /**
     * Create a new `LessThan()` validation rule
     *
     * @param integer|float $value Numeric value
     * @return ValidationRule
     */
    public static function lessThan(int|float $value): ValidationRule {
        return new LessThan($value);
    }

    /**
     * Create a new `GreaterThan()` validation rule
     *
     * @param integer|float $value Numeric value
     * @return ValidationRule
     */
    public static function greaterThan(int|float $value): ValidationRule {
        return new GreaterThan($value);
    }

    /**
     * Create a new `Confirmed()` validation rule
     *
     * @return ValidationRule
     */
    public static function confirmed(): ValidationRule {
        return new Confirmed();
    }

    /**
     * Create a new `Min()` validation rule
     *
     * @param integer|float $value Numeric value
     * @return ValidationRule
     */
    public static function min(int|float $value): ValidationRule {
        return new Min($value);
    }

    /**
     * Create a new `Max()` validation rule
     *
     * @param integer|float $value Numeric value
     * @return ValidationRule
     */
    public static function max(int|float $value): ValidationRule {
        return new Max($value);
    }

    /**
     * Create a new `Max()` validation rule
     *
     * @param string $otherField The `name` value of complementary field
     * @param string $operator Options: `=`, `>`, `<`, `>=`, `<=`. Other options will throw `RuleParseException()`
     * @param integer|float $value Numeric value
     * @return ValidationRule
     */
    public static function requiredWhen(
        string $otherField,
        string $operator,
        int|float $value
    ): ValidationRule {
        return new RequiredWhen($otherField, $operator, $value);
    }

    /**
     * Parse basic rules (without parameters) in `snake_case` format to creat a new instance the rule
     *
     * @param string $ruleName
     * @return ValidationRule
     */
    public static function parseBasicRule(string $ruleName): ValidationRule {
        $class = new ReflectionClass(self::$rules[$ruleName]);

        if (count($class->getConstructor()?->getParameters() ?? []) > 0) {
            throw new RuleParseException("Rule $ruleName requires parameters, but none have been passed");
        }

        return $class->newInstance();
    }

    /**
     * Parse complex rules (with parameters) in `snake_case` format to creat a new instance the rule
     *
     * @param string $ruleName Rule name in snake_case format
     * @param string $params Required params from original string
     * @return ValidationRule
     */
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

    /**
     * Load rules from string
     *
     * @param string $str Rule name, with or without parameters
     * @return ValidationRule
     */
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
