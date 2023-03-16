<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\SoftDeletes;

use \App\Models\Children;
use \App\Models\Bookmark;
use \App\Models\Ratings;
use \App\Models\Order;

class User extends Authenticatable implements MustVerifyEmail {
    use HasApiTokens, HasFactory, Notifiable, HasUUID, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $dates = ['deleted_at'];
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'nik',
        'address',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot(){
        parent::boot();
    
        static::creating(function ($user) {
            $user->id = Str::uuid(36);
        });
    }

    public function childrens(): HasMany{
        return $this->hasMany(Children::class, 'parent_id', 'id');
    }

    public function bookmarks(): HasMany {
        return $this->hasMany(Bookmark::class, 'user_id', 'id');
    }

    public function ratings(): HasMany {
        return $this->hasMany(Rating::class, 'user_id', 'id');
    }

    public function orders(): HasMany {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }
}
