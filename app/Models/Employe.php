<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use \App\Models\Merchant;
use \App\Models\Position;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employe extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUUID, SoftDeletes;

    protected $fillable = [
        'merchant_id',
        'position_id',
        'name',
        'username',
        'password',
        'address',
        'age',
        'phone_number',
        'education'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function merchant(): BelongsTo {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }

    
    public function position(): BelongsTo {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }
}
