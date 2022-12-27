<?php

namespace Arco\View\Fletcher;

trait Meta {
    public string $titleTag = "@title";

    public string $metaTitle;

    public string $title_directive_regex = '/@title->\(([^)]+)\)/';

    public string $delete_title_directive_regex = '/@title->\([^)]+\)/';
    

    public function getMetaTitle(): static {
        if (preg_match($this->title_directive_regex, $this->viewContent, $matches)) {
            $this->metaTitle = $matches[1];
            $viewContent = preg_replace($this->delete_title_directive_regex, "", $this->viewContent);
            $this->viewContent = $viewContent;
            return $this;
        }
        $this->metaTitle = env("APP_NAME");
        return $this;
    }

    public function setMetaTitle(): static {
        $this->layoutContent = str_replace($this->titleTag, $this->metaTitle, $this->layoutContent);
        return $this;
    }
}
