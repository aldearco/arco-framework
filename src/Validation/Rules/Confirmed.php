<?php

namespace Arco\Validation\Rules;

class Confirmed implements ValidationRule {
    public function message(): string {
        return "This field doesn't match with their confirmation field.";
    }

    public function isValid(string $field, array $data): bool {
        return $data[$field] === $data["{$field}_confirmation"];
    }
}
