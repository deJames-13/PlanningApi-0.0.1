<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Particular extends Model
{
    use SoftDeletes;    
    protected $guarded = ['id'];

    // belongsTo: BarData
    public function barData()
    {
        return $this->belongsTo(BarData::class);
    }

    // has Many: ParticularValue
    public function values()
    {
        return $this->hasMany(ParticularValue::class);
    }
}
