<?php

namespace Arco\Validation\Rules;

class Different implements ValidationRule {
    public function __construct(private string $field) {
        $this->field = $field;
    }

    public function message(): string {
        return "Must have a different value than {$this->field} field.";
    }

    public function isValid($field, $data): bool {
        return isset($data[$field])
            && $data[$field] !== $data[$this->field];
    }
}
