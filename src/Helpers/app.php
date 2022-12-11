<?php

use Arco\App;
use Arco\Container\Container;

function app($class = App::class) {
    return Container::resolve($class);
}

function singleton(string $class) {
    return Container::singleton($class);
}
