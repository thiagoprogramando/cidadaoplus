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
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalMessage">Cadastrar</a>
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
                                    <th>Texto</th>
                                    <th class="text-center">Lote</th>
                                    <th>Status</th>
                                    <th class="text-center">Enviado:</th>
                                    <th class="text-center">Opções</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($messages as $key => $message)
                                    <tr>
                                        <td title="{{ $message->texto }}"><strong>{{ strlen($message->texto) > 20 ? substr($message->texto, 0, 20) . '...' : $message->texto }}</strong></td>
                                        <td class="text-center">{{ $message->code }}</td>
                                        <td>{{ $message->status }}</td>
                                        <td class="text-center">{{ $message->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('log', ['code' => $message->code]) }}" class="btn btn-outline-success"> <i class="tf-icons bx bx-list-check"></i> </a>
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

    <div class="modal fade" id="modalMessage" aria-labelledby="modalMessage" tabindex="-1" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('registrerMessage') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalMessage">Cadastrar Mensagem</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <textarea name="texto" class="form-control" rows="3" placeholder="Escreva sua Mensagem:" required></textarea>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <p>(Mídia) Apenas <a href="#">imagens</a> são permitidas!</p>
                                <input type="file" class="form-control" name="base64" accept="image/*" placeholder="Mídia:"/>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <p>(XLSX com contatos) Faça o download do <a download href="{{ asset('template/archive/modelo_disparar_messagens.xlsx') }}">Modelo</a></p>
                                <input type="file" class="form-control" name="numero" accept=".xlsx" placeholder="CSV com os Números:" required/>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <select name="whatsapp_id" class="form-control" required>
                                    <option value="">WhatsApp</option>
                                    @foreach ($whatsapps as $whatsapp)
                                        <option value="{{ $whatsapp->id }}">{{ $whatsapp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Confirme sua senha:" required>
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