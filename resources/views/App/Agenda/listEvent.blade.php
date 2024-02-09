@extends('App.layout')
@section('app')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            @if (Auth::user()->tipo == 1 || Auth::user()->tipo == 4)
                <div class="col-12">
                    <div class="mt-3 mb-3">
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <button type="button" onclick="geraExcel()" class="btn btn-outline-secondary"> <i class="tf-icons bx bx-download"></i> </button>
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Opções </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalEvent">Cadastrar</a>
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalFilter">Filtrar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-12">
                <div class="card p-3"> 
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover" id="tabela">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Data | Hora</th>
                                    <th>Apoiador</th>
                                    <th>Grupo</th>
                                    <th class="text-center">Opções</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($events as $key => $event)
                                    <tr>
                                        <td><strong>{{ $event->nome }}</strong> </td>
                                        <td>{{ \Carbon\Carbon::parse($event->data)->format('d/m/Y') }} | {{ $event->hora }} </td>
                                        <td>{{ $event->lider?->nome }}</td>
                                        <td>{{ $event->grupo?->nome }}</td>
                                        <td class="text-center">
                                            @if (Auth::user()->tipo == 1 || Auth::user()->tipo == 4)
                                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalDelete{{ $event->id }}"> <i class="tf-icons bx bx-trash"></i> </button>
                                                <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalUpdate{{ $event->id }}"> <i class="tf-icons bx bx-edit-alt"></i> </button>
                                            @endif
                                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalViewEvent{{ $event->id }}"> <i class="tf-icons bx bx-book-content"></i> </button>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalUpdate{{ $event->id }}" aria-labelledby="modalUpdate{{ $event->id }}" tabindex="-1" style="display: none" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('updateEvent') }}" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalUpdate{{ $event->id }}">Editar Evento</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @csrf
                                                        <div class="row">
                                                            <input type="hidden" name="id" value="{{ $event->id }}">
                                                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                                                <input type="text" class="form-control" name="nome" placeholder="Nome:" value="{{ $event->nome }}"/>
                                                            </div>
                                                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                                                <textarea name="descricao" class="form-control editor" rows="5">{{ $event->descricao }}</textarea>
                                                            </div>
                                                            <div class="col-12 col-md-6 col-lg-6 mb-3">
                                                                <input type="text" class="form-control" name="data" oninput="mascaraData(this)" placeholder="Data:" value="{{ $event->DataFormatada }}"/>
                                                            </div>
                                                            <div class="col-12 col-md-6 col-lg-6 mb-3">
                                                                <input type="time" class="form-control" name="hora" placeholder="Hora:" value="{{ $event->hora }}"/>
                                                            </div>
                                                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                                                <select name="id_lider" class="form-control">
                                                                    <option value="{{ $event->id_lider }}" selected>Apoiador</option>
                                                                    <option value="{{ Auth::user()->tipo }}">{{ Auth::user()->nome }}</option>
                                                                    @foreach ($alphas as $alpha)
                                                                        <option value="{{ $alpha->id }}">{{ $alpha->nome }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                                                <select name="id_grupo" class="form-control">
                                                                    <option value="{{ $event->id_grupo }}" selected>Grupo </option>
                                                                    @foreach ($grupos as $grupo)
                                                                        <option value="{{ $grupo->id }}">{{ $grupo->nome }}</option>
                                                                    @endforeach
                                                                </select>
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

                                    <div class="modal fade" id="modalDelete{{ $event->id }}" aria-labelledby="modalDelete{{ $event->id }}" tabindex="-1" style="display: none" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('deleteEvent') }}" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalDelete{{ $event->id }}">Excluir {{ $event->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $event->id }}">
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

                                    <div class="modal fade" id="modalViewEvent{{ $event->id }}" aria-labelledby="modalViewEvent{{ $event->id }}" tabindex="-1" style="display: none" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('updateEvent') }}" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalViewEvent{{ $event->id }}">Detalhes do Evento</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                                                {!! $event->descricao !!}
                                                            </div>
                                                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                                                <center>
                                                                    <a class="qrcode" href="#" class="text-info" data-link="{{ env('APP_URL') }}/cadastra-usuario/{{ $event->id_lider }}/{{ $event->id_grupo }}"></a>
                                                                </center>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close"> Fechar </button>
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

    <div class="modal fade" id="modalEvent" aria-labelledby="modalEvent" tabindex="-1" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('registrerEvent') }}" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEvent">Cadastrar Evento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <input type="text" class="form-control" name="nome" placeholder="Nome:"/>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <textarea name="descricao" class="form-control editor" rows="5"></textarea>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 mb-3">
                                <input type="text" class="form-control" name="data" oninput="mascaraData(this)" placeholder="Data:"/>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 mb-3">
                                <input type="time" class="form-control" name="hora" placeholder="Hora:"/>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <select name="id_lider" class="form-control">
                                    <option value="{{ Auth::user()->tipo }}" selected>Apoiador</option>
                                    @foreach ($alphas as $alpha)
                                        <option value="{{ $alpha->id }}">{{ $alpha->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <select name="id_grupo" class="form-control">
                                    <option value="" selected>Grupo </option>
                                    @foreach ($grupos as $grupo)
                                        <option value="{{ $grupo->id }}">{{ $grupo->nome }}</option>
                                    @endforeach
                                </select>
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

    <div class="modal fade" id="modalFilter" aria-labelledby="modalFilter" tabindex="-1" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('filterEvent') }}" method="GET">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFilter">Filtrar Registros</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <input type="text" class="form-control" name="nome" placeholder="Nome:"/>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 mb-3">
                                <input type="text" class="form-control" name="data" oninput="mascaraData(this)" placeholder="Data:"/>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 mb-3">
                                <input type="time" class="form-control" name="hora" placeholder="Hora:"/>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <select name="id_lider" class="form-control">
                                    <option value="" selected>Apoiador</option>
                                    <option value="{{ Auth::user()->tipo }}">{{ Auth::user()->nome }}</option>
                                    @foreach ($alphas as $alpha)
                                        <option value="{{ $alpha->id }}">{{ $alpha->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <select name="id_grupo" class="form-control">
                                    <option value="" selected>Grupo </option>
                                    @foreach ($grupos as $grupo)
                                        <option value="{{ $grupo->id }}">{{ $grupo->nome }}</option>
                                    @endforeach
                                </select>
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