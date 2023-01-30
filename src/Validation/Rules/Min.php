<?php

namespace Arco\Validation\Rules;

class Min implements ValidationRule {
    public function __construct(private float $min) {
        $this->min = $min;
    }

    public function message(): string {
        return "Must have a minimum length of {$this->min} characters.";
    }

    public function isValid($field, $data): bool {
        if (!isset($data[$field])) {
            return false;
        }

        $fieldSize = is_array($data[$field]) ? count($data[$field]) : strlen($data[$field]);

        return isset($data[$field])
            && $fieldSize >= $this->min;
    }
}
