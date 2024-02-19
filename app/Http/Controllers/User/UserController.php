<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\User;

use Carbon\Carbon;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
            $users = Auth::user()->tipo === 1 ? User::where('tipo', $tipo)->get() : User::where('tipo', $tipo)->where('id_lider', Auth::user()->id)->get();
        } else {
            $users = Auth::user()->tipo === 1 ? User::all() : User::where('id_lider', Auth::user()->id)->get();
        }

        $alphas = Auth::user()->tipo == 1 ? User::whereIn('tipo', [1, 2, 4])->get() : User::whereIn('tipo', [1, 2])->where('id_lider', Auth::user()->id)->get();

        return view('App.User.listUsers', ['users' => $users, 'tipo' => $tipo, 'alphas' => $alphas]);
    }

    public function filterUser(Request $request) {

        $query = User::query();

        if ($request->input('nome')) {
            $query->where('nome', 'like', '%' . $request->input('nome') . '%');
        }

        if ($request->input('dataNasc')) {
            $query->where('dataNasc', '=', Carbon::parse($request->input('dataNasc')));
        }

        if ($request->input('id_lider')) {
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

        $users = $query->get();
        
        $alphas = Auth::user()->tipo == 1 ? User::whereIn('tipo', [1, 2, 4])->get() : User::whereIn('tipo', [1, 2])->where('id_lider', Auth::user()->id)->get();

        return view('App.User.listUsers', ['users' => $users, 'tipo' => 1, 'alphas' => $alphas]);
    }

    public function registrerUser($tipo) {

        $users = Auth::user()->tipo == 1 ? User::whereIn('tipo', [1, 2])->get() : User::whereIn('tipo', [1, 2])->where('id_lider', Auth::user()->id)->get();
        return view('App.User.registrerUser', ['tipo' => $tipo, 'users' => $users]);
    }

    public function createUser(Request $request) {

        $rules = [
            'nome'      => 'required|string',
            'tipo'      => 'required',
            'id_lider'  => 'required',
            'email'     => 'email|unique:users',
            'whatsapp'  => 'required'
        ];

        $messages = [
            'nome.required'     => 'O campo Nome é obrgatório!',
            'tipo.required'     => 'Informe um tipo de usuário!',
            'id_lider.required' => 'Informe um Líder!',
            'email.email'       => 'Por favor, informe um Email válido',
            'email.unique'      => 'Já existe uma pessoa com esse Email',
            'whatsapp.required' => 'Por favor, informe um WhatsApp!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors());
        }

        $user = User::where('whatsapp', str_replace(['.', ' ', ',', '-', '(', ')'], '', $request->whatsapp))->first();
        if($user) {
            return redirect()->back()->with('error', 'Já existe uma Pessoa com esse Whatsapp!');
        }

        $user = User::where('email', $request->email)->first();
        if($user) {
            return redirect()->back()->with('error', 'Já existe uma Pessoa com esse Email!');
        }

        if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $request->dataNasc)) {
            return redirect()->back()->with('error', 'Data de Nascimento enviada incorreta!');
        }

        $user               = new User();
        $user->id_lider     = $request->id_lider;
        $user->nome         = $request->nome;
        $user->dataNasc     = Carbon::parse($request->dataNasc);
        $user->sexo         = $request->sexo;
        $user->tipo         = $request->tipo;
        $user->email        = strtolower($request->email);
        $user->profissao    = $request->profissao;
        $user->whatsapp     = str_replace(['.', ' ', ',', '-', '(', ')'], '', $request->whatsapp);
        $user->instagram    = $request->instagram;
        $user->facebook     = $request->facebook;
        $user->cep          = $request->cep;
        $user->logradouro   = $request->logradouro;
        $user->numero       = $request->numero;
        $user->bairro       = $request->bairro;
        $user->cidade       = $request->cidade;
        $user->estado       = $request->estado;
        $user->password     = bcrypt(str_replace(['.', ' ', ',', '-', '(', ')'], '', $request->whatsapp));

        if($user->save()) {
            return redirect()->route('listUser', ['tipo' => $request->tipo])->with('success', 'Cadastro realizado com sucesso!');
        }
        
        return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
    }

    public function createUserExternal(Request $request) {

        $rules = [
            'nome'      => 'required',
            'whatsapp'  => 'required'
        ];

        $messages = [
            'nome.required'     => 'O campo Nome é obrgatório!',
            'whatsapp.required' => 'Por favor, informe um WhatsApp!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->errors());
        }

        $user = User::where('whatsapp', str_replace(['.', ' ', ',', '-', '(', ')'], '', $request->whatsapp))->first();
        if($user) {
            return redirect()->back()->with('error', 'Já existe uma Pessoa com esse Whatsapp!');
        }

        $user = User::where('email', $request->email)->first();
        if($user) {
            return redirect()->back()->with('error', 'Já existe uma Pessoa com esse Email!');
        }

        if (!preg_match('/^\d{2}-\d{2}-\d{4}$/', $request->dataNasc)) {
            return redirect()->back()->with('error', 'Data de Nascimento enviada incorreta!');
        }

        $user               = new User();
        $user->id_lider     = $request->id_lider;
        $user->nome         = $request->nome;
        $user->dataNasc     = Carbon::parse($request->dataNasc);
        $user->sexo         = $request->sexo;
        $user->profissao    = $request->profissao;
        $user->tipo         = 3;
        $user->email        = strtolower($request->email);
        $user->whatsapp     = str_replace(['.', ' ', ',', '-', '(', ')'], '', $request->whatsapp);
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
            return redirect()->back()->with('success', 'Cadastro concluído com Sucesso!');
        }
        
        return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
    }

    public function viewUser($id) {

        $user = User::where('id', $id)->first();
        $alphas = User::whereIn('tipo', [1, 2, 4])->get();
        if($user) {
            return view('App.User.updateUser', ['user' => $user, 'alphas' => $alphas]);
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
