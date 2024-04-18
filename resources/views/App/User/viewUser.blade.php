@extends('App.layout')
@section('app')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            <div class="row">
                <div class="col-lg-4 col-md-3 col-6 mb-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <span class="fw-semibold d-block mb-1">Total de Registros</span>
                            </div>
                            
                            <h3 class="card-title text-white mb-2"> {{ $todos->count() }} </h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 col-6 mb-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <span class="fw-semibold d-block mb-1">Usuários</span>
                            </div>
                            
                            <h3 class="card-title text-white mb-2"> {{ $usuarios }} </h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 col-6 mb-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <span class="fw-semibold d-block mb-1">Apoiadores</span>
                            </div>
                            
                            <h3 class="card-title text-white mb-2"> {{ $apoiadores }} </h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 col-6 mb-4">
                    <div class="card bg-dark text-white">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <span class="fw-semibold d-block mb-1">Coordenadores</span>
                            </div>
                            
                            <h3 class="card-title text-white mb-2"> {{ $coordenadores }} </h3>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 col-6 mb-4">
                    <div class="card bg-secondary  text-white">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <span class="fw-semibold d-block mb-1">Rede</span>
                            </div>
                            
                            <h3 class="card-title text-white mb-2"> {{ $rede }} </h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card p-3"> 
                    <div class="table-responsive">
                        <table class="table table-hover" id="tabela">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>D. Nascimento</th>
                                    <th>Sexo</th>
                                    <th>Profissão</th>
                                    <th>WhatsApp</th>
                                    <th>Email</th>
                                    <th>instagram</th>
                                    <th>facebook</th>
                                    <th>CEP</th>
                                    <th>Endereço</th>
                                    <th>N°</th>
                                    <th>Bairro</th>
                                    <th>Cidade</th>
                                    <th>Estado</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Opções</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($todos as $key => $todo)
                                    <tr>
                                        <td><strong>{{ $todo->nome }}</strong> </td>
                                        <td>{{ $todo->DataFormatada }}</td>
                                        <td>{{ $todo->Sexualidade }}</td>
                                        <td>{{ $todo->profissao }}</td>
                                        <td>{{ $todo->whatsapp }}</td>
                                        <td>{{ $todo->email }}</td>
                                        <td>{{ $todo->instagram }}</td>
                                        <td>{{ $todo->facebook }}</td>
                                        <td>{{ $todo->cep }}</td>
                                        <td>{{ $todo->logradouro }}</td>
                                        <td>{{ $todo->numero }}</td>
                                        <td>{{ $todo->bairro }}</td>
                                        <td>{{ $todo->cidade }}</td>
                                        <td>{{ $todo->estado }}</td>
                                        <td class="text-center"><span class="badge bg-label-success me-1">{{ $todo->Type }}</span></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalDelete{{ $todo->id }}"> <i class="tf-icons bx bx-trash"></i> </button>
                                            <a href="{{ route('viewUser', ['id' => $todo->id]) }}" class="btn btn-outline-warning"> <i class="tf-icons bx bx-edit-alt"></i> </a>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalDelete{{ $todo->id }}" aria-labelledby="modalDelete{{ $todo->id }}" tabindex="-1" style="display: none" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('deleteUser') }}" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalDelete{{ $todo->id }}">Excluir {{ $todo->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $todo->id }}">
                                                        <div class="mb-3">
                                                            <p>Para confirmar a exclusão, confirme sua senha:</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="password" class="form-control" name="password" placeholder="Confirme sua senha:" autofocus/>
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
@endsection