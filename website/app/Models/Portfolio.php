<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portfolio extends Model
{
    use HasFactory, SoftDeletes;
    public function user(){
        return $this->belongsTo(User::class, 'user_id','id');
    }
    public function segment(){
        return $this->belongsTo(Segment::class, 'segment_id','id');
    }
    public function script(){
        return $this->belongsTo(Script::class, 'script_id','id');
    }
    public function expiry(){
        return $this->belongsTo(ExpiryDate::class, 'expiry_date_id','id');
    }
}
