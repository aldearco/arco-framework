<?php

namespace Arco\View;

use Arco\View\Fletcher\Content;
use Arco\View\Fletcher\Meta;
use Arco\View\Fletcher\Spoofing;

class ArrowVulcan implements View {
    use Meta;
    use Content;
    use Spoofing;

    protected string $viewsDirectory;

    protected string $defaultLayout = "main";

    protected string $contentTag = "@content";

    protected string $viewContent;


    public function __construct(string $viewsDirectory) {
        $this->viewsDirectory = $viewsDirectory;
    }

    public function render(string $view, array $params = [], string $layout = null): string {
        $viewContent = $this->renderView($view, $params);
        $layoutContent = $this->renderLayout($layout ?? $this->defaultLayout);

        return str_replace($this->contentTag, $viewContent, $layoutContent);
    }

    protected function renderView(string $view, array $params = []): string {
        $file = $this->phpFileOutput("{$this->viewsDirectory}/{$view}.php", $params);
        $this->getMetaTitle($file);
        $this->extractContent($file)
            ->spoofingParse();
        return $this->viewContent;
    }

    protected function renderLayout(string $layout) {
        $layoutContent = $this->phpFileOutput("{$this->viewsDirectory}/layouts/{$layout}.php");
        $layoutContent = $this->setMetaTitle($layoutContent);
        return $layoutContent;
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
