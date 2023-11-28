<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Processfile extends Model
{
    use HasFactory, HasUuids;


    public $fillable = [
        "filename",
        'path',
        "user_id"
    ];

    protected $keyType = "string";
    public $incrementing = false;

    
}
