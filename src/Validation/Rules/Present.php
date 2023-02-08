<?php

namespace Arco\Validation\Rules;

class Present implements ValidationRule {
    public function message(): string {
        return "This field is must be present";
    }

    public function isValid(string $field, array $data): bool {
        return array_key_exists($field, $data);
    }
}
