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
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class WhatsappController extends Controller {
    
    public function listWhatsapp() {

        $whatsapps = Whatsappp::all();
        return view('App.Whatsapp.listWhatsapp', ['whatsapps' => $whatsapps]);
    }

    public function registrerWhatsapp(Request $request) {

        $whatsapp = new Whatsappp();
        $whatsapp->name     = $request->name;
        $whatsapp->url      = $request->url;
        $whatsapp->number   = $request->number;
        if($whatsapp->save()) {
            return redirect()->back()->with('success', 'WhatsApp criado com sucesso!');
        }

        return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
    }

    public function deleteWhatsapp(Request $request) {

        $whatsapp = Whatsappp::find($request->id);
        if ($whatsapp) {
            $whatsapp->delete();
            return redirect()->back()->with('success', 'Whatsapp excluído com sucesso!');
        }
            
        return redirect()->back()->with('error', 'Não encontramos dados do Whatsapp, tente novamente mais tarde!');
    }

    private function createLog($numbers, $response, $status) {

        $log                = new MensagemLog();
        $log->numbers       = $numbers;
        $log->response      = $response;
        $log->status        = $status;
        if($log->save()) {
            return true;
        }

        return false;
    }

    public function log() {

        $logs = MensagemLog::orderBy('created_at', 'desc')->get();
        return view('App.Whatsapp.listLog', ['logs' => $logs]);
    }

    public function listHappy() {

        $users = User::whereMonth('dataNasc', now()->month)->whereDay('dataNasc', now()->day)->get();
        
        return view('App.Whatsapp.ListHappy', [
            'users' => $users,
            'whatsapps' => Whatsappp::all()
        ]);
    }

    public function sendHappy($number = null) {

        $whatsapp = Whatsappp::first();
        if(!$whatsapp) {
            return redirect()->back()->with('error', 'Nenhum Whatsapp cadastrado!');
        }

        if(!$number) {
            $users = User::whereMonth('dataNasc', now()->month)->whereDay('dataNasc', now()->day)->get();
            $numbers = $users->pluck('whatsapp')->toArray();
        } else {
            $numbers = [$number];
        }

        $numbers = array_filter(array_map(function($num) {
            $num = preg_replace('/[^0-9]/', '', $num);
            if (strpos($num, '84') === 0) {
                if (strlen($num) > 10) {
                    return $num = "5584" . substr($num, 3);
                }
                return '55'.$num;
            } else {
                return null;
            }
        }, $numbers));

        $client = new Client();

        $options = [
            'headers' => [
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'numbers'   => $numbers,
                'image'     => "https://tocomkleberfernandes.com.br/storage/whatsapp/happy.jpg",
                'message'   => "Olá, passando para te desejar um feliz aniversário 🎂🎉",
            ],
            'verify' => false
        ];

        try {
            $response = $client->post($whatsapp->url, $options);
    
            if ($response->getStatusCode() === 200) {
                $this->createLog(implode(', ', $numbers), 'Disparo concluído com Sucesso!', 'success');
                return redirect()->back()->with('success', 'Disparo concluído com Sucesso!');
            } else {
                $this->createLog(implode(', ', $numbers), 'Disparo não efetuado!', 'error');
                return redirect()->back()->with('error', 'Disparo não efetuado!');
            }
        } catch (RequestException $e) {
            $this->createLog(implode(', ', $numbers), $e->getMessage(), 'error');
            return redirect()->back()->with('error', 'Disparo não efetuado!');
        }
    }
}
