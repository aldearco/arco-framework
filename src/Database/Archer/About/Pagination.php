<?php

namespace Arco\Database\Archer\About;

trait Pagination {
    /**
     * Number of items by page.
     *
     * @var integer
     */
    protected int $size;

    /**
     * Get page number from query if is set.
     *
     * @return integer
     */
    protected function getPageNumber(): int {
        return app()->request->query('page') ?? 1;
    }

    /**
     * Get a paginated array
     *
     * @param integer $size Number of items by page
     * @return array
     */
    public function paginate(int $size): array {
        $this->size = $size;
        $offset = ($this->getPageNumber() - 1) * $this->size;
        return array_slice($this->items, $offset, $this->size);
    }

    /**
     * Get the number of pages for this array size.
     *
     * @return float
     */
    public function pages(): float {
        return ceil(count($this->items) / $this->size);
    }
}
