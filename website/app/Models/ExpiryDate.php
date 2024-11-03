<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpiryDate extends Model
{
    use HasFactory;

    protected $table = "expiry_date";
    public function script(){
        return $this->belongsTo(Script::class);
    }

    public function log(){
        return $this->belongsTo(WatchlistLog::class,'log_id','id');
    }
}
