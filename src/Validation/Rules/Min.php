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
        return isset($data[$field])
            && strlen($data[$field]) >= $this->min;
    }
}
