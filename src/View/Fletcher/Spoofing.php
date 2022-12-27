<?php

namespace Arco\View\Fletcher;

trait Spoofing {
    /**
     * Regex of method matching
     *
     * @var string
     */
    public string $method_match = '/@method->\((PUT|PATCH|DELETE)\)/';

    /**
     * Parse `$this->viewContent` (PHP or HTML code) if matches `@method->(PUT|PATCH|DELETE)`
     *
     * @param string $viewContent
     * @return string
     */
    public function spoofingParse(): self {
        if (preg_match($this->method_match, $this->viewContent)) {
            $input = '<input type="hidden" name="_method" value="$1">';
            $viewContent = preg_replace($this->method_match, $input, $this->viewContent);
            $this->viewContent = $viewContent;
        }

        return $this;
    }
}
