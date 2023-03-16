<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use \App\Models\BaseModel;
use \App\Models\Order;
use \App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["user_id", "order_id", "notes", "point", "scale"];

    public function order(): HasOne {
        return $this->hasOne(Order::class, 'order_id', 'id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
}
