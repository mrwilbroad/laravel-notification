<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUniqueIds;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotificationMessage extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'user_id',
        'message',
    ];

    protected $keyType = "string";
    public $incrementing = false;

}
