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

        $data = $this->createInstance($request->instanceName, $request->webhookUrl);
        if($data && $data['statusCode'] == 201) {

            $whatsapp = new Whatsappp();
            $whatsapp->instanceName = $request->instanceName;
            $whatsapp->webhookUrl   = $request->webhookUrl;
            $whatsapp->tokenKey     = $data['tokenKey'];
            $whatsapp->statusCode   = $data['statusCode'];
            $whatsapp->status       = $data['status'];

            if($whatsapp->save()) {
                return redirect()->back()->with('success', 'WhatsApp criado com sucesso. Agora conecte-o!');
            }
        } else {
            return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
        }

        return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
    }

    private function createInstance($instanceName, $webhookUrl) {
        
        $client = new Client();

        $options = [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer 3517973E-B10FA892-767468D4-D1748981-02212FD7 ',
            ],
            'json' => [
                'instanceName'      => $instanceName,
                'webhookUrl'        => $webhookUrl,
            ],
            'verify' => false
        ];

        $response = $client->post('https://api.apizap.me/v1/manager/create', $options);
        $body = (string) $response->getBody();

        if ($response->getStatusCode() === 201) {
            $data = json_decode($body, true);
            return $dados['json'] = [
                'tokenKey'      => $data['tokenKey'],
                'status'        => $data['status'],
                'statusCode'    => $data['statusCode'],
            ];
        } else {
            return false;
        }
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

    private function deleteInstance($token) {

        $client = new Client();

        $options = [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer 3517973E-B10FA892-767468D4-D1748981-02212FD7 ',
            ],
            'verify' => false
        ];

        $response = $client->delete('https://api.apizap.me/v1/manager/delete?tokenKey='.$token, $options);
        $body = (string) $response->getBody();

        if ($response->getStatusCode() === 200) {
            $data = json_decode($body, true);
            return true;
        } else {
            return false;
        }
    }

    public function listMessage() {

        $messages = Mensagem::all();
        $whatsapps = Whatsappp::all();
        return view('App.Whatsapp.listMessage', ['messages' => $messages, 'whatsapps' => $whatsapps]);
    }

    public function registrerMessage(Request $request) {

        $message = new Mensagem();

        $phoneNumbers = Excel::toCollection(new PhoneNumbersImport, $request->file('numero'))[0]->skip(1);
        foreach ($phoneNumbers as $phoneNumber) {
            
            $number = preg_replace('/\s+/', '', $phoneNumber[0]);
            if ($request->hasFile('base64')) {

                if($number) {
                    $base64 = base64_encode(file_get_contents($request->file('base64')->path()));
                    $send = $this->sendMidia($number, $request->texto, $base64, $request->tokenKey);
                    if(!empty($send['error'])) {
                        $this->createLog($number, $send['error']);
                    }
                }

            } else {

                if($number) {
                    $send = $this->sendMessage($number, $request->texto, $request->tokenKey);
                    if(!empty($send['error'])) {
                        $this->createLog($number, $send['error']);
                    }
                }

            }

        }

        $message->texto     = $request->texto;
        $message->tokenKey  = $request->tokenKey;
        $message->status    = 'Operação concluída';
        $message->numero    = $request->numero->store('numero');
        if ($request->hasFile('base64')) { $message->base64    = $request->base64->store('midia'); }

        if($message->save()) {
            return redirect()->back()->with('success', 'Disparos concluídos com Sucesso, confira o Log!');
        }

        return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
    }

    private function createLog($number, $retorno) {

        $ultimaMensagem = Mensagem::latest()->first();
        $log                = new MensagemLog();
        $log->numero        = $number;
        $log->retorno       = $retorno;
        $log->status        = 'Finalizado';

        if($log->save()) {
            return true;
        }

        return false;
    }

    private function sendMidia($numero, $texto, $base64, $tokenKey) {

        $client = new Client();

        $options = [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer 3517973E-B10FA892-767468D4-D1748981-02212FD7 ',
            ],
            'json' => [
                'tokenKey'     => $tokenKey,
                'envioGrupo'   => false,
                'numero'       => '55'.$numero,
                'caption'      => $texto,
                'base64'       => $base64,
                'originalname' => 'Promocional',
                'mimetype'     => 'image/png'
            ],
            'verify' => false
        ];

        try {
            $response = $client->post('https://api.apizap.me/v1/message/mediaBase64', $options);
    
            if ($response->getStatusCode() === 200) {
                return true;
            } else {
                return ['error' => 'API não enviou status'];
            }
        } catch (RequestException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function sendMessage($numero, $texto, $tokenKey) {

        $client = new Client();

        $options = [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer 3517973E-B10FA892-767468D4-D1748981-02212FD7 ',
            ],
            'json' => [
                'tokenKey'     => $tokenKey,
                'envioGrupo'   => false,
                'numero'       => '55'.$numero,
                'texto'         => $texto,
            ],
            'verify' => false
        ];

        try {
            $response = $client->post('https://api.apizap.me/v1/message/text', $options);
    
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
