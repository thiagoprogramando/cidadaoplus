@extends('layout')
@section('app')
    <div class="container-xxl">
        <div class="container-p-y">
            <div class="authentication-inner">
                
                <div class="card">
                    <div class="card-body">
                        
                        <div class="app-brand justify-content-center">
                            <a href="#" class="app-brand-link gap-2">
                                <span class="app-brand-text demo text-body fw-bolder">CidadãoPlus</span>
                            </a>
                        </div>
                        
                        <h4 class="mb-2">Bem-vindo(a)! 👋</h4>
                        <p class="mb-4">Preencha todas às informações abaixo.</p>

                        <form id="formAuthentication" class="mb-3" action="{{ route('createUserExternal') }}" method="POST">
                            @csrf
                            <div class="row">

                                <input type="hidden" name="id_lider" value="{{ $id }}">
                                <input type="hidden" name="tipo" value="3">
                                
                                <div class="col-12 col-md-3 col-lg-3 mb-3">
                                    <input type="text" class="form-control" name="nome" placeholder="Nome:" required/>
                                </div>
                                <div class="col-12 col-md-3 col-lg-3 mb-3">
                                    <input type="text" class="form-control" name="cpf" placeholder="CPF:" oninput="mascaraCpfCnpj(this)" required/>
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
                                    <select name="civil" class="form-control">
                                        <option value="5" selected>Estado Civil</option>
                                        <option value="1">Casado(a)</option>
                                        <option value="2">Solteiro(a)</option>
                                        <option value="3">Viúvo(a)</option>
                                        <option value="4">Divórcio</option>
                                        <option value="5">Outros</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3 col-lg-3 mb-3">
                                    <select name="escolaridade" class="form-control">
                                        <option value="4" selected>Escolaridade </option>
                                        <option value="1">Fundamental</option>
                                        <option value="2">Médio</option>
                                        <option value="3">Superior</option>
                                        <option value="4">Outros</option>
                                    </select>
                                </div>
    
                                <div class="col-12 col-md-3 col-lg-3 mb-3">
                                    <input type="email" class="form-control" name="email" placeholder="Email:" required/>
                                </div>
                                <div class="col-12 col-md-3 col-lg-3 mb-3">
                                    <input type="text" class="form-control" name="whatsapp" placeholder="WhatsApp:" oninput="mascaraTelefone(this)" required/>
                                </div>
                                <div class="col-12 col-md-3 col-lg-3 mb-3">
                                    <input type="text" class="form-control" name="instagram" placeholder="Instagram"/>
                                </div>
                                <div class="col-12 col-md-3 col-lg-3 mb-3">
                                    <input type="text" class="form-control" name="facebook" placeholder="Facebook"/>
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
    
                                <div class="col-12 col-md-3 col-lg-3 mb-3">
                                    <select name="zona" class="form-control" required>
                                        <option value="5" selected>Zona </option>
                                        <option value="1">Norte</option>
                                        <option value="2">Sul</option>
                                        <option value="3">Leste</option>
                                        <option value="4">Oeste</option>
                                        <option value="5">Outras</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3 col-lg-3 mb-3">
                                    <textarea name="observacao" class="form-control" rows="1" placeholder="Observações"></textarea>
                                </div>
                                
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