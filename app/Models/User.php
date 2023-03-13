<?php

namespace App\Models;

use App\Models\Note;
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
