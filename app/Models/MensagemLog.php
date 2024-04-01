<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MensagemLog extends Model {

    use HasFactory;

    protected $table = 'mensagem_log';

    protected $fillable = [
        'id_mensagem',
        'resposta',
        'numero',
        'status',
    ];

}
