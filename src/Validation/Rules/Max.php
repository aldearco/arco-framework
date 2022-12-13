<?php

namespace Arco\Validation\Rules;

class Max implements ValidationRule {
    public function __construct(private float $max) {
        $this->max = $max;
    }

    public function message(): string {
        return "Must have a maximum length of {$this->max} characters.";
    }

    public function isValid($field, $data): bool {
        return isset($data[$field])
            && strlen($data[$field]) <= $this->max;
    }
}
