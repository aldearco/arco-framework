<?php

namespace Arco\View\Fletcher;

trait Layouts {
    /**
     * Default Layout name file
     *
     * @var string
     */
    protected string $layout = "main";

    /**
     * Regex for especified layout
     *
     * @var string
     */
    public string $layoutDirectiveRegex = '/#layout->\(([^)]+)\)/';

    /**
     * Regex to remove layout directive from view content
     *
     * @var string
     */
    public string $deleteLayoutDirectiveRegex = '/#layout->\([^)]+\)/';

    /**
     * Extract specified layout from view content
     *
     * @return static
     */
    public function getLayout(): static {
        if (preg_match($this->layoutDirectiveRegex, $this->viewContent, $matches)) {
            $this->layout = $matches[1];
            $viewContent = preg_replace($this->deleteLayoutDirectiveRegex, "", $this->viewContent);
            $this->viewContent = $viewContent;
            return $this;
        }
        return $this;
    }
}
