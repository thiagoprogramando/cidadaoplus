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
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalFilter">Filtrar</a>
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalImport">Importar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3 col-lg-3 mb-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <span class="fw-semibold d-block mb-1">Total de registros</span>
                        </div>
                        
                        <h3 class="card-title text-white mb-2"> {{ $usersCount }} </h3>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card p-3"> 
                    <div class="table-responsive">
                        <table class="table table-hover" id="tabela">
                            <thead>
                                <tr>
                                    <th class="d-none">Indicador</th>
                                    <th class="d-none">Associação</th>
                                    <th>Nome</th>
                                    <th class="d-none">D. Nascimento</th>
                                    <th>Sexo</th>
                                    <th class="d-none">Profissão</th>
                                    <th>Telefone</th>
                                    <th class="d-none">Email</th>
                                    <th class="d-none">instagram</th>
                                    <th class="d-none">facebook</th>
                                    <th class="d-none">CEP</th>
                                    <th class="d-none">Endereço</th>
                                    <th class="d-none">N°</th>
                                    <th class="d-none">Cidade</th>
                                    <th class="d-none">Estado</th>
                                    <th>Responsável</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Opções</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td class="d-none">N° {{ $user->creator->id }} - {{ $user->creator->name }}</td>
                                        <td class="d-none">N° {{ $user->company->id }} - {{ $user->company->name }}</td>
                                        <td><strong>{{ $user->name }}</strong> </td>
                                        <td class="d-none">{{ $user->birth }}</td>
                                        <td>{{ $user->sexLabel() }}</td>
                                        <td class="d-none">{{ $user->profession }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td class="d-none">{{ $user->email }}</td>
                                        <td class="d-none">{{ $user->instagram }}</td>
                                        <td class="d-none">{{ $user->facebook }}</td>
                                        <td class="d-none">{{ $user->postal_code }}</td>
                                        <td class="d-none">{{ $user->address }}</td>
                                        <td class="d-none">{{ $user->number }}</td>
                                        <td class="d-none">{{ $user->city }}</td>
                                        <td class="d-none">{{ $user->state }}</td>
                                        <td>@if(isset($user->creator->id)) <a href="{{ route('viewUser', ['id' => $user->creator->id ]) }}">{{ $user->creator->name }}</a> @else --- @endif</td>
                                        <td class="text-center"><span class="badge bg-label-success me-1">{{ $user->typeLabel() }}</span></td>
                                        <td class="text-center">
                                            <form action="{{ route('deleteUser') }}" method="POST" class="delete">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $user->id }}">
                                                <button type="submit" class="btn btn-outline-danger"> <i class="tf-icons bx bx-trash"></i> </button>
                                                <a href="{{ route('viewUser', ['id' => $user->id]) }}" class="btn btn-outline-warning"> <i class="tf-icons bx bx-edit-alt"></i> </a>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div id="divComEstilo" class="text-center">
                            {{ $users->links() }}
                        </div>
                        
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
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <input type="text" class="form-control" name="name" placeholder="Nome:"/>
                            </div>

                            <div class="col-12 col-md-6 col-lg-6 mb-3">
                                <input type="text" class="form-control" name="birth" oninput="mascaraData(this)" placeholder="Aniversário:"/>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 mb-3">
                                <input type="text" class="form-control" name="created" oninput="mascaraData(this)" placeholder="Cadastro:"/>
                            </div>

                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <input type="text" id="searchInput" class="form-control mb-2" placeholder="Pesquise o nome do responsável...">
                                <select name="id_Creator" id="selectSearch" class="form-control selectSearch">
                                    <option value="" selected>Responsável</option>
                                    @foreach ($alphas as $alpha)
                                        <option value="{{ $alpha->id }}">{{ $alpha->name }}</option>
                                    @endforeach
                                </select>
                            </div> 

                            <div class="col-12 col-md-4 col-lg-4 mb-3">
                                <select name="type" class="form-control">
                                    <option value="" selected>Tipo</option>
                                    @if (Auth::user()->type == 1) <option value="1">Master</option> @endif
                                    <option value="2">Apoiador</option>
                                    <option value="3">Eleitor</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4 col-lg-4 mb-3">
                                <select name="sex" class="form-control">
                                    <option value="" selected>Sexo</option>
                                    <option value="1">Masculino</option>
                                    <option value="2">Feminino</option>
                                    <option value="3">Outros</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4 col-lg-4 mb-3">
                                <select name="profession" class="form-control">
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
                <form action="{{ route('import-user') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalImport">Importar Registros</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <p>Faça o download do Modelo: <a download href="{{ asset('template/archive/Modelo de importação.xlsx') }}">Modelo Importação Usuário</a></p>
                            </div>   
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <input name="file" type="file" class="form-control" accept=".xlsx, .xls"/>
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

    <script>
        $(document).ready(function() {
            
            var alphas = {!! json_encode($alphas) !!};
            function filterOptions(searchQuery) {
                var filteredAlphas = alphas.filter(function(alpha) {
                    return alpha.name.toLowerCase().includes(searchQuery.toLowerCase());
                });
                populateOptions(filteredAlphas);
            }
        
            function populateOptions(options) {
                var selectElement = $('#selectSearch');
                selectElement.empty();
                selectElement.append($('<option>', {
                    value: " ",
                    text: "Responsável",
                    selected: true
                }));
                $.each(options, function(index, option) {
                    selectElement.append($('<option>', {
                        value: option.id,
                        text: option.name
                    }));
                });
            }
        
            $('#searchInput').on('input', function() {
                filterOptions($(this).val());
            });
        
            populateOptions(alphas);
        });
    </script>
@endsection