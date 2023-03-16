<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model {
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    
    public static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            $model->id = Str::uuid(36);
        });
    }
}
