<?php 

return [
    "csrf" => [
        "enabled" => true,
        "middleware" => App\Middlewares\VerifyCsrfToken::class
    ]
];