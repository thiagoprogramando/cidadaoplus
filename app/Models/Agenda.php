<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Agenda extends Model {

    use HasFactory;

    protected $table = 'agenda';

    protected $fillable = [
        'id_criador',
        'id_lider',
        'id_grupo',
        'nome',
        'descricao',
        'data',
        'hora',
    ];

    public function getDataFormatadaAttribute() {
        if ($this->data) {
            return Carbon::parse($this->data)->format('d-m-Y');
        }
        
        return null;
    }

    public function lider() {
        return $this->belongsTo(User::class, 'id_lider');
    }
}
