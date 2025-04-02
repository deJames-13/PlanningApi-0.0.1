<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'full_name',
        'type',
        'index',
        'related_to'
    ];

    protected $appends = ['text', 'value'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($department) {
            $department->slug = Str::slug($department->name);
        });
        
        static::updating(function ($department) {
            $department->slug = Str::slug($department->name);
        });
    }

    public function sectors()
    {
        return $this->hasMany(Sector::class)->orderBy('index', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo(Department::class, 'related_to');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'related_to')->orderBy('index', 'asc');
    }

    public function getTextAttribute()
    {
        return $this->name;
    }

    public function getValueAttribute()
    {
        return $this->id;
    }
}
