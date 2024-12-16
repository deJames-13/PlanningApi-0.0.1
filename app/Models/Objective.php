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
    public function quarters()
    {
        return $this->hasMany(ObjectiveQuarter::class);
    }

    public function getTotal()
    {
        $total = [
            'target' => $this->quarters->sum('target'),
            'accomplishment' => $this->quarters->sum('accomplishment'),
            'percentage' => 0,
        ];
        $total['percentage'] = $total['target'] > 0 ? round(($total['accomplishment'] / $total['target']) * 100, 2) : 0;
        return $total;
    }
}
