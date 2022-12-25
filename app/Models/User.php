<?php

namespace App\Models;

use App\Models\Contact;
use Arco\Auth\Authenticatable;

class User extends Authenticatable {
    protected array $hidden = ["password"];

    protected array $fillable = [
        "name",
        "email",
        "password"
    ];

    public function contacts() {
        return $this->hasMany(Contact::class);
    }
}
