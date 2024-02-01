<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Whatsappp extends Model {

    use HasFactory;

    protected $table = 'whatsapp';

    protected $fillable = [
        'instanceName',
        'webhookUrl',
        'tokenKey',
        'statusCode',
        'status',
    ];
}
