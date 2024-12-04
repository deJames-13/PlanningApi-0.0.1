<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarData extends Model
{
    use SoftDeletes;    
    
    protected $table = 'bar_datas';
    protected $guarded = ['id'];

    // has Many: Particular
    public function particular()
    {
        return $this->hasMany(Particular::class);
    }
}
