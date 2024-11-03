<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiteToken extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class, 'user_id','id');
    }
    protected $fillable = [
        'user_id', 'access_token',
    ];
}
