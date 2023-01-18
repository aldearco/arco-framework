<?php

namespace Arco\View\Fletcher;

trait Meta {
    /**
     * This tag is replaced in `$this->layoutContent` with `$this->metaTitle` when `public function render()` is executed.
     *
     * @var string
     */
    public string $titleTag = "@title";

    /**
     * Store the page title
     *
     * @var string
     */
    public string $metaTitle;

    /**
     * Regex for especified title
     *
     * @var string
     */
    public string $titleDirectiveRegex = '/@title->\(([^)]+)\)/';

    /**
     * Regex to remove title directive from view content
     *
     * @var string
     */
    public string $deleteTitleDirectiveRegex = '/@title->\([^)]+\)/';

    /**
     * If the `@title->(Page Title)` tag are set, extract their content and save it in `$this->metaTitle`
     *
     * @return static
     */
    public function getMetaTitle(): static {
        if (preg_match($this->titleDirectiveRegex, $this->viewContent, $matches)) {
            $this->metaTitle = $matches[1];
            $viewContent = preg_replace($this->deleteTitleDirectiveRegex, "", $this->viewContent);
            $this->viewContent = $viewContent;
            return $this;
        }
        $this->metaTitle = env("APP_NAME");
        return $this;
    }

    /**
     * Replace the `$this->titleTag` tag in `$this->layoutLayout` with the content of `$this->metaTitle`
     *
     * @return static
     */
    public function setMetaTitle(): static {
        $this->layoutContent = str_replace($this->titleTag, $this->metaTitle, $this->layoutContent);
        return $this;
    }
}
