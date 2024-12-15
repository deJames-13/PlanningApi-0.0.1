<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticularQuarter extends Model
{
    use SoftDeletes;    
    
    protected $table = 'particular_values_quarter';
    protected $guarded = ['id'];

    public function particularValue()
    {
        return $this->belongsTo(ParticularValue::class);
    }


}
