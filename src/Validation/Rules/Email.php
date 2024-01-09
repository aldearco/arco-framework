<?php

namespace Arco\Validation\Rules;

class Email implements ValidationRule {
    public function message(): string {
        return "Email has invalid format";
    }

    public function isValid(string $field, array $data): bool {
        if (!array_key_exists($field, $data)) {
            return false;
        }

        $email = strtolower(trim((string) $data[$field]));

        $split = explode("@", $email);

        if (count($split) != 2) {
            return false;
        }

        [$username, $domain] = $split;

        $domainParts = explode(".", $domain);

        if (count($domainParts) < 2) {
            return false;
        }

        foreach ($domainParts as $part) {
            if (strlen($part) < 1) {
                return false;
            }
        }

        return strlen($username) >= 1;
    }
}
