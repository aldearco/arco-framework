<?php

namespace App\Models;

use Arco\Auth\Authenticatable;

class User extends Authenticatable {
    protected array $hidden = [
        "password",
        "remember_token"
    ];

    protected array $fillable = [
        "name",
        "email",
        "password"
    ];
}
