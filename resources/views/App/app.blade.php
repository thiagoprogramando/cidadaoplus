@extends('App.layout')
@section('app')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            <div class="col-12 mb-3">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6 mb-3">
                        <div class="card bg-dark text-white h-100 mb-3">
                            <div class="card-header">Link (Formulário de Pesquisa) </div>
                            <div class="card-body">
                                <a onclick="copyToClipboard(this)" data-link="{{ env('APP_URL') }}/pesquisa-cidadao/{{ Auth::user()->id }}" href="#" class="text-info"> {{ env('APP_URL') }}/pesquisa-cidadao/{{ Auth::user()->id }} </a>
                                <br>
                                <a onclick="copyToClipboard(this)" data-link="{{ env('APP_URL') }}/pesquisa-cidadao/{{ Auth::user()->id }}" href="#" class="btn btn-success mt-2">Copiar Link</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-6 mb-3">
                        <div class="card bg-light text-white h-100 mb-3">
                            <div class="card-body">
                                <div class="div-qrcode">
                                    <a class="qrcode" href="#" class="text-info" data-link="{{ env('APP_URL') }}/pesquisa-cidadao/{{ Auth::user()->id }}"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 row">

                @if (Auth::user()->type == 1)
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                        <a href="{{ route('listUser', ['type' => 1]) }}">
                            <div class="card bg-dark text-white text-center mb-3">
                                <div class="card-header">Administrador</div>
                                <div class="card-body">
                                    <i class="menu-icon tf-icons bx-lg bx bx-user"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                        <a href="{{ route('listUser', ['type' => 4]) }}">
                            <div class="card bg-dark text-white text-center mb-3">
                                <div class="card-header">Coordenador</div>
                                <div class="card-body">
                                    <i class="menu-icon tf-icons bx-lg bx bx-user-check"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                @if (Auth::user()->type == 1 || Auth::user()->type == 4)
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                        <a href="{{ route('listUser', ['type' => 2]) }}">
                            <div class="card bg-dark text-white text-center mb-3">
                                <div class="card-header">Apoiador</div>
                                <div class="card-body">
                                    <i class="menu-icon tf-icons bx-lg bx bx-user-voice"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                    <a href="{{ route('listUser', ['type' => 3]) }}">
                        <div class="card bg-dark text-white text-center mb-3">
                            <div class="card-header">Cidadão</div>
                            <div class="card-body">
                                <i class="menu-icon tf-icons bx-lg bx bx-user-pin"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection