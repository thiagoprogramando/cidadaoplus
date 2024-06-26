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

class UserController extends Controller {
    
    public function profile() {
        
        return view('App.User.profile');
    }

    public function profileUpdate(Request $request) {
        
        $user = User::find($request->id);
        if($user) {

            if($request->name) {
                $data['name'] = $request->name;
            }
            if($request->cpfcnpj) {
                $data['cpfcnpj'] = str_replace(['.', ' ', ',', '-', '(', ')'], '', $request->cpfcnpj);
            }
            if($request->phone) {
                $data['phone'] = str_replace(['.', ' ', ',', '-', '(', ')'], '', $request->phone);
            }
            if($request->email) {
                $data['email'] = strtolower($request->email);
            }
            if($request->password) {
                $data['password'] = bcrypt($request->password);
            }

            if($user->update($data)) {
                return redirect()->back()->with('success', 'Perfil atualizado com sucesso!');
            }            

            return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
        }

        return redirect()->back()->with('error', 'Não localizamos os dados do usuário, tente novamente mais tarde!');
    }

    public function listUser($type = null) {

        $users = Auth::user()->type === 1 
            ? User::where('type', $type)->orderBy('created_at', 'desc')->paginate(100) 
            : User::where('type', $type)->where('id_creator', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(100);

        $usersCount = Auth::user()->type === 1 
            ? User::where('type', $type)->where('id_creator', '!=', 729)->orderBy('created_at', 'desc')->count() 
            : User::where('type', $type)->where('id_creator', '!=', 729)->where('id_creator', Auth::user()->id)->orderBy('created_at', 'desc')->count();
        
        $alphas = Auth::user()->type == 1 
            ? User::whereIn('type', [1, 2, 4])->orderBy('created_at', 'desc')->get() 
            : User::whereIn('type', [1, 2])->where('id_creator', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        return view('App.User.listUsers', [
            'users'         => $users, 
            'type'          => $type, 
            'alphas'        => $alphas,
            'usersCount'    => $usersCount
        ]);
    }

    public function filterUser(Request $request) {

        $query = User::query();

        if ($request->input('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->input('created')) {
            $created = date('Y-m-d H:i:s', strtotime($request->input('created')));
            $query->whereDate('created_at', '=', $created);
        }        

        if ($request->input('birth')) {
            $birth = $request->input('birth');
            $birthParts = explode('-', $birth);

            if (count($birthParts) === 2) {
                $dia = $birthParts[0];
                $mes = $birthParts[1];

                $query->whereRaw("DAY(birth) = $dia")
                    ->whereRaw("MONTH(birth) = $mes");
            } elseif (count($birthParts) === 3) {
                $dia = $birthParts[0];
                $mes = $birthParts[1];
                $ano = $birthParts[2];

                $query->whereRaw("DAY(birth) = $dia")
                    ->whereRaw("MONTH(birth) = $mes")
                    ->whereRaw("YEAR(birth) = $ano");
            } else {
                $dia = $birthParts[0];
                $query->whereRaw("DAY(birth) = $dia");
            }
        }

        if(Auth::user()->type == 1 || Auth::user()->type == 4) {
            if ($request->input('id_creator')) {
                $query->where('id_creator', $request->input('id_creator'));
            }
        } else {
            $query->where('id_creator', Auth::user()->id);
        }

        if($request->input('type')) {
            $query->where('type', $request->input('type'));
        }

        if($request->input('sex')) {
            $query->where('sex', $request->input('sex'));
        }

        if($request->input('profession')) {
            $query->where('profession', $request->input('profession'));
        }
        
        $usersCount = $query->orderBy('created_at', 'desc')->count();
        $users = $query->orderBy('created_at', 'desc')->paginate(100);
        
        $alphas = Auth::user()->type == 1 ? 
            User::whereIn('type', [1, 2, 4])->orderBy('created_at', 'desc')->get() : 
            User::whereIn('type', [1, 2])->where('id_creator', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        return view('App.User.listUsers', [
            'users'             => $users,
            'usersCount'        => $usersCount, 
            'type'              => 1, 
            'alphas'            => $alphas,
        ]);
    }

    public function listReport(Request $request) {

        $cidadao      = User::where('type', 3);
        $apoiador     = User::where('type', 2);
        $coordenador  = User::where('type', 4);
        $administrador         = User::where('type', 1);
        
        if($request->input('id_creator')) {
            $id_creator = $request->input('id_creator');

            $cidadao->where('id_creator', $id_creator);
            $apoiador->where('id_creator', $id_creator);
            $coordenador->where('id_creator', $id_creator);
            $administrador->where('id_creator', $id_creator);
        } else {
            if(Auth::user()->type != 1) {
                $cidadao->where('id_creator', Auth::user()->id);
                $apoiador->where('id_creator', Auth::user()->id);
                $coordenador->where('id_creator', Auth::user()->id);
                $administrador->where('id_creator', Auth::user()->id);
            }
        }
        
        $cidadao        = $cidadao->get();
        $apoiador       = $apoiador->get();
        $coordenador    = $coordenador->get();
        $administrador  = $administrador->get();
        
        return view('App.User.listReport', [
            'cidadao'       => $cidadao,
            'apoiador'      => $apoiador,
            'coordenador'   => $coordenador,
            'administrador' => $administrador,
        ]);
    }

    public function createUserExternal(Request $request) {

        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'whatsapp'  => 'required|unique:users,whatsapp',
            'birth'  => 'required|date_format:d-m-Y',
            'email'     => 'nullable|email|unique:users,email',
        ], [
            'name.required'         => 'Por favor, informe um name!',
            'whatsapp.required'     => 'Por favor, informe um WhatsApp!',
            'whatsapp.unique'       => 'Já existe uma Pessoa com esse WhatsApp!',
            'birth.required'     => 'Por favor, informe uma Data de Nascimento!',
            'birth.date_format'  => 'Formato de data de nascimento inválido. Use o formato DD-MM-AAAA!',
            'email.email'           => 'Formato de e-mail inválido!',
            'email.unique'          => 'Já existe uma Pessoa com esse E-mail!'
        ]);        

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } else {
            $user               = new User();
            $user->id_creator     = $request->id_creator;
            $user->name         = $request->name;
            $user->birth     = Carbon::parse($request->birth);
            $user->sexo         = $request->sexo;
            $user->profession    = $request->profession;
            $user->type         = 3;
            $user->email        = strtolower($request->email);
            $user->whatsapp     = $request->whatsapp;
            $user->instagram    = $request->instagram;
            $user->facebook     = $request->facebook;
            $user->postal_code          = $request->postal_code;
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
        $alphas = Auth::user()->type == 1 ? 
            User::whereIn('type', [1, 2, 4])->orderBy('created_at', 'desc')->get() : 
            User::whereIn('type', [1, 2])->where('id_creator', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        if($user) {
            return view('App.User.updateUser', ['user' => $user, 'alphas' => $alphas]);
        }
        
        return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
    }

    public function updateUser(Request $request) {

        $user = User::where('id', $request->id)->first();
        if($user) {
            $data = [];

            if ($request->has('id_creator')) {
                $data['id_creator'] = $request->id_creator;
            }
            if ($request->has('name')) {
                $data['name'] = $request->name;
            }
            if ($request->has('profession')) {
                $data['profession'] = $request->profession;
            }
            if ($request->has('birth')) {
                $data['birth'] = Carbon::parse($request->birth);
            }
            if ($request->has('sex')) {
                $data['sex'] = $request->sex;
            }
            if ($request->has('type')) {
                $data['type'] = $request->type;
            }
            if ($request->has('email')) {
                $data['email'] = strtolower($request->email);
            }
            if ($request->has('phone')) {
                $data['phone'] = str_replace(['.', ',', '-', '(', ')'], '', $request->phone);
            }
            if ($request->has('instagram')) {
                $data['instagram'] = $request->instagram;
            }
            if ($request->has('facebook')) {
                $data['facebook'] = $request->facebook;
            }
            if ($request->has('postal_code')) {
                $data['postal_code'] = $request->postal_code;
            }
            if ($request->has('address')) {
                $data['address'] = $request->address;
            }
            if ($request->has('number')) {
                $data['number'] = $request->number;
            }
            if ($request->has('city')) {
                $data['city'] = $request->city;
            }
            if ($request->has('state')) {
                $data['state'] = $request->state;
            }
            if ($request->has('password')) {
                $data['password'] = bcrypt($request->password);
            }

            if($user->update($data)) {
                return redirect()->back()->with('success', 'Dados atualizados com sucesso!');
            }
        }
        
        return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
    }

    public function deleteUser(Request $request) {
    
        $user = User::find($request->id);
        if ($user && $user->delete()) {
            return redirect()->back()->with('success', 'Usuário excluído com sucesso!');
        } else {
            return redirect()->back()->with('error', 'Não encontramos dados do Usuário, tente novamente mais tarde!');
        }
    }

    public function importUser(Request $request) {

        set_time_limit(300);
        
        $file = $request->file('file');
        if($file) {
            try {
                Excel::import(new UsersImport, $file);
                return redirect()->back()->with('success', 'Dados importados com sucesso!');
            } catch (\Throwable $e) {
                return redirect()->back()->with('error', 'Ocorreu um erro durante a importação: ' . $e->getMessage());
            }
        }
        
        return redirect()->back()->with('error', 'Importação incompleta, nenhum arquivo enviado!');
    }

    private function searchPostalCode($bairro) {

        switch($bairro) {
            case 'Igapó':
                return $postal_codes = [
                    '59101045',
                    '59104180',
                    '59104000',
                    '59104212',
                    '59106120',
                    '59104228',
                    '59104317',
                    '59104345',
                    '59104095',
                    '59104200',
                    '59104160',
                    '59104170',
                    '59104255',
                    '59104285',
                    '59101010',
                    '59104010',
                    '59106135',
                    '59104210',
                    '59104080',
                    '59104272',
                    '59101060',
                    '59104430',
                    '59104230',
                    '59101050',
                    '59104050',
                    '59104420',
                    '59106108',
                    '59101070',
                    '59074730',
                    '59104220',
                    '59104360',
                    '59106105',
                    '59104130',
                    '59101064',
                    '59104056',
                    '59104060',
                    '59104280',
                    '59104150',
                    '59104300',
                    '59106005',
                    '59104315',
                    '59106029',
                    '59104120',
                    '59104330',
                    '59106010',
                    '59106310',
                    '59106170',
                    '59104370',
                    '59106040',
                    '59101040'
                ];
                break;
            case 'Lagoa Azul':
                return $postal_codes = [
                    '59139410',
                    '59139290',
                    '59135000',
                    '59129460',
                    '59129470',
                    '59138000',
                    '59138600',
                    '59135200',
                    '59139000',
                    '59135300',
                    '59135500',
                    '59139524',
                    '59138618',
                    '59139420',
                    '59139340',
                    '59136245',
                    '59139440',
                    '59139400',
                    '59136000',
                    '59135700',
                    '59139450',
                    '59139430',
                    '59134010',
                    '59139580',
                    '59139518',
                    '59129690',
                    '59129725',
                    '59134000',
                    '59135740',
                    '59135410',
                    '59136050',
                    '59135570',
                    '59135350',
                    '59139850',
                    '59138660',
                    '59135400',
                    '59138602',
                    '59129304',
                    '59139534',
                    '59139625',
                    '59136040',
                    '59139755',
                    '59139370',
                    '59139545',
                    '59139520',
                    '59139535',
                    '59139528',
                    '59129482',
                    '59139790',
                    '59139810'
                ];
                break;
            case 'Nossa Senhora da Apresentação':
                return $postal_codes = [
                    '59114645',
                    '59114250',
                    '59114309',
                    '59115725',
                    '59114214',
                    '59114275',
                    '59115470',
                    '59114035',
                    '59114500',
                    '59115700',
                    '59114400',
                    '59114268',
                    '59114000',
                    '59115730',
                    '59114312',
                    '59114337',
                    '59114735',
                    '59115000',
                    '59114099',
                    '59114200',
                    '59114185',
                    '59115650',
                    '59114675',
                    '59115001',
                    '59115900',
                    '59115290',
                    '59115260',
                    '59114220',
                    '59114388',
                    '59115042',
                    '59114137',
                    '59114362',
                    '59115621',
                    '59115616',
                    '59115622',
                    '59115219',
                    '59115218',
                    '59115420',
                    '59114236',
                    '59115200',
                    '59115210',
                    '59115230',
                    '59114179',
                    '59115430',
                    '59114007',
                    '59114005',
                    '59114030',
                    '59114181',
                    '59114259',
                    '59114121'
                ];
                break;
            case 'Pajuçara':
                return $postal_codes = ['59133300', '59131515', '59123610', '59122365', '59122518', '59122770', '59131430', '59133390', '59123405', '59123030', '59133030', '59133380', '59132280', '59132480', '59133020', '59133010', '59132440', '59133090', '59132000', '59122537', '59131000', '59132045', '59122385', '59133145', '59133250', '59133240', '59133230', '59133150', '59133270', '59133340', '59133280', '59133290', '59133350', '59133260', '59133220', '59133370', '59133190', '59133310', '59133160', '59133210', '59133200', '59133180', '59133140', '59133320', '59133360', '59133170', '59133330', '59133135', '59131400', '59133065'];
                break;
            case 'Potengi':
                return $postal_codes = [
                    '59124000',
                    '59120260',
                    '59108000',
                    '59112410',
                    '59120370',
                    '59112200',
                    '59112495',
                    '59129200',
                    '59124200',
                    '59108355',
                    '59120330',
                    '59110720',
                    '59120555',
                    '59110200',
                    '59120200',
                    '59108550',
                    '59108200',
                    '59110972',
                    '59110959',
                    '59108500',
                    '59108240',
                    '59110000',
                    '59120645',
                    '59124525',
                    '59125090',
                    '59112560',
                    '59124400',
                    '59124025',
                    '59124215',
                    '59124535',
                    '59124515',
                    '59124105',
                    '59110270',
                    '59120420',
                    '59112000',
                    '59112075',
                    '59108120',
                    '59127000',
                    '59129340',
                    '59120120',
                    '59120220',
                    '59129430',
                    '59120560',
                    '59129450',
                    '59124710',
                    '59129785',
                    '59124790',
                    '59110580',
                    '59124410',
                    '59124660'
                ];                
                break;
            case 'Redinha':
                return $postal_codes = [
                    '59122120',
                    '59122200',
                    '59122468',
                    '59122610',
                    '59122005',
                    '59110970',
                    '59122400',
                    '59122700',
                    '59122170',
                    '59122420',
                    '59122430',
                    '59122640',
                    '59122290',
                    '59122155',
                    '59122590',
                    '59122263',
                    '59122272',
                    '59122205',
                    '59122274',
                    '59122036',
                    '59122044',
                    '59122039',
                    '59122038',
                    '59122406',
                    '59122150',
                    '59122025',
                    '59122090',
                    '59122100',
                    '59122491',
                    '59122020',
                    '59122096',
                    '59122678',
                    '59122560',
                    '59122676',
                    '59122310',
                    '59122425',
                    '59122455',
                    '59122105',
                    '59122152',
                    '59122110',
                    '59122672',
                    '59122230',
                    '59122015',
                    '59122570',
                    '59122027',
                    '59122660',
                    '59122481',
                    '59122670',
                    '59122195',
                    '59122497'
                ];
                break;
            case 'Salinas':
                return $postal_codes = [
                    '59107005',
                    '59107030',
                    '59107025',
                    '59107012',
                    '59107015',
                    '59107035',
                    '59107020',
                    '59107040',
                    '59139040',
                    '59107000',
                    '59107050',
                    '59107010'
                ];
                break;
            case 'Alecrim':
                return $postal_codes = [
                    '59030160',
                    '59030130',
                    '59030145',
                    '59030140',
                    '59030120',
                    '59030150',
                    '59030350',
                    '59031350',
                    '59030000',
                    '59035000',
                    '59031000',
                    '59037000',
                    '59040040',
                    '59032600',
                    '59032445',
                    '59032900',
                    '59040200',
                    '59031200',
                    '59037200',
                    '59030200',
                    '59040900',
                    '59040970',
                    '59040959',
                    '59040081',
                    '59031430',
                    '59037080',
                    '59040170',
                    '59030020',
                    '59040180',
                    '59031440',
                    '59037190',
                    '59040190',
                    '59030400',
                    '59040090',
                    '59040140',
                    '59031450',
                    '59040450',
                    '59037090',
                    '59040220',
                    '59037340',
                    '59032420',
                    '59037370',
                    '59040137',
                    '59040030',
                    '59040130',
                    '59030060',
                    '59040300',
                    '59031330',
                    '59040010',
                    '59032480'
                ];
                break;
            case 'Areia Preta':
                return $postal_codes = [
                    '59014100',
                    '59014104',
                    '59014061',
                    '59014690',
                    '59014062',
                    '59014102',
                    '59014640',
                    '59014050',
                    '59014077',
                    '59014078',
                    '59014075',
                    '59014120',
                    '59014030',
                    '59014106',
                    '59014040',
                    '59014170',
                    '59014090',
                    '59014060',
                    '59014080',
                    '59014130',
                    '59014070',
                    '59014110',
                    '59014055',
                    '59014108',
                    '59014175',
                    '59014155',
                    '59014065',
                    '59014178'
                ];
                break;
            case 'Barro Vermelho':
                return $postal_codes = [
                    '59030660',
                    '59022545',
                    '59022550',
                    '59022100',
                    '59020380',
                    '59030570',
                    '59030210',
                    '59022600',
                    '59030230',
                    '59030340',
                    '59030630',
                    '59030470',
                    '59030410',
                    '59022610',
                    '59030450',
                    '59022050',
                    '59030430',
                    '59030320',
                    '59030530',
                    '59022640',
                    '59022080',
                    '59020560',
                    '59030360',
                    '59022120',
                    '59022530',
                    '59030490',
                    '59022420',
                    '59030560',
                    '59030220',
                    '59030480',
                    '59022405',
                    '59030190',
                    '59030510',
                    '59022060',
                    '59030710',
                    '59022070',
                    '59030215',
                    '59030500',
                    '59020460',
                    '59030640',
                    '59030650',
                    '59030390',
                    '59022500',
                    '59022110',
                    '59030240',
                    '59030620',
                    '59020390',
                    '59030440',
                    '59030250',
                    '59022620'
                ];
                break;
            case 'Cidade Alta':
                return $postal_codes = [
                    '59025280',
                    '59025600',
                    '59025145',
                    '59025225',
                    '59025235',
                    '59025590',
                    '59025460',
                    '59025001',
                    '59025000',
                    '59025002',
                    '59025003',
                    '59025900',
                    '59025970',
                    '59025959',
                    '59025906',
                    '59025800',
                    '59025275',
                    '59025755',
                    '59025640',
                    '59025580',
                    '59025190',
                    '59025550',
                    '59025085',
                    '59025380',
                    '59025480',
                    '59025290',
                    '59025650',
                    '59025660',
                    '59025255',
                    '59025510',
                    '59025300',
                    '59025080',
                    '59025220',
                    '59025570',
                    '59025170',
                    '59025060',
                    '59025763',
                    '59025766',
                    '59025757',
                    '59025070',
                    '59025260',
                    '59025020',
                    '59025010',
                    '59025784',
                    '59071430',
                    '59074640',
                    '59025270',
                    '59025470',
                    '59025765',
                    '59025740'
                ];
                break;
            case 'Lagoa Seca':
                return $postal_codes = [
                    '59022350',
                    '59022385',
                    '59031125',
                    '59032170',
                    '59022305',
                    '59022310',
                    '59022275',
                    '59022340',
                    '59031120',
                    '59022300',
                    '59032070',
                    '59022320',
                    '59022220',
                    '59032080',
                    '59032180',
                    '59022210',
                    '59022470',
                    '59022230',
                    '59031070',
                    '59031140',
                    '59031110',
                    '59074750',
                    '59022330',
                    '59031010',
                    '59032240',
                    '59022360',
                    '59032620',
                    '59032190',
                    '59031820',
                    '59022370',
                    '59022380',
                    '59032230',
                    '59022290',
                    '59022150',
                    '59031060',
                    '59032220',
                    '59022390',
                    '59031630',
                    '59022280',
                    '59022215',
                    '59031030',
                    '59031020',
                    '59031040',
                    '59031080',
                    '59139413',
                    '59032075',
                    '59022285',
                    '59031050',
                    '59020015',
                    '59031130'
                ];
                break;
            case 'Mãe Luiza':
                return $postal_codes = [
                    '59014425',
                    '59014380',
                    '59014360',
                    '59014440',
                    '59014320',
                    '59014210',
                    '59014350',
                    '59014190',
                    '59014290',
                    '59014015',
                    '59014420',
                    '59014272',
                    '59014220',
                    '59014384',
                    '59014340',
                    '59074810',
                    '59014203',
                    '59014317',
                    '59014386',
                    '59014229',
                    '59014224',
                    '59014222',
                    '59014221',
                    '59014228',
                    '59014388',
                    '59014680',
                    '59014323',
                    '59014280',
                    '59014180',
                    '59014370',
                    '59014230',
                    '59014260',
                    '59014000',
                    '59014318',
                    '59014670',
                    '59014322',
                    '59014270',
                    '59014410',
                    '59014650',
                    '59014310',
                    '59014390',
                    '59014250',
                    '59014660',
                    '59014300',
                    '59014430',
                    '59014400',
                    '59014330',
                    '59014240',
                    '59014200',
                    '59014223'
                ];
                break;
            case 'Petrópolis':
                return $postal_codes = [
                    '59020065',
                    '59020055',
                    '59012220',
                    '59012240',
                    '59020025',
                    '59014165',
                    '59020000',
                    '59020035',
                    '59012300',
                    '59012360',
                    '59020400',
                    '59020395',
                    '59020058',
                    '59012335',
                    '59020405',
                    '59014140',
                    '59012230',
                    '59020040',
                    '59012310',
                    '59020060',
                    '59012330',
                    '59074770',
                    '59014470',
                    '59014020',
                    '59014450',
                    '59012270',
                    '59012350',
                    '59014460',
                    '59020020',
                    '59012570',
                    '59012550',
                    '59012340',
                    '59012275',
                    '59012320',
                    '59020250',
                    '59014475',
                    '59020080',
                    '59020085',
                    '59020050',
                    '59135760',
                    '59020030',
                    '59012280',
                    '59014010',
                    '59020010',
                    '59012290',
                    '59020150',
                    '59014150',
                    '59014160',
                    '59012260',
                    '59014720'
                ];
                break;
            case 'Praia do Meio':
                return $postal_codes = [
                    '59010056',
                    '59010000',
                    '59010030',
                    '59010058',
                    '59010097',
                    '59010045',
                    '59010008',
                    '59010115',
                    '59010110',
                    '59010120',
                    '59010010',
                    '59074710',
                    '59010098',
                    '59010123',
                    '59010090',
                    '59010003',
                    '59010020',
                    '59010070',
                    '59010054',
                    '59010096',
                    '59010025',
                    '59010015',
                    '59010100',
                    '59010080',
                    '59010040',
                    '59010160',
                    '59010050',
                    '59010038',
                    '59010035',
                    '59010125',
                    '59010060',
                    '59010094',
                    '59010095',
                    '59010105',
                    '59010085',
                    '59010005',
                    '59010140',
                    '59010150',
                    '59010036',
                    '59010093',
                    '59010092',
                    '59010082',
                    '59010185',
                    '59010175',
                    '59010086'
                ];
                break;
            case 'Ribeira':
                return $postal_codes = [
                    '59010900',
                    '59012600',
                    '59012200',
                    '59010700',
                    '59010970',
                    '59010959',
                    '59020972',
                    '59010750',
                    '59012000',
                    '59012050',
                    '59012090',
                    '59012530',
                    '59012197',
                    '59012380',
                    '59012080',
                    '59012160',
                    '59012010',
                    '59012250',
                    '59012130',
                    '59012120',
                    '59012450',
                    '59010990',
                    '59074760',
                    '59012390',
                    '59012085',
                    '59012410',
                    '59012490',
                    '59012370',
                    '59012440',
                    '59012480',
                    '59012030',
                    '59012192',
                    '59012180',
                    '59012125',
                    '59012430',
                    '59012520',
                    '59012040',
                    '59012650',
                    '59012400',
                    '59012020',
                    '59136300',
                    '59073385',
                    '59135550',
                    '59012420',
                    '59012242',
                    '59012188',
                    '59012110',
                    '59012255',
                    '59012070',
                    '59012560'
                ];                
                break;
            case 'Rocas':
                return $postal_codes = [
                    '59010775',
                    '59020901',
                    '59012500',
                    '59010726',
                    '59010210',
                    '59010132',
                    '59010840',
                    '59010710',
                    '59010730',
                    '59012660',
                    '59010774',
                    '59129000',
                    '59010490',
                    '59010280',
                    '59010300',
                    '59010410',
                    '59010420',
                    '59012149',
                    '59012148',
                    '59010728',
                    '59074680',
                    '59010370',
                    '59010260',
                    '59010190',
                    '59010130',
                    '59010134',
                    '59010650',
                    '59010330',
                    '59010740',
                    '59012145',
                    '59010220',
                    '59010250',
                    '59012140',
                    '59010600',
                    '59010350',
                    '59012100',
                    '59012142',
                    '59010680',
                    '59012150',
                    '59010380',
                    '59012146',
                    '59010340',
                    '59012143',
                    '59010310',
                    '59010470',
                    '59010720',
                    '59010290',
                    '59012060',
                    '59010810',
                    '59010620'
                ];
                break;
            case 'Santos Reis':
                return $postal_codes = [
                    '59010530',
                    '59010640',
                    '59010396',
                    '59010552',
                    '59010500',
                    '59010390',
                    '59010520',
                    '59010460',
                    '59074690',
                    '59010400',
                    '59010570',
                    '59010550',
                    '59010560',
                    '59010440',
                    '59010510',
                    '59010580',
                    '59010508',
                    '59010540',
                    '59010430',
                    '59010240',
                    '59010392',
                    '59010450',
                    '59010336',
                    '59010505',
                    '59010523',
                    '59010572',
                    '59010512',
                    '59010394',
                    '59010455',
                    '59054005',
                    '59010525'
                ];
                break;
            case 'Tirol':
                return $postal_codes = [
                    '59014550',
                    '59020100',
                    '59020265',
                    '59015350',
                    '59022430',
                    '59020300',
                    '59020600',
                    '59022020',
                    '59014555',
                    '59020315',
                    '59020650',
                    '59015145',
                    '59014495',
                    '59020095',
                    '59020145',
                    '59020971',
                    '59020970',
                    '59020972',
                    '59020959',
                    '59020903',
                    '59020902',
                    '59020901',
                    '59020500',
                    '59015450',
                    '59015900',
                    '59020510',
                    '59020505',
                    '59020900',
                    '59020904',
                    '59020200',
                    '59020255',
                    '59020640',
                    '59022205',
                    '59015290',
                    '59015000',
                    '59022000',
                    '59022900',
                    '59015430',
                    '59020125',
                    '59020760',
                    '59015311',
                    '59015510',
                    '59020332',
                    '59015520',
                    '59022650',
                    '59014520',
                    '59020501',
                    '59020110',
                    '59020330',
                    '59022185'
                ];
                break;
            case 'Candelária':
                return $postal_codes = [
                    '59064746',
                    '59064747',
                    '59064760',
                    '59064740',
                    '59064902',
                    '59064745',
                    '59064749',
                    '59064748',
                    '59066842',
                    '59066180',
                    '59066900',
                    '59064720',
                    '59065600',
                    '59065780',
                    '59066035',
                    '59066840',
                    '59065500',
                    '59065305',
                    '59064000',
                    '59066800',
                    '59064900',
                    '59064905',
                    '59064620',
                    '59065700',
                    '59065020',
                    '59066030',
                    '59065480',
                    '59065280',
                    '59066080',
                    '59066480',
                    '59065120',
                    '59066100',
                    '59064340',
                    '59064330',
                    '59065320',
                    '59064590',
                    '59065730',
                    '59064530',
                    '59064570',
                    '59065370',
                    '59066455',
                    '59065550',
                    '59065540',
                    '59064660',
                    '59065110',
                    '59065720',
                    '59064640',
                    '59064255',
                    '59064743',
                    '59065180'
                ];
                break;
            case 'Capim Macio':
                return $postal_codes = [
                    '59080100',
                    '59082065',
                    '59082085',
                    '59080971',
                    '59078220',
                    '59078040',
                    '59080105',
                    '59078500',
                    '59080075',
                    '59078315',
                    '59082055',
                    '59082185',
                    '59082095',
                    '59082175',
                    '59082405',
                    '59082025',
                    '59082400',
                    '59078600',
                    '59082105',
                    '59082971',
                    '59082959',
                    '59082902',
                    '59080900',
                    '59078902',
                    '59078972',
                    '59078959',
                    '59078300',
                    '59078380',
                    '59078320',
                    '59078270',
                    '59078400',
                    '59078190',
                    '59082285',
                    '59094900',
                    '59082275',
                    '59078340',
                    '59078330',
                    '59078200',
                    '59078000',
                    '59080000',
                    '59078901',
                    '59082500',
                    '59082145',
                    '59078630',
                    '59078055',
                    '59082480',
                    '59082160',
                    '59082300',
                    '59082030',
                    '59082200'
                ];
                break;
            case 'Lagoa Nova':
                return $postal_codes = [
                    '59075970',
                    '59075971',
                    '59075959',
                    '59063280',
                    '59074740',
                    '59074990',
                    '59063010',
                    '59135260',
                    '59056320',
                    '59054465',
                    '59062350',
                    '59054830',
                    '59063350',
                    '59056215',
                    '59075810',
                    '59056265',
                    '59075015',
                    '59054380',
                    '59054605',
                    '59054725',
                    '59054735',
                    '59056005',
                    '59056015',
                    '59056285',
                    '59056275',
                    '59056901',
                    '59063410',
                    '59064164',
                    '59064355',
                    '59063400',
                    '59076505',
                    '59076400',
                    '59063901',
                    '59063900',
                    '59075200',
                    '59075979',
                    '59077030',
                    '59063100',
                    '59075710',
                    '59075740',
                    '59056450',
                    '59054590',
                    '59056045',
                    '59054700',
                    '59056200',
                    '59063200',
                    '59075700',
                    '59064625',
                    '59064630',
                    '59064903'
                ];
                break;
            case 'Neópolis':
                return $postal_codes = [
                    '59080560',
                    '59080280',
                    '59080360',
                    '59080150',
                    '59080445',
                    '59080170',
                    '59084010',
                    '59086375',
                    '59086105',
                    '59084205',
                    '59088100',
                    '59088245',
                    '59084295',
                    '59088740',
                    '59080245',
                    '59086200',
                    '59084200',
                    '59080115',
                    '59080250',
                    '59088690',
                    '59088510',
                    '59086640',
                    '59088640',
                    '59080190',
                    '59086500',
                    '59088500',
                    '59086000',
                    '59084190',
                    '59086610',
                    '59086630',
                    '59086190',
                    '59084060',
                    '59084145',
                    '59080110',
                    '59086570',
                    '59080490',
                    '59080520',
                    '59080500',
                    '59080510',
                    '59086110',
                    '59084030',
                    '59084020',
                    '59080580',
                    '59084460',
                    '59084040',
                    '59080430',
                    '59080130',
                    '59080120',
                    '59084050',
                    '59084150'
                ];
                break;
            case 'Nova Descoberta':
                return $postal_codes = [
                    '59075970',
                    '59075959',
                    '59074780',
                    '59075335',
                    '59075250',
                    '59056425',
                    '59056500',
                    '59056520',
                    '59075365',
                    '59056374',
                    '59056375',
                    '59056530',
                    '59056250',
                    '59075350',
                    '59075260',
                    '59056485',
                    '59075400',
                    '59075370',
                    '59056400',
                    '59056360',
                    '59075340',
                    '59075360',
                    '59075290',
                    '59075270',
                    '59056480',
                    '59056370',
                    '59056380',
                    '59056440',
                    '59075310',
                    '59075380',
                    '59056350',
                    '59056460',
                    '59075280',
                    '59075240',
                    '59056750',
                    '59075230',
                    '59056590',
                    '59075390',
                    '59075100',
                    '59056610',
                    '59075320',
                    '59056490',
                    '59056420',
                    '59075254',
                    '59056470',
                    '59056430',
                    '59056510',
                    '59075420',
                    '59075901',
                    '59056410'
                ];
                break;
            case 'Pitimbu':
                return $postal_codes = [
                    '59069100',
                    '59066430',
                    '59066400',
                    '59067400',
                    '59067600',
                    '59068605',
                    '59069605',
                    '59068595',
                    '59066360',
                    '59068810',
                    '59068780',
                    '59068550',
                    '59069670',
                    '59069410',
                    '59067450',
                    '59069210',
                    '59068740',
                    '59069190',
                    '59069180',
                    '59068580',
                    '59068750',
                    '59069640',
                    '59069610',
                    '59069740',
                    '59066285',
                    '59069560',
                    '59069420',
                    '59069120',
                    '59068730',
                    '59068830',
                    '59067320',
                    '59068470',
                    '59069070',
                    '59068610',
                    '59069750',
                    '59069660',
                    '59067435',
                    '59069700',
                    '59068720',
                    '59066433',
                    '59067425',
                    '59067720',
                    '59067560',
                    '59066380',
                    '59067405',
                    '59067520',
                    '59068520',
                    '59068430',
                    '59067710',
                    '59067590'
                ];
                break;
            case 'Ponta Negra':
                return $postal_codes = [
                    '59091010',
                    '59091120',
                    '59091900',
                    '59090420',
                    '59092500',
                    '59091210',
                    '59090095',
                    '59090000',
                    '59090075',
                    '59094410',
                    '59090135',
                    '59092440',
                    '59090145',
                    '59090425',
                    '59090165',
                    '59090100',
                    '59090400',
                    '59091200',
                    '59094010',
                    '59092300',
                    '59082275',
                    '59094100',
                    '59092100',
                    '59094500',
                    '59092200',
                    '59091075',
                    '59094515',
                    '59091132',
                    '59090538',
                    '59094102',
                    '59091080',
                    '59090200',
                    '59092570',
                    '59090177',
                    '59091070',
                    '59090310',
                    '59090657',
                    '59090010',
                    '59090655',
                    '59092557',
                    '59090303',
                    '59090590',
                    '59090577',
                    '59090330',
                    '59090270',
                    '59090360',
                    '59090030',
                    '59090280',
                    '59092513',
                    '59091160'
                ];
                break;
            case 'Quintas':
                return $postal_codes = [
                    '59050-005',
                    '59050-000',
                    '59050-480',
                    '59051-000',
                    '59035-015',
                    '59037-295',
                    '59035-040',
                    '59050-093',
                    '59042-050',
                    '59050-030',
                    '59035-515',
                    '59035-170',
                    '59040-410',
                    '59050-060',
                    '59035-060',
                    '59035-010',
                    '59035-140',
                    '59035-240',
                    '59035-460',
                    '59074-830',
                    '59050-100',
                    '59050-250',
                    '59040-476',
                    '59035-350',
                    '59052-350',
                    '59035-150',
                    '59050-200',
                    '59035-100',
                    '59050-010',
                    '59050-185',
                    '59040-480',
                    '59050-182',
                    '59050-065',
                    '59040-474',
                    '59050-020',
                    '59037-130',
                    '59050-140',
                    '59050-120',
                    '59035-120',
                    '59050-170',
                    '59035-110',
                    '59050-110',
                    '59054-340',
                    '59050-040',
                    '59035-380',
                    '59035-070',
                    '59035-080',
                    '59042-030',
                    '59035-660',
                    '59035-020'
                ];
                break;
            case 'Dix-Sept Rosado':
                return $postal_codes = [
                    '59054-145',
                    '59052-475',
                    '59052-465',
                    '59052-200',
                    '59054-600',
                    '59052-300',
                    '59054-180',
                    '59054-000',
                    '59054-970',
                    '59054-959',
                    '59054-260',
                    '59054-140',
                    '59052-150',
                    '59054-240',
                    '59054-020',
                    '59054-370',
                    '59052-075',
                    '59052-240',
                    '59052-260',
                    '59052-210',
                    '59054-207',
                    '59074-670',
                    '59052-700',
                    '59052-065',
                    '59052-800',
                    '59054-280',
                    '59054-010',
                    '59054-120',
                    '59054-440',
                    '59052-305',
                    '59054-060',
                    '59054-210',
                    '59052-192',
                    '59052-480',
                    '59052-140',
                    '59052-090',
                    '59052-215',
                    '59054-190',
                    '59054-204',
                    '59054-110',
                    '59052-270',
                    '59054-170',
                    '59054-540',
                    '59054-200',
                    '59054-070',
                    '59054-080',
                    '59054-160',
                    '59056-760',
                    '59052-060',
                    '59052-830'
                ];
                break;
            case 'Nordeste':
                return $postal_codes = [
                    '59042-610',
                    '59042-200',
                    '59042-095',
                    '59042-505',
                    '59042-600',
                    '59042-340',
                    '59042-110',
                    '59042-250',
                    '59042-270',
                    '59042-370',
                    '59042-420',
                    '59042-480',
                    '59042-430',
                    '59042-440',
                    '59042-435',
                    '59042-510',
                    '59042-310',
                    '59042-640',
                    '59042-330',
                    '59042-350',
                    '59042-380',
                    '59042-590',
                    '59042-540',
                    '59042-530',
                    '59042-520',
                    '59042-450',
                    '59042-490',
                    '59042-343',
                    '59042-150',
                    '59042-240',
                    '59042-070',
                    '59042-290',
                    '59042-230',
                    '59042-320',
                    '59042-460',
                    '59042-470',
                    '59042-500',
                    '59042-360',
                    '59042-120',
                    '59042-970',
                    '59042-959',
                    '59042-090',
                    '59042-390',
                    '59042-400',
                    '59042-140',
                    '59042-280',
                    '59042-130',
                    '59042-100',
                    '59042-260',
                    '59042-300'
                ];
                break;
            case 'Felipe Camarão':
                $postal_code_array = [
                    '59074-160',
                    '59074-166',
                    '59074-167',
                    '59074-161',
                    '59072-000',
                    '59072-300',
                    '59072-100',
                    '59072-480',
                    '59072-420',
                    '59072-220',
                    '59074-185',
                    '59074-034',
                    '59072-119',
                    '59074-154',
                    '59074-014',
                    '59072-280',
                    '59072-772',
                    '59074-330',
                    '59074-556',
                    '59072-225',
                    '59074-310',
                    '59072-070',
                    '59072-314',
                    '59072-360',
                    '59072-020',
                    '59074-410',
                    '59072-010',
                    '59072-435',
                    '59072-190',
                    '59072-080',
                    '59072-290',
                    '59072-285',
                    '59072-122',
                    '59072-110',
                    '59074-090',
                    '59072-126',
                    '59072-200',
                    '59072-075',
                    '59072-130',
                    '59072-134',
                    '59074-430',
                    '59072-128',
                    '59072-103',
                    '59072-305',
                    '59074-555',
                    '59074-110',
                    '59072-740',
                    '59104-060',
                    '59012-480',
                    '59025-200'
                ];
                break;
            case 'Planalto':
                return $postal_code = [
                    '59073-210',
                    '59073-141',
                    '59073-090',
                    '59073-134',
                    '59073-270',
                    '59073-187',
                    '59073-077',
                    '59073-072',
                    '59073-224',
                    '59073-066',
                    '59073-180',
                    '59073-310',
                    '59073-106',
                    '59073-078',
                    '59073-105',
                    '59073-247',
                    '59073-808',
                    '59073-170',
                    '59073-305',
                    '59073-262',
                    '59073-166',
                    '59073-074',
                    '59073-817',
                    '59073-165',
                    '59073-219',
                    '59073-254',
                    '59073-076',
                    '59073-103',
                    '59073-109',
                    '59073-068',
                    '59073-110',
                    '59073-280',
                    '59073-354',
                    '59073-226',
                    '59073-080',
                    '59073-315',
                    '59073-223',
                    '59073-070',
                    '59073-839',
                    '59073-222',
                    '59073-079',
                    '59073-819',
                    '59073-095',
                    '59073-193',
                    '59073-124',
                    '59073-129',
                    '59073-325',
                    '59073-356',
                    '59073-142',
                    '59073-278'
                ];
                break;
            case 'Bom Pastor':
                return $postal_codes = [
                    '59062-250',
                    '59052-000',
                    '59052-080',
                    '59060-680',
                    '59060-235',
                    '59050-080',
                    '59060-480',
                    '59060-215',
                    '59060-195',
                    '59060-330',
                    '59060-144',
                    '59060-172',
                    '59060-745',
                    '59060-650',
                    '59060-738',
                    '59050-240',
                    '59052-070',
                    '59060-220',
                    '59060-770',
                    '59060-190',
                    '59060-715',
                    '59050-390',
                    '59060-230',
                    '59060-785',
                    '59060-145',
                    '59052-075',
                    '59060-320',
                    '59060-240',
                    '59060-096',
                    '59052-020',
                    '59052-050',
                    '59052-030',
                    '59060-710',
                    '59060-689',
                    '59050-380',
                    '59062-030',
                    '59060-760',
                    '59060-270',
                    '59060-010',
                    '59060-755',
                    '59060-030',
                    '59060-170',
                    '59060-783',
                    '59060-790',
                    '59060-060',
                    '59060-177',
                    '59060-140',
                    '59060-734',
                    '59060-130',
                    '59060-180'
                ];
                break;
            case 'Nossa Senhora de Nazaré':
                return $postal_codes = [
                    '59062-195',
                    '59060-400',
                    '59060-500',
                    '59062-200',
                    '59060-200',
                    '59062-600',
                    '59060-300',
                    '59062-300',
                    '59062-000',
                    '59062-330',
                    '59062-390',
                    '59062-780',
                    '59062-050',
                    '59062-260',
                    '59062-640',
                    '59060-540',
                    '59062-130',
                    '59062-137',
                    '59062-700',
                    '59060-700',
                    '59062-280',
                    '59060-510',
                    '59062-070',
                    '59060-390',
                    '59062-590',
                    '59062-150',
                    '59062-320',
                    '59062-170',
                    '59062-240',
                    '59060-440',
                    '59060-360',
                    '59062-060',
                    '59062-340',
                    '59060-490',
                    '59060-430',
                    '59060-380',
                    '59062-540',
                    '59062-220',
                    '59060-560',
                    '59031-370',
                    '59060-470',
                    '59062-140',
                    '59060-530',
                    '59062-160',
                    '59060-280',
                    '59062-290',
                    '59060-420',
                    '59062-310',
                    '59062-040',
                    '59062-180'
                ];
                break;
            case 'Guarapes':
                return $postal_codes = [
                    '59074-852',
                    '59074-850',
                    '59074-605',
                    '59074-847',
                    '59074-752',
                    '59074-864',
                    '59074-846',
                    '59074-578',
                    '59074-857',
                    '59074-886',
                    '59074-872',
                    '59074-584',
                    '59074-884',
                    '59074-747',
                    '59074-562',
                    '59074-725',
                    '59074-768',
                    '59074-825',
                    '59074-880',
                    '59074-620',
                    '59074-640',
                    '59074-650',
                    '59074-660',
                    '59074-740',
                    '59074-990',
                    '59074-750',
                    '59074-710',
                    '59074-700',
                    '59074-760',
                    '59074-830',
                    '59074-680',
                    '59074-810',
                    '59074-800',
                    '59074-780',
                    '59074-770',
                    '59074-610',
                    '59074-690',
                    '59074-623',
                    '59074-630',
                    '59074-670',
                    '59074-730',
                    '59074-840',
                    '59074-866',
                    '59074-855',
                    '59074-624',
                    '59074-572',
                    '59074-878',
                    '59074-843',
                    '59074-564',
                    '59074-844'
                ];
                break;
            case 'Cidade Nova':
                return $postal_codes = [
                    '59072-645',
                    '59072-800',
                    '59072-865',
                    '59072-530',
                    '59072-840',
                    '59072-850',
                    '59072-500',
                    '59072-974',
                    '59072-838',
                    '59072-675',
                    '59072-887',
                    '59072-700',
                    '59072-710',
                    '59072-550',
                    '59072-885',
                    '59072-650',
                    '59072-600',
                    '59072-590',
                    '59072-514',
                    '59072-890',
                    '59072-852',
                    '59072-730',
                    '59072-523',
                    '59072-820',
                    '59072-520',
                    '59072-826',
                    '59072-886',
                    '59072-842',
                    '59072-844',
                    '59072-630',
                    '59072-515',
                    '59072-660',
                    '59072-822',
                    '59072-580',
                    '59072-519',
                    '59072-812',
                    '59072-720',
                    '59072-521',
                    '59072-570',
                    '59072-864',
                    '59072-866',
                    '59072-780',
                    '59072-750',
                    '59072-870',
                    '59072-785',
                    '59072-400',
                    '59072-517',
                    '59072-512'
                ];
                break;
            case 'Cidade da Esperança':
                return $postal_codes = [
                    '59071-355',
                    '59070-400',
                    '59071-110',
                    '59070-660',
                    '59070-900',
                    '59070-600',
                    '59070-200',
                    '59071-445',
                    '59071-550',
                    '59070-300',
                    '59070-450',
                    '59070-500',
                    '59071-300',
                    '59072-974',
                    '59070-100',
                    '59071-410',
                    '59071-440',
                    '59071-020',
                    '59071-170',
                    '59070-260',
                    '59071-260',
                    '59071-380',
                    '59071-280',
                    '59070-290',
                    '59070-160',
                    '59070-270',
                    '59070-110',
                    '59070-180',
                    '59071-520',
                    '59070-715',
                    '59071-560',
                    '59070-150',
                    '59071-070',
                    '59070-360',
                    '59071-330',
                    '59071-360',
                    '59070-230',
                    '59071-290',
                    '59071-010',
                    '59071-430',
                    '59071-600',
                    '59074-650',
                    '59071-470',
                    '59070-220',
                    '59070-730',
                    '59070-610',
                    '59071-240',
                    '59070-390',
                    '59071-050',
                    '59070-490'
                ];
                break;
            default:
                return $postal_codes = [];
                break;
        }

        return $postal_codes = [];
    }
    
}
