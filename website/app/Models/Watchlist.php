<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Watchlist extends Model
{
    use HasFactory, SoftDeletes;
    public function user(){
        return $this->belongsTo(Script::class);
    }
    public function segment(){
        return $this->belongsTo(Segment::class);
    }
    public function script(){
        return $this->belongsTo(Script::class);
    }
    public function expiry(){
        return $this->belongsTo(ExpiryDate::class, 'expiry_date_id','id');
    }
    public function logs(){
        return $this->belongsTo(WatchlistLog::class, 'logs_id','id');
    }
}
