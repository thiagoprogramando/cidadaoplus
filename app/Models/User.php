<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Mail\Welcome;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable {
    
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'id_creator',
        'id_company',
        
        'name',
        'photo',
        'birth',
        'sex',
        'profession',

        'email',
        'phone',

        'instagram',
        'facebook',

        'postal_code',
        'address',
        'number',
        'city',
        'state',

        'obs',
        
        'type',
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

    public function typeLabel() {
        switch ($this->type) {
            case 1:
                return 'Administrador';
                break;
            case 2:
                return 'Apoiador';
                break;
            case 3:
                return 'Cidadão';
                break;
            case 4:
                return 'Coordenador';
                break;
            default:
                return '---';
                break;
        }
    }

    public function sexLabel() {
        switch ($this->sex) {
            case 1:
                return 'Masculino';
                break;
            case 2:
                return 'Feminino';
                break;
            case 3:
                return 'Outros';
                break;
            default:
                return '---';
                break;
        }
    }

    public function creator() {
        return $this->belongsTo(User::class, 'id_creator');
    }

    public function company() {
        return $this->belongsTo(User::class, 'id_company');
    }  

    protected static function boot() {

        parent::boot();

        static::created(function ($user) {
            if (!empty($user->email) && $user->validarEmail($user->email) != false) {
                Mail::to($user->email, $user->nome)->send(new Welcome([
                    'fromName'  => 'Cidadão Plus',
                    'fromEmail' => 'suporte@cidadaoplus.com.br',
                    'subject'   => 'Você foi cadastrado na pesquisa de área!',
                ]));
            }
        });
    }
}
