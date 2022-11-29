<?php 

require "./Router.php";

$router = new Router();

$router->get('/test', function () {
    return "GET OK";
});

$router->post('/test', function () {
    return "POST OK";
});

$action = $router->resolve();

print($action());