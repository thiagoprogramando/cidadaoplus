<?php

namespace App\Http\Controllers\Acess;

use App\Http\Controllers\Controller;
use App\Mail\Forgout;
use App\Models\Code;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AcessController extends Controller {
    
    public function logon(Request $request) {

        if(empty($request->email) || empty($request->password)) {
            return redirect()->back()->with('error', 'Informe Email e Senha!');
        }

        $credentials = $request->only(['email', 'password']);
        $credentials['password'] = $credentials['password'];
        if (Auth::attempt($credentials)) {
            return redirect()->route('app');
        } else {
            return redirect()->back()->with('error', 'Credenciais inválidas!');
        }
    }

    public function logout() {
        
        Auth::logout();
        return redirect()->route('login');
    }

    public function forgout($code = null) {

        return view('forgout-password', ['code' => $code]);
    }

    public function forgoutPassword(Request $request) {

        $user = User::where('email', $request->email)->first();
        if(empty($user->email)) {
            return redirect()->back()->with('error', 'Cadastro sem E-mail, verifique com o suporte!');
        }

        $code = new Code();
        $code->id_user = $user->id;
        if($code->save()) {

            Mail::to($user->email, $user->nome)->send(new Forgout([
                'fromName'      => 'Cidadão Plus -'.$user->company()->name,
                'fromEmail'     => $user->company()->name,
                'subject'       => 'Recuperação de Senha',
                'message'       => 'Olá,'.$user->nome.'! Gerei um código de segurança para você redefinir sua senha de acesso, basta clicar no código e escolher uma nova senha!',
                'code'          => $code->code
            ]));

            return redirect()->route('forgout', ['code' => 123])->with('success', 'Foi enviado um código para o seu Email, informe-o com sua nova senha!');
        }

        return redirect()->back()->with('error', 'Não foi possível realizar essa operação!');
    }

    public function recoverPassword(Request $request) {

        if($request->password != $request->passwordRepeat) {
            return redirect()->route('forgout', ['code' => 123])->with('error', 'Senhas não coincidem!');
        }

        $code = Code::where('code', $request->code)->first();
        if($code) {

            $user = User::find($code->id_user);
            if($user) {

                $user->password = bcrypt($request->password);
                $user->save();

                if($user->save()) {
                    return redirect()->route('login')->with('success', 'Agora é só Acessar!');
                }

                return redirect()->back()->with('error', 'Instabilidade momentanea, tente novamente mais tarde!');
            }

            return redirect()->back()->with('error', 'Código não relacionando a um Usuário!');
        }

        return redirect()->back()->with('error', 'Código não encontrado!');
    }

    public function registerUserExternal($id) {

        return view('Form.registerUser', ['id' => $id]);
    }

}
