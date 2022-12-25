<?php

namespace Arco\View\Fletcher;

trait Spoofing {
    /**
     * Regex of method matching
     *
     * @var string
     */
    public string $method_match = '/@method->(PUT|PATCH|DELETE)/';

    /**
     * Parse `$viewContent` (PHP or HTML code) if matches `@method->PUT|PATCH|DELETE`
     *
     * @param string $viewContent
     * @return string
     */
    public function spoofingParse(string $viewContent): string {
        if (preg_match($this->method_match, $viewContent)) {
            $input = '<input type="text" name="_method" value="$1">';
            $viewContent = preg_replace($this->method_match, $input, $viewContent);
        }

        return $viewContent;
    }
}
