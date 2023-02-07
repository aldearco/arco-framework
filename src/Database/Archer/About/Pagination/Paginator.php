<?php

namespace Arco\Database\Archer\About\Pagination;

class Paginator {
    /**
     * Number of current page for this query.
     *
     * @var integer
     */
    protected int $current;

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
        $this->current = session()->get('_pagination')['current'];
        $this->pages = intval(session()->get('_pagination')['pages']);
        $this->totalItems = session()->get('_pagination')['total-items'];
        $this->pageItems = session()->get('_pagination')['page-items'];
    }

    /**
     * Get the number of current page for this query.
     *
     * @return integer
     */
    public function currentPage(): int {
        return $this->current;
    }

    /**
     * Get the number of the last page.
     *
     * @return integer
     */
    public function lastPage(): int {
        return $this->pages;
    }

    /**
     * Get the number of total items.
     *
     * @return integer
     */
    public function total(): int {
        return $this->totalItems;
    }

    /**
     * Get the number of items per page.
     *
     * @return integer
     */
    public function perPage(): int {
        return $this->pageItems;
    }

    /**
     * Get an array with information that can be used to display pagination links.
     *
     * @return array
     */
    public function pagesData(): array {
        $currentUri = request()->uri();
        $links = [];

        for ($i = 1; $this->pages >= $i; $i++) {
            $links[$i] = [
                'url' => "$currentUri?page=$i",
                'active' => $this->currentPage() === $i
            ];
        }

        return $links;
    }

    /**
     * Get next page URL.
     *
     * @return string|null
     */
    public function nextPageUrl(): string|null {
        $currentUri = request()->uri();
        $nextPage = $this->current + 1;

        if ($nextPage > $this->pages) {
            return null;
        }

        return "$currentUri?page=$nextPage";
    }

    /**
     * Get previous page URL.
     *
     * @return string|null
     */
    public function previousPageUrl(): string|null {
        $currentUri = request()->uri();
        $previousPage = $this->current - 1;

        if ($previousPage <= 0) {
            return null;
        }

        return "$currentUri?page=$previousPage";
    }

    /**
     * Check if current page is the first page.
     *
     * @return boolean
     */
    public function onFirstPage(): bool {
        return $this->current === 1;
    }

    /**
     * Check if current page is the last page.
     *
     * @return boolean
     */
    public function onLastPage() {
        return $this->current == $this->pages;
    }

    protected function parseTemplate(string $template): string {
        return str_replace('.', '/', $template);
    }

    protected function renderPaginationTemplate(string $template) {
        $file = config('view.path')."/layouts/components/pagination/".$this->parseTemplate($template).'.php';
        $pagination = $this;

        if (file_exists($file)) {
            include $file;
        } else {
            include config('view.path')."/layouts/components/pagination/".$this->parseTemplate(config('view.pagination')).'.php';
        }
    }

    public function links(?string $template = null) {
        if (is_null($template)) {
            return $this->renderPaginationTemplate(config('view.pagination'));
        }

        return $this->renderPaginationTemplate($template);
    }
}
