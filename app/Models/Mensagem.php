<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model {

    use HasFactory;

    protected $table = 'mensagem';

    protected $fillable = [
        'id_whatsapp',
        'texto',
        'base64',
        'phone_number_id',
        'user_access_token',
        'status',
    ];
}
