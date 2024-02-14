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
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalGroup">Criar Grupo</a>    
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
                                    <th>Código</th>
                                    <th>Apoiador</th>
                                    <th class="text-center">Opções</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($grupos as $key => $grupo)
                                    <tr>
                                        <td>{{ $grupo->nome }}</td>
                                        <td><strong>{{ $grupo->code }}</strong> </td>
                                        <td>{{ $grupo->lider()->nome }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteGrupo{{ $grupo->id }}"> <i class="tf-icons bx bx-trash"></i> </button>
                                            <a onclick="copyToClipboard(this)" data-link="{{ env('APP_URL') }}/cadastra-usuario/{{ Auth::user()->id }}/{{ $grupo->id }}" id="indicationLink" href="#" class="btn btn-outline-info"> <i class="tf-icons bx bx-copy"></i> </a>
                                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalQrGrupo{{ $grupo->id }}"> <i class="tf-icons bx bx-qr"></i> </button>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalDeleteGrupo{{ $grupo->id }}" aria-labelledby="modalDeleteGrupo{{ $grupo->id }}" tabindex="-1" style="display: none" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('deleteGrupo') }}" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalDeleteGrupo{{ $grupo->id }}">Excluir {{ $grupo->nome }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $grupo->id }}">
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

                                    <div class="modal fade" id="modalQrGrupo{{ $grupo->id }}" aria-labelledby="modalQrGrupo{{ $grupo->id }}" tabindex="-1" style="display: none" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalQrGrupo{{ $grupo->id }}">QR CODE</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                 <div class="modal-body">
                                                    <center><a class="qrcode" href="#" class="text-info" data-link="{{ env('APP_URL') }}/cadastra-usuario/{{ $grupo->id_lider }}/{{ $grupo->id }}"></a></center>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"> Fechar </button>
                                                 </div>
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

    <div class="modal fade" id="modalGroup" aria-labelledby="modalGroup" tabindex="-1" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('createGrupo') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalGroup">Criar Grupo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <select name="id_lider" class="form-control">
                                    <option value="{{ Auth::user()->id }}" selected>Apoiador </option>
                                    @if(Auth::user()->type == 1)
                                        @foreach ($alphas as $alpha)
                                            <option value="{{ $alpha->id }}">{{ $alpha->nome }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-12 col-md-12 col-md-12 mb-3">
                                <input type="text" class="form-control" name="nome" placeholder="Nome:" required/>
                            </div>
                            <div class="col-12 col-md-12 col-md-12 mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Confirme sua senha:" required/>
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