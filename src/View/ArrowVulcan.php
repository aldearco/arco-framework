<?php

namespace Arco\View;

use Arco\View\Fletcher\Meta;
use Arco\View\Fletcher\Links;
use Arco\View\Fletcher\Content;
use Arco\View\Fletcher\Spoofing;

class ArrowVulcan implements View {
    use Meta;
    use Links;
    use Content;
    use Spoofing;

    protected string $viewsDirectory;

    protected string $defaultLayout = "main";

    protected string $contentTag = "@content";

    protected string $viewContent;

    protected string $layoutContent;


    public function __construct(string $viewsDirectory) {
        $this->viewsDirectory = $viewsDirectory;
    }

    public function render(string $view, array $params = [], string $layout = null): string {
        $viewContent = $this->renderView($view, $params);
        $layoutContent = $this->renderLayout($layout ?? $this->defaultLayout);

        return str_replace($this->contentTag, $viewContent, $layoutContent);
    }

    protected function renderView(string $view, array $params = []): string {
        $this->viewContent = $this->phpFileOutput("{$this->viewsDirectory}/{$view}.php", $params);
        $this->getMetaTitle()
            ->getStyles()
            ->getScripts()
            ->extractContent()
            ->spoofingParse();
        return $this->viewContent;
    }

    protected function renderLayout(string $layout) {
        $this->layoutContent = $this->phpFileOutput("{$this->viewsDirectory}/layouts/{$layout}.php");
        $this->setMetaTitle()
            ->setStlyes()
            ->setScripts();
        return $this->layoutContent;
    }

    protected function phpFileOutput(string $phpFile, array $params = []): string {
        foreach ($params as $param => $value) {
            $$param = $value;
        }

        ob_start();

        include_once $phpFile;

        return ob_get_clean();
    }
}
