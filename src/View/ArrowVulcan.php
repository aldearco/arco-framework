<?php

namespace Arco\View;

use Arco\View\Fletcher\Meta;
use Arco\View\Fletcher\Links;
use Arco\View\Fletcher\Content;
use Arco\View\Fletcher\Layouts;
use Arco\View\Fletcher\OtherTags;
use Arco\View\Fletcher\Spoofing;

class ArrowVulcan implements View {
    use Meta;
    use Links;
    use Layouts;
    use Content;
    use Spoofing;
    use OtherTags;

    /**
     * View file path
     *
     * @var string
     */
    protected string $viewPath;

    /**
     * This tag is replaced in `$this->layoutContent` with `$this->viewContent` when `public function render()` is executed.
     *
     * @var string
     */
    protected string $contentTag = "@content";

    /**
     * View HTML Code stored into this string
     *
     * @var string
     */
    protected string $viewContent;

    /**
     * Layout HTML Code stored into this string
     *
     * @var string
     */
    protected string $layoutContent;


    public function __construct(string $viewPath) {
        $this->viewPath = $viewPath;
    }

    /**
     * Return the final HTML to the client
     *
     * @param string $view
     * @param array $params
     * @param string|null $layout
     */
    public function render(string $view, array $params = [], string $layout = null): string {
        $viewContent = $this->renderView($view, $params);
        $layoutContent = $this->renderLayout($layout ?? $this->layout);

        return str_replace($this->contentTag, $viewContent, $layoutContent);
    }

    /**
     * Construct the view HTML and extract some parts for the layout
     *
     * @param string $view
     * @param array $params
     * @return string
     */
    protected function renderView(string $view, array $params = []): string {
        $this->viewContent = $this->phpFileOutput("{$this->viewPath}/{$view}.php", $params);
        $this->getLayout()
            ->getMetaTitle()
            ->getStyles()
            ->getScripts()
            ->extractContent()
            ->csrfTagParse()
            ->spoofingParse();
        return $this->viewContent;
    }

    /**
     * Construct the Layout HTML and set some parts extracted from the view
     *
     * @param string $layout
     * @return string
     */
    protected function renderLayout(string $layout): string {
        $this->layoutContent = $this->phpFileOutput("{$this->viewPath}/layouts/{$layout}.php");
        $this->setMetaTitle()
            ->setStlyes()
            ->setScripts();
        return $this->layoutContent;
    }

    /**
     * Get contents from PHP File
     *
     * @param string $phpFile
     * @param array $params
     * @return string
     */
    protected function phpFileOutput(string $phpFile, array $params = []): string {
        foreach ($params as $param => $value) {
            $$param = $value;
        }

        ob_start();

        include_once $phpFile;

        return ob_get_clean();
    }
}
