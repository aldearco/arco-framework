<?php

namespace Arco\View\Fletcher;

use Arco\View\Exceptions\MissingTagException;

trait Content {
    public string $startContentTag = "@content";

    public string $endContentTag = "@endcontent";

    public function extractContent(string $file): self {
        $start = strpos($file, "$this->startContentTag");

        if ($start === false) {
            throw new MissingTagException("Content start tag '$this->startContentTag' is missing in your file view.");
        }

        $end = strpos($file, $this->endContentTag, $start);

        if ($end === false) {
            throw new MissingTagException("Content end tag '$this->endContentTag' is missing in your file view.");
        }

        $content = substr($file, $start + strlen($this->startContentTag), $end - $start - strlen($this->startContentTag));
        
        $this->viewContent = $content;

        return $this;
    }

}
