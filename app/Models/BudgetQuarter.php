<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetQuarter extends Model
{
    use SoftDeletes;    
    protected $guarded = ['id'];
    // belongsTo: BudgetAnnual
    public function budgetAnnual()
    {
        return $this->belongsTo(BudgetAnnual::class);
    }
}
