<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use \App\Models\Employe;
use \App\Models\Service;

class Merchant extends Authenticatable{
    use HasApiTokens, HasFactory, Notifiable, HasUUID, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_branch',
        'center_id',
        'address1',
        'address2',
        'phone_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function employes(): HasMany{
        return $this->hasMany(Employe::class, 'merchant_id', 'id');
    }

    public function services(): HasMany{
        return $this->hasMany(Service::class, 'merchant_id', 'id');
    }
}
