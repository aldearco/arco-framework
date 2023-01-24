<?php

namespace Arco\Validation;

use Arco\Validation\Exceptions\ValidationException;

/**
 * Validation API of this framework
 */
class Validator {
    /**
     * Array of data that will passed to validation process
     *
     * @var array
     */
    protected array $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    /**
     * Validate array of data. For example in a request or other bundle of data.
     *
     * @param array $validationRules Array of `"field" => ["rules"]`
     * @param array $messages (Optional) You can load custom message for each field that you want to validate.
     * @return array
     */
    public function validate(array $validationRules, array $messages = []): array {
        $validated = [];
        $errors = [];
        foreach ($validationRules as $field => $rules) {
            if (!is_array($rules)) {
                $rules = [$rules];
            }
            $fieldUnderValidationError = [];
            foreach ($rules as $rule) {
                if (is_string($rule) && $rule === 'nullable') {
                    if (Rule::nullable($field, $this->data)) {
                        break;
                    } else {
                        continue;
                    }
                }
                if (is_string($rule)) {
                    $rule = Rule::from($rule);
                }
                if (!$rule->isValid($field, $this->data)) {
                    $message = $messages[$field][Rule::nameOf($rule)] ?? $rule->message();
                    $fieldUnderValidationError[Rule::nameOf($rule)] = $message;
                }
            }
            if (count($fieldUnderValidationError) > 0) {
                $errors[$field] = $fieldUnderValidationError;
            } else {
                $validated[$field] = isset($this->data[$field]) ? $this->data[$field] : null;
            }
        }

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        return $validated;
    }
}
