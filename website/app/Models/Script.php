<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Script extends Model
{
    use HasFactory, SoftDeletes;
    public function segment(){
        return $this->belongsTo(Segment::class, 'segment_id','id');
    }
}
