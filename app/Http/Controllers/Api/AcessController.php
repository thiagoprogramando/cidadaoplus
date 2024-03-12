<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;

use Illuminate\Http\Request;

class AcessController extends Controller {
    

    public function login(Request $request) {

        $user = User::where('email', $request->email)->first();
        if($user) {
            return [
                'status'    => 200,
                'nome'      => $user->nome,
                'email'     => $user->email,
                'whatsapp'  => $user->whatsapp,
                'tipo'      => $user->tipo,
                'lider'     => $user->id_lider,
                'id'        => $user->id
            ];
        }

        return [
            'message' => 'Crendenciais inválidas!',
            'status'  => 401
        ];
    }

    public function users(Request $request) {

        $query = User::query();

        if ($request->input('nome')) {
            $query->where('nome', 'like', '%' . $request->input('nome') . '%');
        }

        if ($request->input('data_nascimento')) {
            $data_nascimento = $request->input('data_nascimento');
            $data_nascimentoParts = explode('-', $data_nascimento);

            if (count($data_nascimentoParts) === 2) {
                $dia = $data_nascimentoParts[0];
                $mes = $data_nascimentoParts[1];

                $query->whereRaw("DAY(data_nascimento) = $dia")
                    ->whereRaw("MONTH(data_nascimento) = $mes");
            } elseif (count($data_nascimentoParts) === 3) {
                $dia = $data_nascimentoParts[0];
                $mes = $data_nascimentoParts[1];
                $ano = $data_nascimentoParts[2];

                $query->whereRaw("DAY(data_nascimento) = $dia")
                    ->whereRaw("MONTH(data_nascimento) = $mes")
                    ->whereRaw("YEAR(data_nascimento) = $ano");
            } else {
                $dia = $data_nascimentoParts[0];
                $query->whereRaw("DAY(data_nascimento) = $dia");
            }
        }

        if ($request->input('id_lider') && $request->input('id_lider') != 0) {
            $query->where('id_lider', $request->input('id_lider'));
        }

        if ($request->input('tipo')) {
            $query->where('tipo', $request->input('tipo'));
        }

        if ($request->input('sexo')) {
            $query->where('sexo', $request->input('sexo'));
        }

        if ($request->input('profissao')) {
            $query->where('profissao', $request->input('profissao'));
        }

        if ($request->input('cep')) {
            $query->where('cep', $request->input('cep'));
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        return [
            'status'    => 200,
            'users'      => $users,
        ];
    }

    public function data(Request $request) {

        $eleitores      = User::where('tipo', 3);
        $apoiadores     = User::where('tipo', 2);
        $coordenadores  = User::where('tipo', 4);
        $master         = User::where('tipo', 1);

        
        if($request->input('id_lider')) {
            $id_lider = $request->input('id_lider');

            $eleitores->where('id_lider', $id_lider);
            $apoiadores->where('id_lider', $id_lider);
            $coordenadores->where('id_lider', $id_lider);
            $master->where('id_lider', $id_lider);

            $rede = User::where('id', $id_lider)->first();
            if($rede) {
                $rede = $rede->totalUsers();
            } else {
                $rede = 0;
            }
            
        }
        
        return [
            'eleitores'      => $eleitores->count(),
            'apoiadores'     => $apoiadores->count(),
            'coordenadores'  => $coordenadores->count(),
            'master'         => $master->count(),
            'rede'          => $rede
        ];
    }

}
