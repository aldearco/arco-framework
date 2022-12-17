<?php

namespace Arco\Tests\Validation;

use Arco\Validation\Rule;
use Arco\Validation\Rules\Confirmed;
use PHPUnit\Framework\TestCase;
use Arco\Validation\Rules\Email;
use Arco\Validation\Rules\Number;
use Arco\Validation\Rules\Required;

class RuleParseTest extends TestCase {
    protected function setUp(): void {
        Rule::loadDefaultRules();
    }

    public function basicRules() {
        return [
            [Email::class, "email"],
            [Required::class, "required"],
            [Number::class, "number"],
            [Confirmed::class, "confirmed"],
        ];
    }

    /**
     * @dataProvider basicRules
     */
    public function test_parse_basic_rules($class, $name) {
        $this->assertInstanceOf($class, Rule::from($name));
    }
}
