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
                                <a class="dropdown-item" href="{{ route('log') }}">Log</a>
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
                                    <th>URL</th>
                                    <th>Número</th>
                                    <th class="text-center">Opções</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($whatsapps as $key => $whatsapp)
                                    <tr>
                                        <td><strong>{{ $whatsapp->name }}</strong> </td>
                                        <td>{{ $whatsapp->url }}</td>
                                        <td>{{ $whatsapp->number }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('delete-whatsapp') }}" method="POST" class="delete">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $whatsapp->id }}">
                                                <button type="submit" class="btn btn-outline-danger"> <i class="tf-icons bx bx-trash"></i> </button>
                                            </form>
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

    <div class="modal fade" id="modalWhatsapp" aria-labelledby="modalWhatsapp" tabindex="-1" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('registrer-whatsapp') }}" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalWhatsapp">Cadastrar WhatsApp</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <input type="text" class="form-control" name="name" placeholder="Nome:" required/>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <input type="text" class="form-control" name="url" placeholder="URL:" required/>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <input type="text" class="form-control" name="number" placeholder="Número:" required/>
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