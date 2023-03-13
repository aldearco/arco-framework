<?php 

return [
    "csrf" => [
        "enabled" => true,
        "middleware" => App\Http\Middlewares\VerifyCsrfToken::class
    ],
    /**
     * Error responses.
     * Must be Arco\Http\Response object.
     */
    "errors" => [
        "404" => Arco\Http\Response::text("Not found")->setStatus(404),
        "500" => Arco\Http\Response::text("Internal Server Error")->setStatus(500),
        "csrf" => Arco\Http\Response::text("CSRF Token Not Valid")->setStatus(403),
    ]
];