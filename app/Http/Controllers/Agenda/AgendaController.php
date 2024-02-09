<?php

namespace App\Http\Controllers\Agenda;

use App\Http\Controllers\Controller;

use App\Models\Agenda;
use App\Models\Grupo;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AgendaController extends Controller {
    
    public function listEvent() {

        $events = Auth::user()->tipo == 1 ? Agenda::all() : Agenda::where('id_lider', Auth::user()->id)->orWhere('id_criador', Auth::user()->id)->get();
        $alphas = Auth::user()->tipo == 1 ? User::whereIn('tipo', [1, 2, 3])->get() : User::whereIn('tipo', [1, 2, 3])->where('id_lider', Auth::user()->id)->get();
        $grupos = Auth::user()->tipo == 1 ? Grupo::all() : Grupo::where('id_lider', Auth::user()->id)->get();

        return view('App.Agenda.listEvent', ['events' => $events, 'alphas' => $alphas, 'grupos' => $grupos]);
    }

    public function registrerEvent(Request $request) {

        $event              = new Agenda();
        $event->id_criador  = Auth::user()->id;
        $event->id_lider    = $request->id_lider;
        $event->id_grupo    = $request->id_grupo;
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

        if ($request->filled('id_lider')) {
            $query->where('id_lider', $request->input('id_lider'));
        }

        if ($request->filled('id_grupo')) {
            $query->where('id_grupo', $request->input('id_grupo'));
        }

        $events = $query->get();
        $alphas = Auth::user()->tipo == 1 ? User::whereIn('tipo', [1, 2, 3])->get() : User::whereIn('tipo', [1, 2, 3])->where('id_lider', Auth::user()->id)->get();
        $grupos = Auth::user()->tipo == 1 ? Grupo::all() : Grupo::where('id_lider', Auth::user()->id)->get();

        return view('App.Agenda.listEvent', ['events' => $events, 'alphas' => $alphas, 'grupos' => $grupos]);
    }

    public function updateEvent(Request $request) {

        $event = Agenda::where('id', $request->id)->first();
        if($event) {
            $event->nome        = $request->nome;
            $event->descricao   = $request->descricao;
            $event->id_lider    = $request->id_lider;
            $event->id_grupo    = $request->id_grupo;
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
