<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetAnnual extends Model
{
    use SoftDeletes;    
    protected $guarded = ['id'];
    
    // belongsTo: Budget
    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    // has Many: BudgetQuarter
    public function quarter()
    {
        return $this->hasMany(BudgetQuarter::class);
    }
}
