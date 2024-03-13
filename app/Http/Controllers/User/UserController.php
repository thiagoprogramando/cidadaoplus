<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Mail\Welcome;
use App\Models\User;

use Carbon\Carbon;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller {
    
    public function profile() {
        
        return view('App.User.profile');
    }

    public function profileUpdate(Request $request) {
        
        $user = User::find($request->id);
        if($user) {
            if($request->nome) {
                $user->nome = $request->nome;
            }
            if($request->password) {
                $user->password = $request->name;
            }
            if($request->cpf) {
                $user->cpf = str_replace(['.', ' ', ',', '-', '(', ')'], '', $request->cpf);
            }
            if($request->whatsapp) {
                $user->whatsapp = str_replace(['.', ' ', ',', '-', '(', ')'], '', $request->whatsapp);
            }
            if($request->email) {
                $user->email = strtolower($request->email);
            }
            if($request->password) {
                $user->password = bcrypt($request->password);
            }

            if($user->save()) {
                return redirect()->back()->with('success', 'Perfil atualizado com sucesso!');
            }

            return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
        }

        return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
    }

    public function listUser($tipo = null) {

        if($tipo) {
            $users = Auth::user()->tipo === 1 ? User::where('tipo', $tipo)->orderBy('created_at', 'desc')->get() : User::where('tipo', $tipo)->where('id_lider', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        } else {
            $users = Auth::user()->tipo === 1 ? User::all() : User::where('id_lider', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        }

        $alphas = Auth::user()->tipo == 1 ? User::whereIn('tipo', [1, 2, 4])->orderBy('created_at', 'desc')->get() : User::whereIn('tipo', [1, 2])->where('id_lider', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        return view('App.User.listUsers', ['users' => $users, 'tipo' => $tipo, 'alphas' => $alphas]);
    }

    public function listReport(Request $request) {

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
            $rede = $rede->totalUsers();
        } else {
            if(Auth::user()->tipo != 1) {
                $eleitores->where('id_lider', Auth::user()->id);
                $apoiadores->where('id_lider', Auth::user()->id);
                $coordenadores->where('id_lider', Auth::user()->id);
                $master->where('id_lider', Auth::user()->id);

                $rede = User::where('id', Auth::user()->id)->first();
                $rede = $rede->totalUsers();
            } else {
                $rede = 0;
            }
        }
        
        $eleitores      = $eleitores->get();
        $apoiadores     = $apoiadores->get();
        $coordenadores  = $coordenadores->get();
        $master         = $master->get();

        if(Auth::user()->tipo == 2 || Auth::user()->tipo == 4) {
            $alphas = User::whereIn('tipo', [2, 4])->where('id_lider', Auth::user()->id)->get();
        } else {
            $alphas = User::whereIn('tipo', [4, 2])->get();
        }
        
        return view('App.User.listReport', [
            'eleitores'     => $eleitores,
            'apoiadores'    => $apoiadores,
            'coordenadores' => $coordenadores,
            'master'        => $master,
            'alphas'        => $alphas,
            'rede'          => $rede
        ]);
    }

    public function filterUser(Request $request) {

        $query = User::query();

        if ($request->input('nome')) {
            $query->where('nome', 'like', '%' . $request->input('nome') . '%');
        }

        if ($request->input('dataNasc')) {
            $dataNasc = $request->input('dataNasc');
            $dataNascParts = explode('-', $dataNasc);

            if (count($dataNascParts) === 2) {
                $dia = $dataNascParts[0];
                $mes = $dataNascParts[1];

                $query->whereRaw("DAY(dataNasc) = $dia")
                    ->whereRaw("MONTH(dataNasc) = $mes");
            } elseif (count($dataNascParts) === 3) {
                $dia = $dataNascParts[0];
                $mes = $dataNascParts[1];
                $ano = $dataNascParts[2];

                $query->whereRaw("DAY(dataNasc) = $dia")
                    ->whereRaw("MONTH(dataNasc) = $mes")
                    ->whereRaw("YEAR(dataNasc) = $ano");
            } else {
                $dia = $dataNascParts[0];
                $query->whereRaw("DAY(dataNasc) = $dia");
            }
        }

        if(Auth::user()->tipo == 1 || Auth::user()->tipo == 2 || Auth::user()->tipo == 4) {
            if ($request->input('id_lider')) {
                $query->where('id_lider', $request->input('id_lider'));
            }
        } else {
            $query->where('id_lider', Auth::user()->id);
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
        
        $alphas = Auth::user()->tipo == 1 ? User::whereIn('tipo', [1, 2, 4])->orderBy('created_at', 'desc')->get() : User::whereIn('tipo', [1, 2])->where('id_lider', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        return view('App.User.listUsers', [
            'users'             => $users, 
            'tipo'              => 1, 
            'alphas'            => $alphas,
        ]);
    }

    public function createUserExternal(Request $request) {

        $validator = Validator::make($request->all(), [
            'nome'      => 'required',
            'whatsapp'  => 'required|unique:users,whatsapp',
            'dataNasc'  => 'required|date_format:d-m-Y',
            'email'     => 'nullable|email|unique:users,email',
        ], [
            'nome.required'         => 'Por favor, informe um Nome!',
            'whatsapp.required'     => 'Por favor, informe um WhatsApp!',
            'whatsapp.unique'       => 'Já existe uma Pessoa com esse WhatsApp!',
            'dataNasc.required'     => 'Por favor, informe uma Data de Nascimento!',
            'dataNasc.date_format'  => 'Formato de data de nascimento inválido. Use o formato DD-MM-AAAA!',
            'email.email'           => 'Formato de e-mail inválido!',
            'email.unique'          => 'Já existe uma Pessoa com esse E-mail!'
        ]);        

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } else {
            $user               = new User();
            $user->id_lider     = $request->id_lider;
            $user->nome         = $request->nome;
            $user->dataNasc     = Carbon::parse($request->dataNasc);
            $user->sexo         = $request->sexo;
            $user->profissao    = $request->profissao;
            $user->tipo         = 3;
            $user->email        = strtolower($request->email);
            $user->whatsapp     = $request->whatsapp;
            $user->instagram    = $request->instagram;
            $user->facebook     = $request->facebook;
            $user->cep          = $request->cep;
            $user->logradouro   = $request->logradouro;
            $user->numero       = $request->numero;
            $user->bairro       = $request->bairro;
            $user->cidade       = $request->cidade;
            $user->estado       = $request->estado;
            $user->password     = bcrypt(str_replace(['.', ' ',',', '-', '(', ')'], '', $request->whatsapp));

            if($user->save()) {

                return redirect('https://api.whatsapp.com/send?phone=5584987674348&text=Ol%C3%A1,%20acabei%20de%20me%20cadastrar%20via%20site%20e%20gostaria%20de%20conhecer%20os%20projetos%20do%20vereador%20Kleber%C2%A0Fernandes!');
            }
        }
    }

    private function validarEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public function viewUser($id) {

        $user = User::where('id', $id)->first();
        $alphas = User::whereIn('tipo', [1, 2, 4])->orderBy('created_at', 'desc')->get();
        if($user) {
            return view('App.User.updateUser', ['user' => $user, 'alphas' => $alphas]);
        }
        
        return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
    }

    public function view($id) {

        $user           = User::where('id', $id)->first();
        if($user) {

            $eleitores      = User::where('id_lider', $user->id)->where('tipo', 3)->count();
            $apoiadores     = User::where('id_lider', $user->id)->where('tipo', 2)->count();
            $coordenadores  = User::where('id_lider', $user->id)->where('tipo', 4)->count();
            $todos          = User::where('id_lider', $user->id)->orderBy('created_at', 'desc')->get();

            return view('App.User.viewUser', [
                'user'          => $user, 
                'eleitores'     => $eleitores, 
                'apoiadores'    => $apoiadores, 
                'coordenadores' => $coordenadores, 
                'todos'         => $todos
            ]);
        }
        
        return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
    }

    public function updateUser(Request $request) {

        if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $request->dataNasc)) {
            return redirect()->back()->with('error', 'Data de Nascimento enviada incorreta! Formato correto: DD-MM-AAAA');
        }

        $user = User::where('id', $request->id)->first();
        if($user) {
            $user->id_lider     = $request->id_lider;
            $user->nome         = $request->nome;
            $user->profissao    = $request->profissao;
            $user->dataNasc     = Carbon::parse($request->dataNasc);
            $user->sexo         = $request->sexo;
            $user->tipo         = $request->tipo;
            $user->email        = strtolower($request->email);
            $user->whatsapp     = str_replace(['.', ',', '-', '(', ')'], '', $request->whatsapp);
            $user->instagram    = $request->instagram;
            $user->facebook     = $request->facebook;
            $user->cep          = $request->cep;
            $user->logradouro   = $request->logradouro;
            $user->numero       = $request->numero;
            $user->bairro       = $request->bairro;
            $user->cidade       = $request->cidade;
            $user->estado       = $request->estado;
            if($request->password) {
                $user->password = bcrypt(str_replace(['.', ',', '-', '(', ')'], '', $request->password));
            }

            if($user->save()) {
                return redirect()->back()->with('success', 'Dados atualizados com sucesso!');
            }
        }
        
        return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
    }

    public function deleteUser(Request $request) {

        $password = $request->password;    
        if (Hash::check($password, auth()->user()->password)) {
           
            $user = User::find($request->id);
            if ($user) {

                $user->delete();
                return redirect()->back()->with('success', 'Usuário excluído com sucesso!');
            } else {
                return redirect()->back()->with('error', 'Não encontramos dados do Usuário, tente novamente mais tarde!');
            }
        } else {
            return redirect()->back()->with('error', 'Senha incorreta!');
        }
    }

    public function importUser(Request $request) {

        $password = $request->password;    
        if (Hash::check($password, auth()->user()->password)) {
           
            $file = $request->file('arquivo');
            Excel::import(new UsersImport, $file);

            return redirect()->back()->with('success', 'Importação concluída com Sucesso!');
        } else {
            return redirect()->back()->with('error', 'Senha inválida!');
        }
    }
}
