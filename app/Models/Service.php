<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

use \App\Models\BaseModel;
use \App\Models\Service_Header;
use \App\Models\Merchant;
use \App\Models\Bookmark;
use \App\Models\Ratings;
use \App\Models\Order;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["merchant_id", "service_header_id", "name", "desc", "price", "image_path"];

    public function service_header(): BelongsTo {
        return $this->belongsTo(Service_Header::class, 'service_header_id', 'id');
    }

    public function merchant(): BelongsTo {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }

    public function bookmarks(): HasMany {
        return $this->hasMany(Bookmark::class, 'service_id', 'id');
    }

    public function ratings(): HasMany {
        return $this->hasMany(Rating::class, 'service_id', 'id');
    }

    public function orders(): HasMany {
        return $this->hasMany(Order::class, 'service_id', 'id');
    }
}
