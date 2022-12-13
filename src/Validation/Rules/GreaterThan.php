<?php

namespace Arco\Validation\Rules;

class GreaterThan implements ValidationRule {
    public function __construct(private float $greaterThan) {
        $this->greaterThan = $greaterThan;
    }

    public function message(): string {
        return "Must be a numeric value greater than {$this->greaterThan}";
    }

    public function isValid($field, $data): bool {
        return isset($data[$field])
            && is_numeric($data[$field])
            && $data[$field] > $this->greaterThan;
    }
}
