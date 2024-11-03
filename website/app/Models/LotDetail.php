<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotDetail extends Model
{
    use HasFactory;

    public function segment(){
        return $this->belongsTo(Segment::class, 'segment_id','id');
    }
    public function script(){
        return $this->belongsTo(Script::class, 'script_id','id');
    }
    
}
