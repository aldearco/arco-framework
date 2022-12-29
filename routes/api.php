<?php 

use Arco\Routing\Route;

Route::get("/api", fn () => json(["message" => "Arco API"]));