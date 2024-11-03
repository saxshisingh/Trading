<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Trading extends Model
{
    use HasFactory, SoftDeletes;

    public function segment(){
        return $this->belongsTo(Segment::class, 'segment_id','id');
    }
    public function script(){
        return $this->belongsTo(Script::class, 'script_id', 'id');
    }
    public function order(){
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function wallet(){
        return $this->hasOne(Wallet::class, 'trade_id', 'id');
    }
    public function expiry(){
        return $this->belongsTo(ExpiryDate::class, 'expiry_date_id', 'id');
    }
    public function data(){
        return $this->belongsTo(TradeData::class, 'order_id', 'order_id');
    }
    public function position()
    {
        return $this->belongsTo(Position::class,'position_id','id');
    }
}
