<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use \App\Models\BaseModel;
use \App\Models\Employe;

class Position extends BaseModel
{
    use HasFactory;

    protected $fillable = ['position'];

    public function employes(): HasMany{
        return $this->hasMany(Employe::class, 'position_id', 'id');
    }
}
