@extends('App.layout')
@section('app')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            <div class="col-12">
                <div class="card p-3">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ $error }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endforeach
                     @endif
                    <form id="formAuthentication" class="mb-3" action="{{ route('createUser') }}" method="POST">
                        @csrf
                        <div class="row">
                            
                            <div class="col-12 col-md-9 col-lg-9 mb-3">
                                <input type="text" class="form-control" name="nome" placeholder="Nome:" required/>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="text" class="form-control" name="dataNasc" placeholder="Data de Nascimento:" oninput="mascaraData(this)" required/>
                            </div>

                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <select name="sexo" class="form-control">
                                    <option value="3" selected>Sexo</option>
                                    <option value="1">Masculino</option>
                                    <option value="2">Feminino</option>
                                    <option value="3">Outros</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <select name="profissao" class="form-control">
                                    <option value="0" selected>Profissão</option>
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
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <select name="tipo" class="form-control" required>
                                    <option value="3" selected>Tipo</option>
                                    @if (Auth::user()->tipo == 1) <option value="1">Master</option> @endif
                                    <option value="2">Apoiador</option>
                                    <option value="3">Eleitor</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <select name="id_lider" class="form-control" required>
                                    <option value="{{ Auth::user()->id }}" selected>Apoiador</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Email: (Opcional)"/>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="text" class="form-control" name="whatsapp" placeholder="WhatsApp:" oninput="mascaraTelefone(this)" required/>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="text" class="form-control" name="instagram" placeholder="Instagram: (Opcional)"/>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="text" class="form-control" name="facebook" placeholder="Facebook: (Opcional)"/>
                            </div>

                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="number" class="form-control" name="cep" placeholder="CEP:" onblur="consultaCEP()" required/>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="text" class="form-control" name="logradouro" placeholder="Endereço:" required/>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="text" class="form-control" name="numero" placeholder="N°:" required/>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="text" class="form-control" name="bairro" placeholder="Bairro:" required/>
                            </div>
                            <input type="hidden" class="form-control" name="cidade"/>
                            <input type="hidden" class="form-control" name="estado"/>
                            
                            <div class="col-12 col-md-12 col-lg-12 mb-3">
                                <button class="btn btn-success d-grid w-100" type="submit">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection