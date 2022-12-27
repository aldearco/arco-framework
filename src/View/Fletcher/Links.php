<?php

namespace Arco\View\Fletcher;

use Arco\View\Exceptions\MissingTagException;

trait Links {
    public string $stylesTag = "@styles";

    public string $startStylesTag = "@styles";

    public string $endStylesTag = "@endstyles";

    public string $styles;

    public string $scriptsTag = "@scripts";

    public string $startScriptsTag = "@scripts";

    public string $endScriptsTag = "@endscripts";

    public string $scripts;

    public function getStyles(): static {
        $start = strpos($this->viewContent, $this->startStylesTag);

        if ($start === false) {
            $this->styles = "";
            return $this;
        }

        $end = strpos($this->viewContent, $this->endStylesTag, $start);

        if ($end === false) {
            throw new MissingTagException("Styles end tag '$this->endStylesTag' is missing in your view.");
        }

        $styles = substr($this->viewContent, $start + strlen($this->startStylesTag), $end - $start - strlen($this->startStylesTag));
        
        $this->styles = $styles;

        $content = substr_replace($this->viewContent, '', $start, $end + strlen($this->endStylesTag) - $start);

        $this->viewContent = $content;

        return $this;
    }

    public function setStlyes(): static {
        $this->layoutContent = str_replace($this->stylesTag, $this->styles, $this->layoutContent);
        return $this;
    }

    public function getScripts(): static {
        $start = strpos($this->viewContent, $this->startScriptsTag);

        if ($start === false) {
            $this->scripts = "";
            return $this;
        }

        $end = strpos($this->viewContent, $this->endScriptsTag, $start);

        if ($end === false) {
            throw new MissingTagException("Scripts end tag '$this->endScriptsTag' is missing in your view.");
        }

        $scripts = substr($this->viewContent, $start + strlen($this->startScriptsTag), $end - $start - strlen($this->startScriptsTag));
        
        $this->scripts = $scripts;

        $content = substr_replace($this->viewContent, '', $start, $end + strlen($this->endScriptsTag) - $start);

        $this->viewContent = $content;

        return $this;
    }

    public function setScripts(): static {
        $this->layoutContent = str_replace($this->scriptsTag, $this->scripts, $this->layoutContent);
        return $this;
    }


}
