@extends('App.layout')
@section('app')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            <div class="col-12">
                <div class="mt-3 mb-3">
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <button type="button" onclick="geraExcel()" class="btn btn-outline-secondary"> <i class="tf-icons bx bx-download"></i> </button>
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opções </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalWhatsapp">Cadastrar</a>
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalFilter">Envio em Massa</a>
                            </div>
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
                                    <th>Webhook</th>
                                    <th>Token</th>
                                    <th>Status</th>
                                    <th class="text-center">Opções</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($whatsapps as $key => $whatsapp)
                                    <tr>
                                        <td><strong>{{ $whatsapp->instanceName }}</strong> </td>
                                        <td>{{ $whatsapp->webhookUrl }}</td>
                                        <td>{{ $whatsapp->tokenKey }}</td>
                                        <td>{{ $whatsapp->status }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalDelete{{ $whatsapp->id }}"> <i class="tf-icons bx bx-trash"></i> </button>
                                            <a href="https://api.apizap.me/v1/instance/qrcode?tokenKey={{$whatsapp->tokenKey}}&online=true" target="_blank" class="btn btn-outline-success"> <i class="tf-icons bx bx-qr"></i> </a>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalDelete{{ $whatsapp->id }}" aria-labelledby="modalDelete{{ $whatsapp->id }}" tabindex="-1" style="display: none" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('deleteWhatsapp') }}" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalDelete{{ $whatsapp->id }}">Excluir {{ $whatsapp->instanceName }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $whatsapp->id }}">
                                                        <div class="mb-3">
                                                            <p>Para confirmar a exclusão, confirme sua senha:</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="text" class="form-control" name="password" placeholder="Confirme sua senha:" autofocus/>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"> Cancelar </button>
                                                        <button type="submit" class="btn btn-success"> Confirmar </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="modalWhatsapp" aria-labelledby="modalWhatsapp" tabindex="-1" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('registrerWhatsapp') }}" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalWhatsapp">Cadastrar WhatsApp</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <input type="text" class="form-control" name="instanceName" placeholder="Nome:" required/>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <input type="text" class="form-control" name="webhookUrl" placeholder="WebHook:" value="{{ env('APP_WHATSAPP_URL') }}" readonly/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"> Cancelar </button>
                        <button type="submit" class="btn btn-success"> Confirmar </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection