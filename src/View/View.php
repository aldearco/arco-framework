<?php

namespace Arco\View;

interface View {
    public function render(string $string): string;
}
