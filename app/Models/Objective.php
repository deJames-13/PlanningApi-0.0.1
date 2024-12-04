<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Objective extends Model
{
    use SoftDeletes;    
    protected $guarded = ['id'];


    // belongsTo: Sector
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
    
    // has Many: ObjectiveQuarter
    public function quarter()
    {
        return $this->hasMany(ObjectiveQuarter::class);
    }
}
