<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion_request extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'reason', 'user_id'];

    public function users(){
        return $this ->belongsTo(User::class);
    }
}
