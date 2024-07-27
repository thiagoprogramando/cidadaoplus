<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection {

    protected $users;

    public function __construct($users) {
        $this->users = $users;
    }

    public function collection() {
        // return $this->users;
        return $this->users->map(function($user) {
            return [
                'Apoiador'              => optional($user->lider)->nome ?: '---',
                'Nome'                  => $user->nome,
                'Whatsapp'              => $user->whatsapp,
                'Email'                 => $user->email,
                'Data de Criação'       => $user->created_at,
                'Data de Nascimento'    => $user->dataNasc,
                'CEP'                   => $user->cep,
                'Bairro'                => $user->bairro,
                'Observação'            => $user->observacao
            ];
        });
    }

    public function headings(): array {
        return [
            'Nome', 'Whatsapp', 'Email', 'Data de Cadastro', 'Data de Nascimento', 'CEP', 'Bairro', 
        ];
    }
}
