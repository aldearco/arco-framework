<?php 

return [
    "csrf" => [
        "enabled" => true,
        "middleware" => App\Http\Middlewares\VerifyCsrfToken::class
    ]
];