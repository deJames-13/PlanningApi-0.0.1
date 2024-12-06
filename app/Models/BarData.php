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

    // helpers
    public function mapItem()
    {
        return [
            'id' => $this->id,
            'name' => $this->title,
            'description' => $this->description,
            'indicators' => isset($this->particular) ? $this->particular->map(function ($particular) {
                return $this->mapParticular($particular);
            }) : [],
        ];
    }

    public function mapParticular($particular)
    {
        return [
            'id' => $particular->id,
            'name' => $particular->title,
            'description' => $particular->description,
            'values' => isset($particular->values) ? $particular->values->map(function ($value) {
                return $this->mapValue($value);
            }) : [],
        ];
    }

    public function mapValue($value)
    {
        return [
            'id' => $value->id,
            'year' => $value->year,
            'target' => $value->target,
            'accomplishment' => $value->accomplishment,
        ];
    }
}
