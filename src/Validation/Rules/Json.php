<?php

namespace Arco\Validation\Rules;

class Json implements ValidationRule {
    public function message(): string {
        return "This field must be a valid JSON";
    }

    public function isValid(string $field, array $data): bool {
        if (!empty($data[$field])) {
            @json_decode($data[$field]);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }
}
