<?php

namespace Arco\Database\Archer\About;

use Arco\Database\Archer\Collection;
use Arco\Database\Archer\Model;

trait Arrayable {
    protected bool $showHidden = false;

    /**
     * Turn an Model Object into an array with their attributes.
     *
     * @return array
     */
    protected function modelToArray(): array {
        if (count($this->attributes) == 0) {
            return [];
        }

        return $this->showHidden ? $this->getAttributes() : $this->getPublicAttributes();
    }

    /**
     * Turn a Collection of Model Objects into an array of items with their attributes.
     *
     * @return array
     */
    protected function collectionToArray(): array {
        $array = [];

        foreach ($this->items as $item) {
            array_push(
                $array,
                $this->showHidden
                    ? $item->getAttributes()
                    : $item->getPublicAttributes()
            );
        }

        return $array;
    }

    /**
     * Show hidden attibutes in array items.
     *
     * @return static
     */
    public function showHiddenAttributes(): static {
        $this->showHidden = true;
        return $this;
    }

    /**
     * Turn into array.
     *
     * @return array
     */
    public function toArray(): array {
        if (is_subclass_of($this, Model::class)) {
            return $this->modelToArray();
        } elseif (get_class($this) === Collection::class) {
            return $this->collectionToArray();
        } else {
            return [];
        }
    }
}
