<?php

namespace Arco\Database\Archer\About;

use Arco\Database\Archer\Collection;
use Arco\Database\Archer\Model;

trait Arrayable {
    /**
     * Turn an Model Object into an array with their attributes.
     *
     * @return array
     */
    protected function modelToArray(): array {
        if (count($this->attributes) == 0) {
            return [];
        }

        return $this->getPublicAttributes();
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
                $item->getPublicAttributes()
            );
        }

        return $array;
    }

    /**
     * Turn into array.
     *
     * @return array
     */
    public function toArray(): array {
        if (is_subclass_of($this, Model::class)) {
            return $this->modelToArray();
        } elseif (class_basename($this) === Collection::class) {
            return $this->collectionToArray();
        } else {
            return [];
        }
    }
}
