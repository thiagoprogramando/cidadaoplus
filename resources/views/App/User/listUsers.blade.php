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
                                <a class="dropdown-item" href="{{ route('registrerUser', ['tipo' => $tipo]) }}">Cadastrar</a>
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalImport">Importar Registros</a>
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalFilter">Filtrar</a>
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
                                    <th class="d-none">Identificador Apoiador</th>
                                    <th>Nome</th>
                                    <th class="d-none">D. Nascimento</th>
                                    <th>Sexo</th>
                                    <th>Profissão</th>
                                    <th>WhatsApp</th>
                                    <th class="d-none">Email</th>
                                    <th class="d-none">instagram</th>
                                    <th class="d-none">facebook</th>
                                    <th class="d-none">CEP</th>
                                    <th class="d-none">N°</th>
                                    <th class="d-none">Bairro</th>
                                    <th class="d-none">Cidade</th>
                                    <th class="d-none">Estado</th>

                                    <th>Apoiador</th>
                                    <th>Grupo</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Opções</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td class="d-none">{{ $user->id_lider }}</td>
                                        <td><strong>{{ $user->nome }}</strong> </td>
                                        <td class="d-none">{{ $user->dataNasc }}</td>
                                        <td>{{ $user->Sexualidade }}</td>
                                        <td>{{ $user->profissao }}</td>
                                        <td>{{ $user->whatsapp }}</td>
                                        <td class="d-none">{{ $user->email }}</td>
                                        <td class="d-none">{{ $user->instagram }}</td>
                                        <td class="d-none">{{ $user->facebook }}</td>
                                        <td class="d-none">{{ $user->cep }}</td>
                                        <td class="d-none">{{ $user->logradouro }}</td>
                                        <td class="d-none">{{ $user->numero }}</td>
                                        <td class="d-none">{{ $user->bairro }}</td>
                                        <td class="d-none">{{ $user->cidade }}</td>
                                        <td class="d-none">{{ $user->estado }}</td>
                                        <td>@if(isset($user->lider->id)) <a href="{{ route('viewUser', ['id' => $user->lider->id ]) }}">{{ $user->lider->nome }}</a> @else --- @endif</td>
                                        <td>@if(isset($user->grupo->nome)) {{ $user->grupo->nome }} @else --- @endif</td>
                                        <td class="text-center"><span class="badge bg-label-success me-1">{{ $user->Type }}</span></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalDelete{{ $user->id }}"> <i class="tf-icons bx bx-trash"></i> </button>
                                            <a href="{{ route('viewUser', ['id' => $user->id]) }}" class="btn btn-outline-warning"> <i class="tf-icons bx bx-edit-alt"></i> </a>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalDelete{{ $user->id }}" aria-labelledby="modalDelete{{ $user->id }}" tabindex="-1" style="display: none" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('deleteUser') }}" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalDelete{{ $user->id }}">Excluir {{ $user->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $user->id }}">
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

    <div class="modal fade" id="modalFilter" aria-labelledby="modalFilter" tabindex="-1" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('filterUser') }}" method="GET">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFilter">Filtrar Registros</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-6 mb-3">
                                <input type="text" class="form-control" name="nome" placeholder="Nome:"/>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 mb-3">
                                <input type="text" class="form-control" name="dataNasc" oninput="mascaraData(this)" placeholder="Data Nascimento:"/>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <select name="id_lider" class="form-control">
                                    <option value="{{ Auth::user()->id }}" selected>Apoiador</option>
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
                            <div class="col-12 col-md-6 col-lg-6 mb-3">
                                <select name="tipo" class="form-control">
                                    <option value="" selected>Tipo</option>
                                    @if (Auth::user()->tipo == 1) <option value="1">Master</option> @endif
                                    <option value="2">Apoiador</option>
                                    <option value="3">Eleitor</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 mb-3">
                                <select name="sexo" class="form-control">
                                    <option value="" selected>Sexo</option>
                                    <option value="1">Masculino</option>
                                    <option value="2">Feminino</option>
                                    <option value="3">Outros</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 mb-3">
                                <select name="profissao" class="form-control">
                                    <option value="" selected>Profissão</option>
                                    <option value="outros">Outros</option>
                                    <option value="do_lar">Do lar</option>
                                    <option value="autonomo">Autônomo</option>
                                    <option value="advogado">Advogado</option>
                                    <option value="arquiteto">Arquiteto</option>
                                    <option value="assistente_social">Assistente Social</option>
                                    <option value="biologo">Biólogo</option>
                                    <option value="chef_de_cozinha">Chef de Cozinha</option>
                                    <option value="dentista">Dentista</option>
                                    <option value="designer_grafico">Designer Gráfico</option>
                                    <option value="enfermeiro">Enfermeiro</option>
                                    <option value="engenheiro_civil">Engenheiro Civil</option>
                                    <option value="escritor">Escritor</option>
                                    <option value="fisioterapeuta">Fisioterapeuta</option>
                                    <option value="geologo">Geólogo</option>
                                    <option value="historiador">Historiador</option>
                                    <option value="jornalista">Jornalista</option>
                                    <option value="medico">Médico</option>
                                    <option value="musico">Músico</option>
                                    <option value="nutricionista">Nutricionista</option>
                                    <option value="odontologo">Odontólogo</option>
                                    <option value="piloto">Piloto</option>
                                    <option value="psicologo">Psicólogo</option>
                                    <option value="quimico">Químico</option>
                                    <option value="radiologista">Radiologista</option>
                                    <option value="sociologo">Sociólogo</option>
                                    <option value="tecnico_de_informatica">Técnico de Informática</option>
                                    <option value="veterinario">Veterinário</option>
                                    <option value="web_designer">Web Designer</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 mb-3">
                                <input type="number" class="form-control" name="cep" placeholder="CEP:"/>
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

    <div class="modal fade" id="modalImport" aria-labelledby="modalImport" tabindex="-1" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('importUser') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalImport">Importar Registros</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <p>Faça o download do <a download href="{{ asset('template/archive/modelo_importar_usuarios.xlsx') }}">Modelo</a></p>
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <input type="file" class="form-control" name="arquivo" accept=".xlsx" placeholder="Arquivo XLSX:"/>
                            </div>
                            <div class="col-12 col-md-12 col-md-12 mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Confirme sua senha:"/>
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