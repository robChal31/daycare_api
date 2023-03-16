<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use \App\Models\BaseModel;
use \App\Models\Service;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceHeader extends BaseModel
{
    use HasFactory, SoftDeletes;
    protected $table = 'service_headers';

    protected $fillable = ['name', 'desc'];

    public function services(): HasMany{
        return $this->hasMany(Service::class, 'service_header_id', 'id');
    }
}
