<?php 

return [
    "boot" => [
        Arco\Providers\ServerServiceProvider::class,
        Arco\Providers\DatabaseDriverServiceProvider::class,
        Arco\Providers\SessionStorageServiceProvider::class,
        Arco\Providers\ViewServiceProvider::class,
        Arco\Providers\AuthenticatorServiceProvider::class,
        Arco\Providers\HasherServiceProvider::class,
        Arco\Providers\FileStorageDriverServiceProvider::class,
    ],
    "runtime" => [
        App\Providers\RuleServiceProvider::class,
        App\Providers\RouteServiceProvider::class
    ]
];