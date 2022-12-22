<?php

namespace App\Models;

use App\Models\User;
use Arco\Database\Archer\Model;

class Note extends Model {
    public function user() {
        return $this->belongsTo(User::class);
    }
}
