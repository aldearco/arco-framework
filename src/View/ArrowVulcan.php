<?php

namespace Arco\View;

use Arco\View\Fletcher\Spoofing;

class ArrowVulcan implements View {
    use Spoofing;

    protected string $viewsDirectory;

    protected string $defaultLayout = "main";

    protected string $contentTag = "@content";


    public function __construct(string $viewsDirectory) {
        $this->viewsDirectory = $viewsDirectory;
    }

    public function render(string $view, array $params = [], string $layout = null): string {
        $layoutContent = $this->renderLayout($layout ?? $this->defaultLayout);
        $viewContent = $this->renderView($view, $params);

        return str_replace($this->contentTag, $viewContent, $layoutContent);
    }

    protected function renderView(string $view, array $params = []): string {
        $viewContent = $this->phpFileOutput("{$this->viewsDirectory}/{$view}.php", $params);
        $viewContent = $this->spoofingParse($viewContent);
        return $viewContent;
    }

    protected function renderLayout(string $layout) {
        return $this->phpFileOutput("{$this->viewsDirectory}/layouts/{$layout}.php");
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
