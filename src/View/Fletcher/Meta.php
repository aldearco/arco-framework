<?php

namespace Arco\View\Fletcher;

trait Meta {
    public string $defaultTitle = "Hola";

    public string $metaTitle;

    public string $title_directive_regex = '/@title->\(([^)]+)\)/';

    public string $delete_title_directive_regex = '/@title->\([^)]+\)/';
    

    public function getMetaTitle(string $file): bool {
        if (preg_match($this->title_directive_regex, $file, $matches)) {
            $this->metaTitle = $matches[1];
            return true;
        }
        $this->metaTitle = $this->defaultTitle;
        return false;
    }

    public function setMetaTitle(string $layoutContent): string {
        $layoutContent = str_replace('@title', $this->metaTitle, $layoutContent);
        return $layoutContent;
    }
}
