<?php

namespace Arco\Tests\Validation;

use Arco\Validation\Rules\In;
use Arco\Validation\Rules\Max;
use Arco\Validation\Rules\Min;
use Arco\Validation\Rules\Size;
use PHPUnit\Framework\TestCase;
use Arco\Validation\Rules\Email;
use Arco\Validation\Rules\NotIn;
use Arco\Validation\Rules\Number;
use Arco\Validation\Rules\Between;
use Arco\Validation\Rules\Boolean;
use Arco\Validation\Rules\LessThan;
use Arco\Validation\Rules\Required;
use Arco\Validation\Rules\Confirmed;
use Arco\Validation\Rules\GreaterThan;
use Arco\Validation\Rules\RequiredWhen;
use Arco\Validation\Rules\RequiredWith;
use Arco\Validation\Exceptions\RuleParseException;
use Arco\Validation\Rules\Different;
use Arco\Validation\Rules\Json;
use Arco\Validation\Rules\Present;

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

    public function presentData() {
        return [
            ["", true],
            [null, true],
            [5, true],
            ["test", true],
        ];
    }

    /**
     * @dataProvider presentData
     */
    public function test_present($value, $expected) {
        $data = ['test' => $value];
        $rule = new Present();
        $this->assertEquals($expected, $rule->isValid('test', $data));
    }

    public function test_present_with_missing_data_field() {
        $data = ['test' => ""];
        $rule = new Present();
        $this->assertEquals(true, $rule->isValid('test', $data));
        $this->assertEquals(false, $rule->isValid('test2', $data));
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
            [['test1', 'test2', 'test3'], 2, true],
            [['test1', 'test2', 'test3'], 5, false],
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

    public function maxData() {
        return [
            ["test", 5, true],
            ["holasoyalberto", 6, false],
            ["", 3, true],
            [['test1', 'test2', 'test3'], 2, false],
            [['test1', 'test2', 'test3'], 5, true],
            ["0123456789", 9, false],
            [5, 5, true],
        ];
    }

    /**
     * @dataProvider maxData
     */
    public function test_max($value, $max, $expected) {
        $rule = new Max($max);
        $data = ["test" => $value];
        $this->assertEquals($expected, $rule->isValid("test", $data));
    }

    public function inData() {
        return [
            ['test', ['test', 'string', 'hello'], true],
            ['testing', ['test', 'world', 'hello'], false],
            ['inside', ['not_in', 'world', 'outside'], false],
        ];
    }

    /**
     * @dataProvider inData
     */
    public function test_in($value, $array, $expected) {
        $rule = new In($array);
        $data = ["test" => $value];
        $this->assertEquals($expected, $rule->isValid("test", $data));
    }

    public function notInData() {
        return [
            ['test', ['test', 'string', 'hello'], false],
            ['testing', ['test', 'world', 'hello'], true],
            ['inside', ['not_in', 'world', 'outside'], true],
        ];
    }

    /**
     * @dataProvider notInData
     */
    public function test_not_in($value, $array, $expected) {
        $rule = new NotIn($array);
        $data = ["test" => $value];
        $this->assertEquals($expected, $rule->isValid("test", $data));
    }

    public function sizeData() {
        return [
            ['test', 4, true],
            ['tests', 4, false],
            [['test', 'unit', 'testing'], 3, true],
            [['hello', 'world'], 3, false],
            ['', 1, false],
            [null, 50, false],
            [[], 0, true]
        ];
    }

    /**
     * @dataProvider sizeData
     */
    public function test_size($value, $size, $expected) {
        $rule = new Size($size);
        $data = ["test" => $value];
        $this->assertEquals($expected, $rule->isValid("test", $data));
    }

    public function betweenData() {
        return [
            ['test', 2, 5, true],
            ['test is required', 2, 8, false],
            ['test test', 6, 9, true],
            [['one', 'two', 'three'], 1, 4, true],
            [['one', 'two', 'three', 'four'], 1, 3, false],
        ];
    }

    /**
     * @dataProvider betweenData
     */
    public function test_between($value, $min, $max, $expected) {
        $rule = new Between($min, $max);
        $data = ["test" => $value];
        $this->assertEquals($expected, $rule->isValid("test", $data));
    }

    public function booleanData() {
        return [
            ['test', false],
            ['1', true],
            ['0', true],
            ['true false', false],
            ['false', true],
            ['true', true],
            [true, true],
            [false, true],
            [null, false],
        ];
    }

    /**
     * @dataProvider booleanData
     */
    public function test_boolean($value, $expected) {
        $rule = new Boolean();
        $data = ["test" => $value];
        $this->assertEquals($expected, $rule->isValid("test", $data));
    }

    public function differentData() {
        return [
            ['test', 'test 2', true],
            ['test', 'test', false],
            ['test', 'test is required', true],
            [123, 123, false],
            [321, "123", true],
        ];
    }

    /**
     * @dataProvider differentData
     */
    public function test_different($value, $value2, $expected) {
        $rule = new Different('test2');
        $data = ["test" => $value, "test2" => $value2 ];
        $this->assertEquals($expected, $rule->isValid("test", $data));
    }

    public function jsonData() {
        return [
            ['{"Test": "Is a test"}', true],
            ['{"Test" => "Is a test"}', false],
            ['{Test: "Is a test"}', false],
            ['', false],
            ['{"Primary": "Primary Value", "Secondary": "Secondary Value"}', true],
            ['{"Primary": "Primary Value", "Secondary": "Secondary Value",}', false],
        ];
    }

    /**
     * @dataProvider jsonData
     */
    public function test_json($value, $expected) {
        $rule = new Json();
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

    public function test_required_when_throws_parse_rule_exception_when_operator_is_invalid() {
        $rule = new RequiredWhen("other", "|||", "test");
        $data = ["other" => 5, "test" => 1];
        $this->expectException(RuleParseException::class);
        $rule->isValid("test", $data);
    }
}
