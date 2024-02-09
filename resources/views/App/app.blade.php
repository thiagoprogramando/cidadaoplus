@extends('App.layout')
@section('app')

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            <div class="col-12 mb-3">
                <div class="row">

                    <div class="col-12 col-md-6 col-lg-6 mb-3">
                        <div class="card bg-dark text-white h-100 mb-3">
                            <div class="card-header">Links para indicação</div>
                            <div class="card-body">
                                <a onclick="copyToClipboard(this)" data-link="{{ env('APP_URL') }}/cadastra-usuario/{{ Auth::user()->id }}" href="#" class="text-info"> {{ env('APP_URL') }}/cadastra-usuario/{{ Auth::user()->id }} </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-6 mb-3">
                        <div class="card bg-light text-white h-100 mb-3">
                            <div class="card-body">
                                <center><a class="qrcode" href="#" class="text-info" data-link="{{ env('APP_URL') }}/cadastra-usuario/{{ Auth::user()->id }}"></a></center>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-12">

                <div class="row">
                    <div class="col-6 col-sm-6 col-md-4 col-lg-2">
                        <a href="{{ route('listUser', ['tipo' => 3]) }}">
                            <div class="card bg-dark text-white text-center mb-3">
                                <div class="card-header">Eleitores</div>
                                <div class="card-body">
                                    <i class="menu-icon tf-icons bx-lg bx bx-user-pin"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-sm-6 col-md-4 col-lg-2">
                        <a href="{{ route('listEvent') }}">
                            <div class="card bg-dark text-white text-center mb-3">
                                <div class="card-header">Eventos</div>
                                <div class="card-body">
                                    <i class="menu-icon tf-icons bx-lg bx bx-calendar-star"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    @if (Auth::user()->tipo == 1)
                        <div class="col-6 col-6 col-sm-6 col-md-4 col-lg-2">
                            <a href="{{ route('listUser', ['tipo' => 2]) }}">
                                <div class="card bg-dark text-white text-center mb-3">
                                    <div class="card-header">Apoiador</div>
                                    <div class="card-body">
                                        <i class="menu-icon tf-icons bx-lg bx bx-user-voice"></i>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-6 col-sm-6 col-md-4 col-lg-2">
                            <a href="{{ route('listUser', ['tipo' => 1]) }}">
                                <div class="card bg-dark text-white text-center mb-3">
                                    <div class="card-header">Master</div>
                                    <div class="card-body">
                                        <i class="menu-icon tf-icons bx-lg bx bx-user"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                
                        <div class="col-6 col-sm-6 col-md-4 col-lg-2">
                            <a href="{{ route('listWhatsapp') }}">
                                <div class="card bg-dark text-white text-center mb-3">
                                    <div class="card-header">WhatsApp</div>
                                    <div class="card-body">
                                        <i class="menu-icon tf-icons bx-lg bx bxl-whatsapp"></i>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-6 col-sm-6 col-md-4 col-lg-2">
                            <div class="card bg-dark text-white text-center mb-3">
                                <div class="card-header">TSE</div>
                                <div class="card-body">
                                    <i class="menu-icon tf-icons bx-lg bx bxs-file-import"></i>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
@endsection