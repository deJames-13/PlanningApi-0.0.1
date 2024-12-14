<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    
    // has Many: Sector
    public function sectors()
    {
        return $this->hasMany(Sector::class);
    }
    /**
    * The "booted" method of the model.
    *
    * @return void
    */
   protected static function boot()
   {
       parent::boot();
       static::saving(function ($department) {
           $department->slug = \Str::slug($department->name);
       });
   }
}
