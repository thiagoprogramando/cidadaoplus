<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Whatsappp extends Model {

    use HasFactory;

    protected $table = 'whatsapp';

    protected $fillable = [
        'name',
        'webhookUrl',
        'phone_number_id',
        'user_access_token',
        'status',
    ];
}
