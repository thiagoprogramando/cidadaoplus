<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Str;

class UsersImport implements ToModel, WithHeadingRow {

    public function model(array $row) {

        if (!empty(array_filter($row))) {

            $row = array_filter($row);
            if (!empty($row)) {
                return new User([
                    'id_lider'    => $row['id_lider'],
                    'nome'        => $row['nome'],
                    'dataNasc'    => \Carbon\Carbon::parse($row['d_nascimento'])->toDateString(),
                    'whatsapp'    => str_replace(['.', ' ', ',', '-', '(', ')'], '', $row['whatsapp']),
                    'email'       => $row['email'],
                    'cep'         => $row['cep'],
                    'numero'      => $row['n'],
                    'bairro'      => $row['bairro'],
                    'cidade'      => $row['cidade'],
                    'estado'      => $row['estado'],
                    'tipo'        => $row['tipo'],
                    'password'    => bcrypt(str_replace(['.', ' ', ',', '-', '(', ')'], '', $row['whatsapp'])),
                ]);
            }
        
            return null;
        }

        return null; 
    }

    public function startRow(): int {
        return 2;
    }
}
