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

    private function searchPostalCode($bairro) {

        switch($bairro) {
            case 'Igapó':
                $ceps = [];
                break;
            case 'Lagoa Azul':
                $ceps = [];
                break;
            case 'Nossa Senhora da Apresentação':
                $ceps = [];
                break;
            case 'Pajuçara':
                $ceps = ['59133300', '59131515', '59123610', '59122365', '59122518', '59122770', '59131430', '59133390', '59123405', '59123030', '59133030', '59133380', '59132280', '59132480', '59133020', '59133010', '59132440', '59133090', '59132000', '59122537', '59131000', '59132045', '59122385', '59133145', '59133250', '59133240', '59133230', '59133150', '59133270', '59133340', '59133280', '59133290', '59133350', '59133260', '59133220', '59133370', '59133190', '59133310', '59133160', '59133210', '59133200', '59133180', '59133140', '59133320', '59133360', '59133170', '59133330', '59133135', '59131400', '59133065'];
                break;
            case 'Potengi':
                $ceps = [];
                break;
            case 'Redinha':
                $ceps = [];
                break;
            case 'Salinas':
                $ceps = [];
                break;

            case 'Alecrim':
                // Código para o bairro Alecrim
                break;
            case 'Areia Preta':
                // Código para o bairro Areia Preta
                break;
            case 'Barro Vermelho':
                // Código para o bairro Barro Vermelho
                break;
            case 'Cidade Alta':
                // Código para o bairro Cidade Alta
                break;
            case 'Lagoa Seca':
                // Código para o bairro Lagoa Seca
                break;
            case 'Mãe Luiza':
                // Código para o bairro Mãe Luiza
                break;
            case 'Petrópolis':
                // Código para o bairro Petrópolis
                break;
            case 'Praia do Meio':
                // Código para o bairro Praia do Meio
                break;
            case 'Ribeira':
                // Código para o bairro Ribeira
                break;
            case 'Rocas':
                // Código para o bairro Rocas
                break;
            case 'Santos Reis':
                // Código para o bairro Santos Reis
                break;
            case 'Tirol':
                // Código para o bairro Tirol
                break;
            
            case 'Candelária':
                // Código para o bairro Candelária
                break;
            case 'Capim Macio':
                // Código para o bairro Capim Macio
                break;
            case 'Lagoa Nova':
                // Código para o bairro Lagoa Nova
                break;
            case 'Neópolis':
                // Código para o bairro Neópolis
                break;
            case 'Nova Descoberta':
                // Código para o bairro Nova Descoberta
                break;
            case 'Pitimbu':
                // Código para o bairro Pitimbu
                break;
            case 'Ponta Negra':
                // Código para o bairro Ponta Negra
                break;
        }

    }

    public function listUser($tipo = null) {

        if($tipo) {
            $users = Auth::user()->tipo === 1 ? 
                User::where('tipo', $tipo)->orderBy('created_at', 'desc')->paginate(100) : 
                User::where('tipo', $tipo)->where('id_lider', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(100);
            $usersCount = Auth::user()->tipo === 1 ? 
                User::where('tipo', $tipo)->orderBy('created_at', 'desc')->count() : 
                User::where('tipo', $tipo)->where('id_lider', Auth::user()->id)->orderBy('created_at', 'desc')->count();
        } else {
            $users = Auth::user()->tipo === 1 ? 
                User::orderBy('created_at', 'desc')->paginate(100) : 
                User::where('id_lider', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(100);
            $usersCount = Auth::user()->tipo === 1 ? 
            User::orderBy('created_at', 'desc')->count() : 
            User::where('id_lider', Auth::user()->id)->orderBy('created_at', 'desc')->count();
        }

        $alphas = Auth::user()->tipo == 1 ? 
            User::whereIn('tipo', [1, 2, 4])->orderBy('created_at', 'desc')->get() : 
            User::whereIn('tipo', [1, 2])->where('id_lider', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        return view('App.User.listUsers', [
            'users'         => $users, 
            'tipo'          => $tipo, 
            'alphas'        => $alphas,
            'usersCount'    => $usersCount
        ]);
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

        $alphas = Auth::user()->tipo == 1 ? 
            User::whereIn('tipo', [1, 2, 4])->orderBy('created_at', 'desc')->get() : 
            User::whereIn('tipo', [2, 4])->where('id_lider', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        
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

        $usersCount = $query->orderBy('created_at', 'desc')->count();
        $users = $query->orderBy('created_at', 'desc')->paginate(100);
        
        $alphas = Auth::user()->tipo == 1 ? 
            User::whereIn('tipo', [1, 2, 4])->orderBy('created_at', 'desc')->get() : 
            User::whereIn('tipo', [1, 2])->where('id_lider', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        return view('App.User.listUsers', [
            'users'             => $users,
            'usersCount'        => $usersCount, 
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
    
    public function viewUser($id) {

        $user = User::where('id', $id)->first();
        $alphas = Auth::user()->tipo == 1 ? 
            User::whereIn('tipo', [1, 2, 4])->orderBy('created_at', 'desc')->get() : 
            User::whereIn('tipo', [1, 2])->where('id_lider', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        if($user) {
            return view('App.User.updateUser', ['user' => $user, 'alphas' => $alphas]);
        }
        
        return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
    }

    public function view($id) {

        $user = User::where('id', $id)->first();
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
                'todos'         => $todos,
                'rede'          => $user->totalUsers()
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
    
        $user = User::find($request->id);
        if ($user) {

            $user->delete();
            return redirect()->back()->with('success', 'Usuário excluído com sucesso!');
        } else {
            return redirect()->back()->with('error', 'Não encontramos dados do Usuário, tente novamente mais tarde!');
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
