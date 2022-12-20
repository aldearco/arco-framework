<?php 

return [
    "boot" => [
        Arco\Providers\ServerServiceProvider::class,
        Arco\Providers\DatabaseDriverServiceProvider::class,
        Arco\Providers\SessionStorageServiceProvider::class,
        Arco\Providers\ViewServiceProvider::class,
    ],
    "runtime" => [
        App\Providers\RuleServiceProvider::class,
        App\Providers\RouteServiceProvider::class
    ]
];