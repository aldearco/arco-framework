<?php

namespace App\Models;

use App\Models\User;
use Arco\Database\Archer\Model;

class Note extends Model {
    //

    public function users() {
        return $this->belongsToMany(User::class, 'notes_users', 'user_id', 'note_id', 'id', 'id');
    }
}
