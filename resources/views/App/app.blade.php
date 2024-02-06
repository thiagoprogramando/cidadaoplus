@extends('App.layout')
@section('app')

    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            {{-- <div class="col-12">
                <div class="mt-3 mb-3">
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opções </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item">Envio em Massa</a>
                                <a class="dropdown-item">Relatórios</a>
                                <a class="dropdown-item">Log de Erros</a>
                                <a class="dropdown-item">Log de Atividades</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

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
                                <center><a id="qrcode" href="{{ env('APP_URL') }}/cadastra-usuario/{{ Auth::user()->id }}" target="_blank" class="text-info" data-link="{{ env('APP_URL') }}/cadastra-usuario/{{ Auth::user()->id }}"></a></center>
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
                                    <div class="card-header">Liderança</div>
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var linkElement = document.getElementById("qrcode");
            var link = linkElement.getAttribute("data-link");

            var qrcode = new QRCode(linkElement, {
                text: link,
                width: 128,
                height: 128
            });

            linkElement.href = link;
            linkElement.appendChild(qrcode._el);
        });

    </script>
@endsection