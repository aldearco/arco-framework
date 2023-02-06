<?php

namespace Arco\Database\Archer;

use Arco\Database\Archer\About\Arrayable;
use Arco\Database\Archer\About\Pagination;

class Collection {
    use Pagination;
    use Arrayable;

    public function __construct(protected $items) {
        $this->items = $items;
    }

    /**
     * Count collection items.
     *
     * @return int
     */
    public function count(): int {
        return count($this->items);
    }

    /**
     * Get only array specified size.
     *
     * @param integer $size
     * @return self
     */
    public function take(int $size): self {
        $this->items = array_slice($this->items, 0, $size);

        return $this;
    }

    /**
     * Get collection items.
     *
     * @return array
     */
    public function get(): array {
        return $this->items;
    }
}
