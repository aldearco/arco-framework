<?php

namespace App\Models;

use App\Models\Note;
use Arco\Auth\Authenticatable;

class User extends Authenticatable {
    protected array $hidden = ["password"];

    protected array $fillable = [
        "name",
        "email",
        "password"
    ];

    public function notes() {
        return $this->hasMany(Note::class);
    }
}
