<?php

namespace Arco\Validation\Rules;

class Size implements ValidationRule {
    public function __construct(private int $size) {
        $this->size = $size;
    }

    public function message(): string {
        return "Must have a exact length of {$this->size}.";
    }

    public function isValid($field, $data): bool {
        if (!isset($data[$field])) {
            return false;
        }

        $fieldSize = is_array($data[$field]) ? count($data[$field]) : strlen($data[$field]);

        return $fieldSize === $this->size;
    }
}
