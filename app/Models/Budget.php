<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use SoftDeletes;    
    
    protected $guarded = ['id'];
    // with annual and sector
    protected $with = ['annual', 'sector'];

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


         /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($budget) {
            $budget->slug = \Str::slug($budget->title);
        });
    }
}
