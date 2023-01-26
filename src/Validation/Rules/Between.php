<?php

namespace Arco\Validation\Rules;

use Arco\Validation\Exceptions\RuleParseException;

class Between implements ValidationRule {
    public function __construct(private int $min, private int $max) {
        $this->min = $min;
        $this->max = $max;
    }

    public function message(): string {
        return "Size is not between {$this->min} - {$this->max}.";
    }

    public function isValid($field, $data): bool {
        if ($this->min >= $this->max) {
            throw new RuleParseException("Minimum value can't bé greater or equal to maximum value.");
        }

        $fieldSize = is_array($data[$field]) ? count($data[$field]) : strlen($data[$field]);

        return isset($data[$field])
            && $fieldSize >= $this->min
            && $fieldSize <= $this->max;
    }
}
