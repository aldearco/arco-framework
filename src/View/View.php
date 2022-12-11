<?php

namespace Arco\View;

interface View {
    public function render(string $string, array $params = [], string $layout = null): string;
}
