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
                <div class="col-6 col-md-3 col-lg-4 mb-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <span class="fw-semibold d-block mb-1">Total de registros</span>
                            </div>
                            
                            <h3 class="card-title text-white mb-2"> {{ $eleitores->count() + $apoiadores->count() + $coordenadores->count() + $master->count() }} </h3>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-4 mb-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <span class="fw-semibold d-block mb-1">Eleitores</span>
                            </div>
                            
                            <h3 class="card-title text-white mb-2"> {{ $eleitores->count() }} </h3>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-4 mb-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <span class="fw-semibold d-block mb-1">Apoiadores</span>
                            </div>
                            
                            <h3 class="card-title text-white mb-2"> {{ $apoiadores->count() }} </h3>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-4 mb-4">
                    <div class="card bg-dark text-white">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <span class="fw-semibold d-block mb-1">Coordenadores</span>
                            </div>
                            <h3 class="card-title text-white mb-2"> {{ $coordenadores->count() }} </h3>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-4 mb-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <span class="fw-semibold d-block mb-1">Masters</span>
                            </div>
                            <h3 class="card-title text-white mb-2"> {{ $master->count() }} </h3>
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
                    @csrf
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
                                    @foreach ($coordenadores as $coordenador)
                                        <option value="{{ $coordenador->id }}">{{ $coordenador->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 mb-2">
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