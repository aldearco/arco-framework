<?php

use Arco\Http\Request;
use Arco\Http\Response;
use Arco\Routing\Route;

Route::get("/", fn (Request $request) => Response::text("Arco Framework"));
Route::get("/form", fn (Request $request) => Response::view("form"));