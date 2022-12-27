<?php

namespace Arco\View\Fletcher;

use Arco\View\Exceptions\MissingTagException;

trait Content {
    public string $startContentTag = "@content";

    public string $endContentTag = "@endcontent";

    public function extractContent(): static {
        $start = strpos($this->viewContent, $this->startContentTag);

        if ($start === false) {
            $this->viewContent = $this->viewContent;

            return $this;
        }

        $end = strpos($this->viewContent, $this->endContentTag, $start);

        if ($end === false) {
            throw new MissingTagException("Content end tag '$this->endContentTag' is missing in your view.");
        }

        $content = substr($this->viewContent, $start + strlen($this->startContentTag), $end - $start - strlen($this->startContentTag));
        
        $this->viewContent = $content;

        return $this;
    }

}
