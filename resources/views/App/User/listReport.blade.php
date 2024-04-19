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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6 col-md-3 col-lg-2 mb-4">
                    <div class="card bg-info text-white text-center">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-center">
                                <span class="fw-semibold d-block mb-1">Total de registros</span>
                            </div>
                            
                            <h3 class="card-title text-white mb-2"> {{ $eleitores->count() + $apoiadores->count() + $coordenadores->count() + $master->count() }} </h3>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2 mb-4">
                    <div class="card bg-warning text-white text-center">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-center">
                                <span class="fw-semibold d-block mb-1">Usuários</span>
                            </div>
                            
                            <h3 class="card-title text-white mb-2"> {{ $eleitores->count() }} </h3>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2 mb-4">
                    <div class="card bg-primary text-white text-center">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-center">
                                <span class="fw-semibold d-block mb-1">Apoiadores</span>
                            </div>
                            
                            <h3 class="card-title text-white mb-2"> {{ $apoiadores->count() }} </h3>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2 mb-4">
                    <div class="card bg-dark text-white text-center">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-center">
                                <span class="fw-semibold d-block mb-1">Coordenadores</span>
                            </div>
                            <h3 class="card-title text-white mb-2"> {{ $coordenadores->count() }} </h3>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2 mb-4">
                    <div class="card bg-danger text-white text-center">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-center">
                                <span class="fw-semibold d-block mb-1">Masters</span>
                            </div>
                            <h3 class="card-title text-white mb-2"> {{ $master->count() }} </h3>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2 mb-4">
                    <div class="card bg-secondary text-white text-center">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-center">
                                <span class="fw-semibold d-block mb-1">Rede</span>
                            </div>
                            <h3 class="card-title text-white mb-2"> {{ $rede }} </h3>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="modalFilter" aria-labelledby="modalFilter" tabindex="-1" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('listReport') }}" method="GET">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFilter">Filtrar Registros</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-12 mb-2">
                                <input type="text" id="searchInput" class="form-control mb-2" placeholder="Pesquisar...">
                                <select name="id_lider" id="selectSearch" class="form-control selectSearch">
                                    <option value="" selected>Coordenador</option>
                                    @foreach ($alphas as $alpha)
                                        <option value="{{ $alpha->id }}">{{ $alpha->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <select name="bairro" class="form-control">
                                    <option value="" selected>Bairros</option>
                                    <option value="Cidade da Esperança">Cidade da Esperança</option>
                                    <option value="Cidade Nova">Cidade Nova</option>
                                    <option value="Guarapes">Guarapes</option>
                                    <option value="Nossa Senhora de Nazaré">Nossa Senhora de Nazaré</option>
                                    <option value="Bom Pastor">Bom Pastor</option>
                                    <option value="Planalto">Planalto</option>
                                    <option value="Felipe Camarão">Felipe Camarão</option>
                                    <option value="Nordeste">Nordeste</option>
                                    <option value="Dix-Sept Rosado">Dix-Sept Rosado</option>
                                    <option value="Quintas">Quintas</option>
                                    <option value="Igapó">Igapó</option>
                                    <option value="Lagoa Azul">Lagoa Azul</option>
                                    <option value="Nossa Senhora da Apresentação">Nossa Senhora da Apresentação</option>
                                    <option value="Pajuçara">Pajuçara</option>
                                    <option value="Potengi">Potengi</option>
                                    <option value="Redinha">Redinha</option>
                                    <option value="Salinas">Salinas</option>
                                    <option value="Alecrim">Alecrim</option>
                                    <option value="Areia Preta">Areia Preta</option>
                                    <option value="Barro Vermelho">Barro Vermelho</option>
                                    <option value="Cidade Alta">Cidade Alta</option>
                                    <option value="Lagoa Seca">Lagoa Seca</option>
                                    <option value="Mãe Luiza">Mãe Luiza</option>
                                    <option value="Petrópolis">Petrópolis</option>
                                    <option value="Praia do Meio">Praia do Meio</option>
                                    <option value="Ribeira">Ribeira</option>
                                    <option value="Rocas">Rocas</option>
                                    <option value="Santos Reis">Santos Reis</option>
                                    <option value="Tirol">Tirol</option>
                                    <option value="Candelária">Candelária</option>
                                    <option value="Capim Macio">Capim Macio</option>
                                    <option value="Lagoa Nova">Lagoa Nova</option>
                                    <option value="Neópolis">Neópolis</option>
                                    <option value="Nova Descoberta">Nova Descoberta</option>
                                    <option value="Pitimbu">Pitimbu</option>
                                    <option value="Ponta Negra">Ponta Negra</option>
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

    <script>
        $(document).ready(function() {
            
            var alphas = {!! json_encode($alphas) !!};
            function filterOptions(searchQuery) {
                var filteredAlphas = alphas.filter(function(alpha) {
                    return alpha.nome.toLowerCase().includes(searchQuery.toLowerCase());
                });
                populateOptions(filteredAlphas);
            }
        
            function populateOptions(options) {
                var selectElement = $('#selectSearch');
                selectElement.empty();
                selectElement.append($('<option>', {
                    value: " ",
                    text: "Apoiador",
                    selected: true
                }));
                $.each(options, function(index, option) {
                    selectElement.append($('<option>', {
                        value: option.id,
                        text: option.nome
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