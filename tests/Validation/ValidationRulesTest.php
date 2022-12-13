<?php

namespace Arco\Tests\Validation;

use Arco\Validation\Rules\Confirmed;
use PHPUnit\Framework\TestCase;
use Arco\Validation\Rules\Email;
use Arco\Validation\Rules\GreaterThan;
use Arco\Validation\Rules\Number;
use Arco\Validation\Rules\LessThan;
use Arco\Validation\Rules\Min;
use Arco\Validation\Rules\Required;
use Arco\Validation\Rules\RequiredWhen;
use Arco\Validation\Rules\RequiredWith;

class ValidationRulesTest extends TestCase {
    public function emails() {
        return [
            ["test@test.com", true],
            ["antonio@mastermind.ac", true],
            ["test@testcom", false],
            ["test@test.", false],
            ["antonio@", false],
            ["antonio@.", false],
            ["antonio", false],
            ["@", false],
            ["", false],
            [null, false],
            [4, false],
        ];
    }

    /**
     * @dataProvider emails
     */
    public function test_email($email, $expected) {
        $data = ["email" => $email];
        $rule = new Email();
        $this->assertEquals($expected, $rule->isValid('email', $data));
    }

    public function requiredData() {
        return [
            ["", false],
            [null, false],
            [5, true],
            ["test", true],
        ];
    }

    /**
     * @dataProvider requiredData
     */
    public function test_required($value, $expected) {
        $data = ['test' => $value];
        $rule = new Required();
        $this->assertEquals($expected, $rule->isValid('test', $data));
    }

    public function test_required_with() {
        $rule = new RequiredWith('other');
        $data = ['other' => 10, 'test' => 5];
        $this->assertTrue($rule->isValid('test', $data));
        $data = ['other' => 10];
        $this->assertFalse($rule->isValid('test', $data));
    }

    public function numbers() {
        return [
            [0, true],
            [1, true],
            [1.5, true],
            [-1, true],
            [-1.5, true],
            ["0", true],
            ["1", true],
            ["1.5", true],
            ["-1", true],
            ["-1.5", true],
            ["test", false],
            ["1test", false],
            ["-5test", false],
            ["", false],
            [null, false],
        ];
    }

    /**
     * @dataProvider numbers
     */
    public function test_number($n, $expected) {
        $rule = new Number();
        $data = ["test" => $n];
        $this->assertEquals($expected, $rule->isValid("test", $data));
    }

    public function lessThanData() {
        return [
            [5, 5, false],
            [5, 6, false],
            [5, 3, true],
            [5, null, false],
            [5, "", false],
            [5, "test", false],
        ];
    }

    /**
     * @dataProvider lessThanData
     */
    public function test_less_than($value, $check, $expected) {
        $rule = new LessThan($value);
        $data = ["test" => $check];
        $this->assertEquals($expected, $rule->isValid("test", $data));
    }

    public function greaterThanData() {
        return [
            [5, 5, false],
            [5, 6, true],
            [5, 3, false],
            [5, null, false],
            [5, "", false],
            [5, "test", false],
        ];
    }

    /**
     * @dataProvider greaterThanData
     */
    public function test_greater_than($value, $check, $expected) {
        $rule = new GreaterThan($value);
        $data = ["test" => $check];
        $this->assertEquals($expected, $rule->isValid("test", $data));
    }

    public function confirmedData() {
        return [
            ["test", "test", true],
            ["12345", "12345", true],
            ["1235", "12345", false],
            ["netflix", "netlix", false],
            [null, "12345", false],
            ["", null, false]
        ];
    }

    /**
     * @dataProvider confirmedData
     */
    public function test_confirmed($value, $confirmation, $expected) {
        $rule = new Confirmed();
        $data = [
            "test" => $value,
            "test_confirmation" => $confirmation
        ];
        $this->assertEquals($expected, $rule->isValid("test", $data));
    }

    public function minData() {
        return [
            ["hol", 5, false],
            ["holasoyalberto", 6, true],
            ["", 3, false],
            ["5", 5, false],
            ["0123456789", 9, true],
            [5, 5, false],
        ];
    }

    /**
     * @dataProvider minData
     */
    public function test_min($value, $min, $expected) {
        $rule = new Min($min);
        $data = ["test" => $value];
        $this->assertEquals($expected, $rule->isValid("test", $data));
    }

    public function requiredWhenData() {
        return [
            ["other", "=", "value", ["other" => "value"], "test", false],
            ["other", "=", "value", ["other" => "value", "test" => 1], "test", true],
            ["other", "=", "value", ["other" => "not value"], "test", true],
            ["other", ">", 5, ["other" => 1], "test", true],
            ["other", ">", 5, ["other" => 6], "test", false],
            ["other", ">", 5, ["other" => 6, "test" => 1], "test", true],
        ];
    }

    /**
     * @dataProvider requiredWhenData
     */
    public function test_required_when($other, $operator, $compareWith, $data, $field, $expected) {
        $rule = new RequiredWhen($other, $operator, $compareWith);
        $this->assertEquals($expected, $rule->isValid($field, $data));
    }
}
