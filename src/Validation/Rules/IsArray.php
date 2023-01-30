<?php

namespace Arco\Validation\Rules;

class isArray implements ValidationRule {
    public function message(): string {
        return "Must be an array of data";
    }

    public function isValid($field, $data): bool {
        return isset($data[$field]) && is_array($data[$field]);
    }
}
