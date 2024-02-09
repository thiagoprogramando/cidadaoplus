<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use Illuminate\Support\Str;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'id_lider',
        
        'nome',
        'foto',
        'dataNasc',
        'sexo',
        'profissao',

        'email',
        'whatsapp',
        'instagram',
        'facebook',

        'cep',
        'logradouro',
        'numero',
        'bairro',
        'cidade',
        'estado',
        
        'tipo',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getTypeAttribute() {
        return $this->attributes['tipo'] == 1 ? 'Master' : ($this->attributes['tipo'] == 2 ? 'Apoiador' : ($this->attributes['tipo'] == 4 ? 'Coordenador' : 'Eleitor'));
    }

    public function getSexualidadeAttribute() {
        return $this->attributes['sexo'] == 1 ? 'Masc' : ($this->attributes['sexo'] == 2 ? 'Fem' : 'Outros');
    }

    public function lider() {
        return $this->belongsTo(User::class, 'id_lider');
    }

    public function grupo() {
        return $this->belongsTo(Grupo::class, 'id_grupo');
    }

    public function getDataFormatadaAttribute() {
        if ($this->dataNasc) {
            return Carbon::parse($this->dataNasc)->format('d-m-Y');
        }
        
        return null;
    }

    public function getWhatsappFormatadoAttribute() {
        if ($this->whatsapp) {
            $numero = Str::of($this->whatsapp)->replaceMatches('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3');
            return $numero;
        }
        
        return null;
    }

    
}
