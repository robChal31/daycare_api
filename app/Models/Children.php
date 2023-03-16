<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

use \App\Models\BaseModel;
use \App\Models\User;

class Children extends BaseModel{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'age', 'gender', 'parent_id'];

    public function user(): HasOne {
        return $this->belongsTo(User::class, 'parent_id', 'id');
    }

}
