<?php 

namespace App\Providers;

use Arco\Validation\Rule;
use Arco\Providers\ServiceProvider;

class RuleServiceProvider implements ServiceProvider {
    public function registerServices() {
        Rule::loadDefaultRules();
    }
}