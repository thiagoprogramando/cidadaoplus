<?php

namespace App\Http\Controllers\Agenda;

use App\Http\Controllers\Controller;

use App\Models\Agenda;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AgendaController extends Controller {
    
    public function listEvent() {

        $events = Agenda::all();
        return view('App.Agenda.listEvent', ['events' => $events]);
    }

    public function registrerEvent(Request $request) {

        $event              = new Agenda();
        $event->nome        = $request->nome;
        $event->descricao   = $request->descricao;
        $event->data        = Carbon::parse($request->data);
        $event->hora        = $request->hora;

        if($event->save()) {
            return redirect()->back()->with('success', 'Evento cadastrado com Sucesso!');
        }

        return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
    }

    public function filterEvent(Request $request) {

        $query = Agenda::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->input('nome') . '%');
        }

        if ($request->filled('data')) {
            $query->where('data', '=', Carbon::parse($request->input('data')));
        }

        if ($request->filled('hora')) {
            $query->where('hora', $request->input('hora'));
        }

        $events = $query->get();

        return view('App.Agenda.listEvent', ['events' => $events]);
    }

    public function updateEvent(Request $request) {

        $event = Agenda::where('id', $request->id)->first();
        if($event) {
            $event->nome        = $request->nome;
            $event->descricao   = $request->descricao;
            $event->data        =  Carbon::parse($request->data);
            $event->hora        = $request->hora;

            if($event->save()) {
                return redirect()->back()->with('success', 'Evento alterado com sucesso!');
            }

            return redirect()->back()->with('error', 'Encontramos um problema, tente novamente mais tarde!');
        }

        return redirect()->back()->with('error', 'Não encontramos dados do Evento, tente novamente mais tarde!');
    }

    public function deleteEvent(Request $request) {

        $password = $request->password;    
        if (Hash::check($password, auth()->user()->password)) {
           
            $event = Agenda::find($request->id);
            if ($event) {

                $event->delete();
                return redirect()->back()->with('success', 'Evento excluído com sucesso!');
            } else {
                return redirect()->back()->with('error', 'Não encontramos dados do Evento, tente novamente mais tarde!');
            }
        } else {
            return redirect()->back()->with('error', 'Senha incorreta!');
        }
    }

}
