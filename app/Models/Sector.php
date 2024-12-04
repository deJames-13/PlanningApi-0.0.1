<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sector extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    
    // belongsTo: Department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // has Many: Objective
    public function objectives()
    {
        return $this->hasMany(Objective::class);
    }

    // has Many: Budget
    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

}
