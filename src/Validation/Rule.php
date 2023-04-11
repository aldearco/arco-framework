<?php

namespace Arco\Validation;

use ReflectionClass;
use Arco\Validation\Rules\In;
use Arco\Validation\Rules\Max;
use Arco\Validation\Rules\Min;
use Arco\Validation\Rules\Json;
use Arco\Validation\Rules\Size;
use Arco\Validation\Rules\Email;
use Arco\Validation\Rules\NotIn;
use Arco\Validation\Rules\Number;
use Arco\Validation\Rules\Unique;
use Arco\Validation\Rules\Between;
use Arco\Validation\Rules\Boolean;
use Arco\Validation\Rules\IsArray;
use Arco\Validation\Rules\Present;
use Arco\Validation\Rules\LessThan;
use Arco\Validation\Rules\Required;
use Arco\Validation\Rules\Confirmed;
use Arco\Validation\Rules\Different;
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
        Present::class,
        RequiredWhen::class,
        RequiredWith::class,
        Unique::class,
        In::class,
        NotIn::class,
        Size::class,
        Between::class,
        Boolean::class,
        Different::class,
        Json::class,
        IsArray::class,
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
     * Create a new `Present()` validation rule
     *
     * @return ValidationRule
     */
    public static function present(): ValidationRule {
        return new Present();
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
     * Create a new `RequiredWhen()` validation rule
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
     * Create a new `In()` validation rule
     *
     * @param array $array Array of allowed values
     * @return ValidationRule
     */
    public static function in(array $array): ValidationRule {
        return new In($array);
    }

    /**
     * Create a new `NotIn()` validation rule
     *
     * @param array $array Array of forbbiden values
     * @return ValidationRule
     */
    public static function notIn(array $array): ValidationRule {
        return new NotIn($array);
    }

    /**
     * Create a new `Size()` validation rule
     *
     * @param integer $size Expected size, valid for `array` and `string` values
     * @return ValidationRule
     */
    public static function size(int $size): ValidationRule {
        return new Size($size);
    }

    /**
     * Create a new `Size()` validation rule
     *
     * @param integer $min Minimum size
     * @param integer $max Maximum size
     * @return ValidationRule
     */
    public function between(int $min, int $max): ValidationRule {
        return new Between($min, $max);
    }

    /**
     * Create a new `Boolean()` validation rule
     *
     * @return ValidationRule
     */
    public static function boolean(): ValidationRule {
        return new Boolean();
    }

    /**
     * Create a new `Different()` validation rule
     *
     * @param integer|float $field Other request data field to compare with
     * @return ValidationRule
     */
    public static function different(string $field): ValidationRule {
        return new Different($field);
    }

    /**
     * Create a new `IsArray()` validation rule
     *
     * @return ValidationRule
     */
    public static function isArray(): ValidationRule {
        return new IsArray();
    }

    /**
     * Nullable rule
     *
     * @param string $field
     * @param array $data
     * @return bool
     */
    public static function nullable(string $field, array $data): bool {
        return !isset($data[$field]) || empty($data[$field]);
    }

    /**
     * Create a new `Json()` validation rule
     *
     * @return ValidationRule
     */
    public static function json(): ValidationRule {
        return new Json();
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
     * Converts the given parameters to an array
     *
     * @param array $constructorParameters Parameters in the constructor rule class
     * @param string $params Required params from original string
     * @return array
     */
    protected static function getGivenParameters(array $constructorParameters, string $params): array {
        if (count($constructorParameters) && $constructorParameters[0]->name === 'array') {
            return [explode(',', $params)];
        }

        return array_filter(explode(",", $params), fn ($p) => !empty($p));
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

        // var_dump(self::getGivenParameters($constructorParameters, $params)); die;

        // $givenParameters = array_filter(explode(",", $params), fn ($p) => !empty($p));
        $givenParameters = self::getGivenParameters($constructorParameters, $params);

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
