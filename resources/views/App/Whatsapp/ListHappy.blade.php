@extends('App.layout')
@section('app')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            <div class="col-12">
                <div class="mt-3 mb-3">
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <button type="button" onclick="geraExcel()" class="btn btn-outline-secondary"> <i class="tf-icons bx bx-download"></i> </button>
                        <div class="btn-group" role="group">
                            <a href="{{ route('send-happy') }}" class="btn btn-outline-success"><i class="tf-icons bx bxl-whatsapp"></i> Disparar para todos</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card p-3"> 
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover" id="tabela">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th class="text-center">Whatsapp</th>
                                    <th class="text-center">Opções</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td title="{{ $user->nome }}"><strong>{{ strlen($user->nome) > 40 ? substr($user->nome, 0, 40) . '...' : $user->nome }}</strong></td>
                                        <td class="text-center">{{ $user->whatsapp }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('send-happy', ['number' => $user->whatsapp]) }}" class="btn btn-outline-success"> <i class="tf-icons bx bxl-whatsapp"></i> </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection