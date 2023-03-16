<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use \App\Models\Basemodel;
use \App\Models\User;
use \App\Models\Service;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["user_id", "service_id", "children_id", "note", "status", "order_at"];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function service() {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function rating(): HasOne{
        return $this->hasOne(Rating::class); 
    }
}
