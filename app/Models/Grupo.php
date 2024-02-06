<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model {

    use HasFactory;

    protected $table = 'grupo';

    protected $fillable = [
        'id_lider',
        'nome',
        'code',
    ];

    public function lider() {
        return $this->belongsTo(User::class, 'id_lider');
    }
}
