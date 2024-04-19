<?php

namespace App\Imports;

use App\Models\User;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow {

    public function model(array $row) {

        if (!empty(array_filter($row, function($value) {
            return $value !== null;
        }))) {

            $existingUser = User::where('whatsapp', $row['whatsapp'])->first();
            if ($existingUser) {
                return null;
            }

            return new User([
                'id_lider'    => $row['id_lider'] ?? null,
                'nome'        => $row['nome'] ?? null,
                'dataNasc'    => isset($row['datanasc']) ? \DateTime::createFromFormat('d/m/Y', $row['datanasc'])->format('Y-m-d') : null,
                'whatsapp'    => isset($row['whatsapp']) ? str_replace(['.', ' ', ',', '-', '(', ')'], '', $row['whatsapp']) : null,
                'email'       => $row['email'] ?? null,
                'cep'         => str_replace(['.', ' ', ',', '-', '(', ')'], '', $row['cep']) ?? null,
                'numero'      => $row['n'] ?? null,
                'bairro'      => $row['bairro'] ?? null,
                'cidade'      => $row['cidade'] ?? null,
                'estado'      => $row['estado'] ?? null,
                'logradouro'  => $row['logradouro'] ?? null,
                'tipo'        => $row['tipo'] ?? null,
                'sexo'        => $row['sexo'] ?? null,
                'observacao'  => $row['observacao'] ?? null,
                'password'    => isset($row['whatsapp']) ? bcrypt(str_replace(['.', ' ', ',', '-', '(', ')'], '', $row['whatsapp'])) : bcrypt('123456'),
            ]);
        }
        
        return null;
    }

    public function startRow(): int {
        return 2;
    }
}
