<html lang="pt-br" class="light-style customizer-hide">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>#Tô com Kleber - {{ Auth::user()->nome }}</title>

        <link rel="icon" type="image/x-icon" href="{{ asset('template/img/background/icon.png') }}" />
        <link rel="preconnect" href="https://fonts.googleapis.com"/>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>

        <link rel="stylesheet" href="{{ asset('template/vendor/fonts/boxicons.css') }}"/>
        <link rel="stylesheet" href="{{ asset('template/vendor/css/core.css') }}" class="template-customizer-core-css"/>
        <link rel="stylesheet" href="{{ asset('template/vendor/css/theme-default.css') }}" class="template-customizer-theme-css"/>
        <link rel="stylesheet" href="{{ asset('template/css/demo.css') }}"/>
        <link rel="stylesheet" href="{{ asset('template/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}"/>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css"/>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>

    <body>

        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">

                <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                    <div class="app-brand demo">
                        <a href="{{ route('app') }}" class="app-brand-link">
                            <span class="app-brand-text demo menu-text fw-bolder ms-2">#Tô com Kleber</span>
                        </a>

                        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                            <i class="bx bx-chevron-left bx-sm align-middle"></i>
                        </a>
                    </div>

                    <div class="menu-inner-shadow"></div>

                    <ul class="menu-inner py-1">

                        <li class="menu-item active">
                            <a href="{{ route('app') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                <div data-i18n="Analytics">Início</div>
                            </a>
                        </li>

                        <li class="menu-header small text-uppercase"><span class="menu-header-text">Cadastro</span></li>
                        <li class="menu-item">
                            <a href="{{ route('listUser', ['tipo' => 3]) }}" class="menu-link">
                              <i class="menu-icon tf-icons bx bx-user-pin"></i>
                              <div>Eleitores</div>
                            </a>
                        </li>
                        
                        @if (Auth::user()->tipo == 4 || Auth::user()->tipo == 1)
                            <li class="menu-item">
                                <a href="{{ route('listUser', ['tipo' => 2]) }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-user-voice"></i>
                                    <div>Apoiadores</div>
                                </a>
                            </li>
                        @endif

                        @if (Auth::user()->tipo == 1)
                            <li class="menu-item">
                                <a href="{{ route('listUser', ['tipo' => 4]) }}" class="menu-link">
                                    <i class='menu-icon tf-icons bx bx-user-check'></i>
                                    <div>Coordenadores</div>
                                </a>
                            </li>

                            <li class="menu-item">
                                <a href="{{ route('listUser', ['tipo' => 1]) }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-user"></i>
                                <div>Master</div>
                                </a>
                            </li>
                        @endif
                        <li class="menu-item">
                            <a href="{{ route('listReport') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-data"></i>
                                <div>Relatórios</div>
                            </a>
                        </li>

                        <li class="menu-header small text-uppercase"><span class="menu-header-text">Agenda & Comunicação</span></li>
                        <li class="menu-item">
                            <a href="{{ route('listEvent') }}" class="menu-link">
                              <i class="menu-icon tf-icons bx bx-calendar-star"></i>
                              <div>Eventos</div>
                            </a>
                        </li>
                        @if (Auth::user()->tipo == 1)
                            <li class="menu-item">
                                <a href="{{ route('listMessage') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-message-rounded-dots"></i>
                                    <div>Envio em Massa</div>
                                </a>
                            </li>
                        

                            <li class="menu-header small text-uppercase"><span class="menu-header-text">Integrações</span></li>
                            <li class="menu-item">
                                <a href="{{ route('listWhatsapp') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bxl-whatsapp"></i>
                                    <div>WhatsApp</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="#" class="menu-link">
                                    <i class="menu-icon tf-icons bx bxs-file-import"></i>
                                    <div>TSE</div>
                                </a>
                            </li>
                        @endif
                    </ul>
                </aside>

                <div class="layout-page">
                    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                                <i class="bx bx-menu bx-sm"></i>
                            </a>
                        </div>

                        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                            
                            <div class="navbar-nav align-items-center d-none d-sm-block">
                                <div class="nav-item d-flex align-items-center">
                                    {{-- <i><b>{{ $phrase }}</b></i> --}}
                                </div>
                            </div>

                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                
                                <li class="nav-item lh-1 me-3">
                                    <a class="github-button" data-icon="octicon-star" data-size="large" data-show-count="true">{{ Auth::user()->name }}</a>
                                </li>

                                <li class="nav-item navbar-dropdown dropdown-user dropdown">

                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                        <div class="avatar avatar-online">
                                            <img src="@if(Auth::user()->sexo == 1) {{ asset("template/img/avatar/man.png") }} @else {{ asset("template/img/avatar/woman.png") }} @endif" class="w-px-40 h-auto rounded-circle"/>
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('profile') }}">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar avatar-online">
                                                            <img src="@if(Auth::user()->sexo == 1) {{ asset("template/img/avatar/man.png") }} @else {{ asset("template/img/avatar/woman.png") }} @endif" class="w-px-40 h-auto rounded-circle"/>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <span class="fw-semibold d-block">{{ Auth::user()->whatsapp }}</span>
                                                        <small class="text-muted">{{ Auth::user()->Type }}</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('profile') }}">
                                                <i class="bx bx-user me-2"></i>
                                                <span class="align-middle">Meus Dados</span>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('logout') }}">
                                                <i class="bx bx-power-off me-2"></i> <span class="align-middle">Sair</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </nav>

                    <div class="content-wrapper">
                        @yield('app')
                    </div>

                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="bs-toast toast toast-placement-ex m-2 fade bg-success bottom-0 start-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
                <div class="toast-header">
                    <i class="bx bx-bell me-2"></i>
                    <div class="me-auto fw-semibold">Sucesso!</div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <p id="messageToast">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bs-toast toast toast-placement-ex m-2 fade bg-danger bottom-0 start-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
                <div class="toast-header">
                    <i class="bx bx-bell me-2"></i>
                    <div class="me-auto fw-semibold">Erro!</div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <p id="messageToast">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if(session('infor'))
            <div class="bs-toast toast toast-placement-ex m-2 fade bg-warning bottom-0 start-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
                <div class="toast-header">
                    <i class="bx bx-bell me-2"></i>
                    <div class="me-auto fw-semibold">Atenção!</div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <p id="messageToast">{{ session('infor') }}</p>
                </div>
            </div>
        @endif

        <script src="{{ asset('template/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('template/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('template/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('template/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('template/vendor/js/menu.js') }}"></script>
        <script src="{{ asset('template/vendor/js/helpers.js') }}"></script>

        <script src="{{ asset('template/js/main.js') }}"></script>
        <script src="{{ asset('template/js/ui-toasts.js') }}"></script>
        <script src="{{ asset('template/js/mask.js') }}"></script>
        <script src="{{ asset('template/js/config.js') }}"></script>
    
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.2/xlsx.full.min.js"></script>
        <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
        <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function () {

                var elementosQrCode = document.querySelectorAll('.qrcode');
                elementosQrCode.forEach(function(elemento) {
                    var link = elemento.getAttribute("data-link");
                    var qrcode = new QRCode(elemento, {
                        text: link,
                        width: 128,
                        height: 128
                    });
                    elemento.href = link;
                });

                var qrCodeLinks = document.querySelectorAll(".qrcode");
                qrCodeLinks.forEach(function(linkElement) {
                    linkElement.addEventListener("click", function(event) {
                        event.preventDefault();

                        var link = linkElement.getAttribute("data-link");
                        html2canvas(linkElement).then(canvas => {
                            var imageData = canvas.toDataURL("image/png");
                            var downloadLink = document.createElement("a");
                            downloadLink.href = imageData;
                            downloadLink.download = "qrcode.png";
                            downloadLink.click();
                        });
                    });
                });

                $('#tabela').DataTable({
                    "paging": false,
                    "pageLength": 50,
                    "responsive": true,
                    "language": {
                        "url": "http://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
                    },
                    "autoWidth": false,
                    "order": []
                });
            });
        </script>
        
    </body>
</html>