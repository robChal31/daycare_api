<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use \App\Models\BaseModel;
use \App\Models\User;
use \App\Models\Service;

class Bookmark extends BaseModel
{
    use HasFactory;

    protected $fillable = ['service_id', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function service() {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
}
