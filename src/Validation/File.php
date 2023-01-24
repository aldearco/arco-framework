<?php

namespace Arco\Validation;

use Arco\Helpers\Arrows\Str;
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

    protected array $max;

    protected array $min;

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
        return (new self())
            ->setRule('types')
            ->setTypes($types);
    }

    /**
     * Check if the file size does not exceed a set maximum.
     *
     * @param string $size
     * @return self
     */
    public static function max(string $size): self {
        return (new self())
            ->setRule('max')
            ->setMax($size);
    }

    /**
     * Check if the file size does not reach a set minimum.
     *
     * @param string $size
     * @return self
     */
    public static function min(string $size): self {
        return (new self())
            ->setRule('min')
            ->setMin($size);
    }

    /**
     * Check if the file size is within a range.
     *
     * @param string $min
     * @param string $max
     * @return self
     */
    public static function within(string $min, string $max): self {
        return (new self())
            ->setRule('within')
            ->setMin($min)
            ->setMax($max);
    }

    /**
     * Set maximum size.
     *
     * @param string $size
     * @return self
     */
    public function setMax(string $size): self {
        $this->max = [
            "string" => $size,
            "bytes" => Str::toBytes($size)
        ];
        return $this;
    }

    /**
     * Set minimum size.
     *
     * @param string $size
     * @return self
     */
    public function setMin(string $size): self {
        $this->min = [
            "string" => $size,
            "bytes" => Str::toBytes($size)
        ];
        return $this;
    }

    /**
     * Set file allowed types.
     *
     * @param array $types
     * @return self
     */
    protected function setTypes(array $types): self {
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
            "types" => "The file you have uploaded does not match with the expected types: {$this->typesToString()}.",
            "image" => "The file you have uploaded is not an image.",
            "max" => "The file you have uploaded exceed the maximum: {$this->max['string']}.",
            "min" => "The file you have uploaded don't reach the minimum: {$this->min['string']}.",
            "within" => "The file you have uploaded is not within {$this->min['string']} - {$this->max['string']}.",
        };
    }

    protected function maxValidation(string $field, array $data) {
        return $data[$field]->size() <= $this->max['bytes'];
    }

    protected function minValidation(string $field, array $data) {
        return $data[$field]->size() > $this->min['bytes'];
    }

    protected function withinValidation(string $field, array $data) {
        return $data[$field]->size() >= $this->min['bytes'] && $data[$field]->size() <= $this->max['bytes'];
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
