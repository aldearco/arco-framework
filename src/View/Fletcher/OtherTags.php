<?php

namespace Arco\View\Fletcher;

use Arco\Container\Utilities\Testable;

trait OtherTags {
    use Testable;

    public string $csrfTag = '#csrf';

    public function csrfTagParse(): static {
        if (!$this->isTest()) {
            $this->viewContent = str_replace($this->csrfTag, csrf_input(), $this->viewContent);
        }
        return $this;
    }
}
