<?php

namespace Arco\Container\Utilities;

trait Testable {
    protected bool $isTest = false;

    protected function isTest() {
        return $this->isTest;
    }

    public function runningTests() {
        $this->isTest = true;
    }
}
