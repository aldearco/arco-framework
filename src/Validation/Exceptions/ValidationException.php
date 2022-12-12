<?php

namespace Arco\Validation\Exceptions;

use Arco\Exceptions\ArrowRejected;

class ValidationException extends ArrowRejected {
    public function __construct(protected array $errors) {
        $this->errors = $errors;
    }

    public function errors(): array {
        return $this->errors;
    }
}
