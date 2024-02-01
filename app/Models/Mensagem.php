<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model {

    use HasFactory;

    protected $table = 'mensagem';

    protected $fillable = [
        'texto',
        'tokenKey',
        'base64',
        'numero',
        'status',
    ];
}
