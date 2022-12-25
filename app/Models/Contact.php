<?php 

namespace App\Models;

use App\Models\User;
use Arco\Database\Archer\Model;

class Contact extends Model {
    protected array $fillable = [
        "name",
        "phone_number",
        "user_id"
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}