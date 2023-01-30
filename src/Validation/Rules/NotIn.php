<?php

namespace Arco\Validation\Rules;

class NotIn implements ValidationRule {
    public function __construct(private array $array) {
        $this->array = $array;
    }

    public function message(): string {
        $values = implode(', ', $this->array);
        return "Must not be a value in this list: {$values}";
    }

    public function isValid($field, $data): bool {
        if (is_array($data[$field])) {
            foreach ($data[$field] as $value) {
                if (in_array($value, $this->array)) {
                    return false;
                }
            }
            return true;
        }

        return !in_array($data[$field], $this->array);
    }
}
