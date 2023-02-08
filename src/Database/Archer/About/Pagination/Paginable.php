<?php

namespace Arco\Database\Archer\About\Pagination;

trait Paginable {
    /**
     * Number of items by page.
     *
     * @var integer
     */
    protected int $size;

    protected function setSize(int $size) {
        $this->size = $size;
    }

    /**
     * Get page number from query if is set.
     *
     * @return integer
     */
    protected function getPageNumber(): int {
        return app()->request->query('page') ?? 1;
    }

    /**
     * Set pagination in session flash.
     *
     * @return void
     */
    protected function setPagination() {
        session()->flash(
            '_pagination',
            [
                'current' => $this->getPageNumber(),
                'pages' => ceil(count($this->items) / $this->size),
                'total-items' => count($this->items),
                'page-items' => $this->size,
            ]
        );
    }

    /**
     * Get a paginated array
     *
     * @param integer $size Number of items by page
     * @return array
     */
    public function paginate(int $size): array {
        $this->setSize($size);
        $this->setPagination();
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
