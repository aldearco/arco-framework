<?php

namespace Arco\Validation\Rules;

class Boolean implements ValidationRule {
    public function message(): string {
        return "Must be a boolean value.";
    }

    public function isValid($field, $data): bool {
        $booleans = [1, 0, true, false, '1', '0', 'true', 'false'];
        return isset($data[$field]) && in_array($data[$field], $booleans, true);
    }
}
