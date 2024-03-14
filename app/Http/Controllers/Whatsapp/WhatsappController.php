<?php

namespace App\Http\Controllers\Whatsapp;

use App\Http\Controllers\Controller;
use App\Models\Mensagem;
use App\Models\Whatsappp;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PhoneNumbersImport;
use App\Models\MensagemLog;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class WhatsappController extends Controller {
    
    public function listWhatsapp() {

        $whatsapps = Whatsappp::all();
        return view('App.Whatsapp.listWhatsapp', ['whatsapps' => $whatsapps]);
    }

    public function registrerWhatsapp(Request $request) {

        $whatsapp = new Whatsappp();
        $whatsapp->name                = $request->name;
        $whatsapp->webhookUrl          = $request->webhookUrl;
        $whatsapp->phone_number_id     = $request->phone_number_id;
        $whatsapp->user_access_token   = $request->user_access_token;

        if($whatsapp->save()) {
            return redirect()->back()->with('success', 'WhatsApp criado com sucesso!');
        }

        return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
    }

    public function deleteWhatsapp(Request $request) {

        $password = $request->password;    
        if (Hash::check($password, auth()->user()->password)) {
           
            $whatsapp = Whatsappp::find($request->id);
            if ($whatsapp) {

                if($this->deleteInstance($whatsapp->tokenKey)){
                    $whatsapp->delete();
                    return redirect()->back()->with('success', 'Whatsapp excluído com sucesso!');
                }
                
                return redirect()->back()->with('error', 'Não encontramos dados do Whatsapp, tente novamente mais tarde!');
            } else {
                return redirect()->back()->with('error', 'Não encontramos dados do Whatsapp, tente novamente mais tarde!');
            }
        } else {
            return redirect()->back()->with('error', 'Senha incorreta!');
        }
    }

    public function listMessage() {

        $messages = Mensagem::all();
        $whatsapps = Whatsappp::all();
        return view('App.Whatsapp.listMessage', ['messages' => $messages, 'whatsapps' => $whatsapps]);
    }

    public function registrerMessage(Request $request) {

        $whatsapp = Whatsappp::find($request->whatsapp_id);
        if(!$whatsapp) {
            return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
        }

        $code = $whatsapp->id.rand(0, 999999);

        $phoneNumbers = Excel::toCollection(new PhoneNumbersImport, $request->file('numero'))[0]->skip(1);
        
        if ($request->hasFile('base64')) {
            $file      = $request->file('base64');
            $fileName  = rand(0, 5689).rand(0, 99999).$file->getClientOriginalExtension();
            $path      = $file->storeAs('whatsapp', $fileName);
        }

        foreach ($phoneNumbers as $phoneNumber) {
            $number = preg_replace('/\s+/', '', $phoneNumber[0]);
            if ($request->hasFile('base64')) {

                if($number) {
                    // $base64 = base64_encode(file_get_contents($request->file('base64')->path()));
                    $send = $this->sendMidia($number, env('APP_URL').$path, $whatsapp->phone_number_id, $whatsapp->user_access_token);
                    if(!empty($send['error'])) {
                        $status = "error";
                        $this->createLog($number, $send['error'], $code, $status);
                    } else {
                        $status = "success";
                        $message = "Disparo concluído com Sucesso!";
                        $this->createLog($number, env('APP_URL').'storage/'.$path, $code, $status);
                    }
                }
            } else {
                if($number) {
                    $send = $this->sendMessage($number, $request->texto, $whatsapp->phone_number_id, $whatsapp->user_access_token);
                    if(!empty($send['error'])) {
                        $status = "error";
                        $this->createLog($number, $send['error'], $code, $status);
                    } else {
                        $status = "success";
                        $message = "Disparo concluído com Sucesso!";
                        $this->createLog($number, $message, $code, $status);
                    }
                }
            }
        }

        $message = new Mensagem();
        $message->id_whatsapp        = $whatsapp->id;
        $message->code               = $code;
        $message->texto              = $request->texto;
        $message->phone_number_id    = $whatsapp->phone_number_id;
        $message->user_access_token  = $whatsapp->user_access_token;
        $message->status             = 'Operação Finalizada';
        $message->base64             = !empty($base64) ? $base64 : '';
        if($message->save()) {
            return redirect()->back()->with('success', 'Disparos enviados com sucesso, confira o Log!');
        }

        return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
    }

    private function createLog($number, $retorno, $code, $status) {

        $log                = new MensagemLog();
        $log->code          = $code;
        $log->numero        = $number;
        $log->resposta      = $retorno;
        $log->status        = $status;
        if($log->save()) {
            return true;
        }

        return false;
    }

    private function sendMidia($number, $base64, $phone_number_id, $user_access_token) {

        $client = new Client();

        $options = [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer '.$user_access_token,
            ],
            'json' => [
                'messaging_product' => "whatsapp",
                'recipient_type'    => "individual",
                'to'                => '55'.$number,
                'type'              => "image",
                'image'             => [
                    'link' => $base64
                ],
            ],
            'verify' => false
        ];

        try {
            $response = $client->post('https://graph.facebook.com/v18.0/'.$phone_number_id.'/messages', $options);
    
            if ($response->getStatusCode() === 200) {
                return true;
            } else {
                return ['error' => 'API não enviou status'];
            }
        } catch (RequestException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function sendMessage($number, $text, $phone_number_id, $user_access_token) {

        $client = new Client();

        $options = [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer '.$user_access_token,
            ],
            'json' => [
                'messaging_product' => "whatsapp",
                'recipient_type'    => "individual",
                'to'                => '55'.$number,
                'type'              => "text",
                'text'             => [
                    'preview_url' => false,
                    'body'        => $text
                ],
            ],
            'verify' => false
        ];

        try {
            $response = $client->post('https://graph.facebook.com/v18.0/'.$phone_number_id.'/messages', $options);
    
            if ($response->getStatusCode() === 200) {
                return true;
            } else {
                return ['error' => 'API não enviou status'];
            }
        } catch (RequestException $e) {
            return ['error' => $e->getMessage()];
        }
    }

}
