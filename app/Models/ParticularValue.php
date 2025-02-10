<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParticularValue extends Model
{
    use SoftDeletes;    
    protected $guarded = ['id'];

    
    // belongsTo: Particular
    public function particulars()
    {
        return $this->belongsTo(Particular::class);
    }

    // hasMany: ParticularQuarter
    public function quarters()
    {
        return $this->hasMany(ParticularQuarter::class);
    }
}
