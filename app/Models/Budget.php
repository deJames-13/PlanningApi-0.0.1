<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use SoftDeletes;    
    
    protected $guarded = ['id'];


    // belongsTo: Sector
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    // has Many: BudgetAnnual
    public function annual()
    {
        return $this->hasMany(BudgetAnnual::class);
    }
}
