<?php

namespace Arco\Database\Archer\About\Pagination;

class Paginator {
    /**
     * Number of actual page for this query.
     *
     * @var integer
     */
    protected int $actual;

    /**
     * Number of pages for this query.
     *
     * @var integer
     */
    protected int $pages;

    /**
     * Number of total items.
     *
     * @var integer
     */
    protected int $totalItems;

    /**
     * Number of items per page.
     *
     * @var integer
     */
    protected int $pageItems;

    public function __construct() {
        $this->actual = session()->get('_pagination')['actual'];
        $this->pages = intval(session()->get('_pagination')['pages']);
        $this->totalItems = session()->get('_pagination')['total-items'];
        $this->pageItems = session()->get('_pagination')['page-items'];
    }

    /**
     * Get the number of actual page for this query.
     *
     * @return integer
     */
    public function actual(): int {
        return $this->actual;
    }

    /**
     * Get the number of pages for this query.
     *
     * @return integer
     */
    public function pages(): int {
        return $this->pages;
    }

    /**
     * Get the number of total items.
     *
     * @return integer
     */
    public function totalItems(): int {
        return $this->totalItems;
    }

    /**
     * Get the number of items per page.
     *
     * @return integer
     */
    public function pageItems(): int {
        return $this->pageItems;
    }

    /**
     * Get an array with information that can be used to display pagination links
     *
     * @return array
     */
    public function links(): array {
        $currentUri = request()->uri();
        $links = [];

        for ($i = 1; $this->pages >= $i; $i++) {
            $links[$i] = [
                'uri' => "$currentUri?page=$i",
                'active' => $this->actual() === $i
            ];
        }

        return $links;
    }
}
