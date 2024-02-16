@extends('layout')
@section('app')
    <div class="container-xxl">
        <div class="container-p-y">
            <div class="authentication-inner">

                <div class="mb-3">
                    <div class="justify-content-center">
                        <a href="https://www.instagram.com/kleberfernandesvereador/" target="_blank">
                            <img style="width: 100%;" src="{{ asset('template/img/background/tocomkleber.png') }}">
                        </a>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        
                        <h4 class="mb-2">Bem-vindo(a)! 👋</h4>
                        <p class="mb-4">Preencha todas às informações abaixo.</p>

                        <form id="formAuthentication" class="mb-3" action="{{ route('createUserExternal') }}" method="POST">
                            @csrf
                            <div class="row">

                                <input type="hidden" name="id_lider" value="{{ $id }}">
                                <input type="hidden" name="id_grupo" value="@if(isset($grupo)) {{ $grupo }} @endif">
                                
                                <div class="col-12 col-md-3 col-lg-3 mb-3">
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
                                        <option value="---" selected>Profissão</option>
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
                                    <input type="number" class="form-control" name="cep" placeholder="CEP:" onblur="consultaCEP()"/>
                                </div>
                                <div class="col-12 col-md-3 col-lg-3 mb-3">
                                    <input type="text" class="form-control" name="logradouro" placeholder="Endereço:"/>
                                </div>
                                <div class="col-12 col-md-3 col-lg-3 mb-3">
                                    <input type="text" class="form-control" name="numero" placeholder="N°:"/>
                                </div>
                                <div class="col-12 col-md-3 col-lg-3 mb-3">
                                    <input type="text" class="form-control" name="bairro" placeholder="Bairro:"/>
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
    </div>
@endsection