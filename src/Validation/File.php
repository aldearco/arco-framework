<?php

namespace Arco\Validation;

use Arco\Validation\Rules\ValidationRule;

class File implements ValidationRule {
    /**
     * File rule.
     *
     * @var string
     */
    protected string $rule;

    /**
     * File allowed types.
     *
     * @var array
     */
    protected array $types = [];

    /**
     * Set file rule.
     *
     * @param string $rule
     * @return self
     */
    protected function setRule(string $rule): self {
        $this->rule = $rule;
        return $this;
    }

    /**
     * Check if file is an image.
     *
     * @return self
     */
    public static function image(): self {
        return (new self())->setRule('image');
    }

    /**
     * Check if file type is allowed.
     *
     * @param array $types
     * @return self
     */
    public static function types(array $types): self {
        return (new self())->setTypes($types);
    }

    /**
     * Set file allowed types.
     *
     * @param array $types
     * @return self
     */
    protected function setTypes(array $types): self {
        $this->setRule('types');
        $this->types = $types;
        return $this;
    }

    /**
     * Turn types into string for the message message.
     *
     * @return string
     */
    protected function typesToString(): string {
        return implode(', ', $this->types);
    }

    /**
     * Error message.
     *
     * @return string
     */
    public function message(): string {
        return match ($this->rule) {
            "types" => "The file that you uploaded does not match with the expected types: {$this->typesToString()}.",
            "image" => "The file that you uploaded is not an image."
        };
    }

    /**
     * Check if file is an image.
     *
     * @param string $field
     * @param array $data
     * @return boolean
     */
    protected function imageValidation(string $field, array $data): bool {
        return $data[$field]->isImage();
    }

    /**
     * Check if file type is allowed.
     *
     * @param string $field
     * @param array $data
     * @return boolean
     */
    protected function typesValidation(string $field, array $data): bool {
        return in_array($data[$field]->extension(), $this->types);
    }

    /**
     * Call the validation function for each rule.
     *
     * @param string $field
     * @param array $data
     * @return boolean
     */
    public function isValid(string $field, array $data): bool {
        return $this->{$this->rule.'Validation'}($field, $data);
    }
}
