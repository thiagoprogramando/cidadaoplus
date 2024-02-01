@extends('App.layout')
@section('app')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            <div class="col-12">
                <div class="card p-3"> 

                    <div class="alert alert-info alert-dismissible" role="alert">
                        Altere apenas os dados necessários!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <form id="formAuthentication" class="mb-3" action="{{ route('updateUser') }}" method="POST">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="id" value="{{ $user->id }}">

                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="text" class="form-control" name="nome" placeholder="Nome:" value="{{ $user->nome }}"/>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="text" class="form-control" name="cpf" placeholder="CPF:" value="{{ $user->cpf }}" oninput="mascaraCpfCnpj(this)"/>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="text" class="form-control" name="dataNasc" placeholder="Data de Nascimento:" value="{{ $user->dataNasc }}" oninput="mascaraData(this)"/>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <select name="tipo" class="form-control">
                                    <option value="{{ $user->tipo }}" selected>Tipo</option>
                                    <option value="1">Master</option>
                                    <option value="2">Liderança</option>
                                    <option value="3">Eleitor</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <select name="sexo" class="form-control">
                                    <option value="{{ $user->sexo }}" selected>Sexo</option>
                                    <option value="1">Masculino</option>
                                    <option value="2">Feminino</option>
                                    <option value="3">Outros</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <select name="civil" class="form-control">
                                    <option value="{{ $user->civil }}" selected>Estado Civil</option>
                                    <option value="1">Casado(a)</option>
                                    <option value="2">Solteiro(a)</option>
                                    <option value="3">Viúvo(a)</option>
                                    <option value="4">Divórcio</option>
                                    <option value="5">Outros</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <select name="escolaridade" class="form-control">
                                    <option value="{{ $user->escolaridade }}" selected>Escolaridade </option>
                                    <option value="1">Fundamental</option>
                                    <option value="2">Médio</option>
                                    <option value="3">Superior</option>
                                    <option value="4">Outros</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <select name="id_lider" class="form-control">
                                    <option value="{{ $user->id_lider }}" selected>Liderança </option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="email" class="form-control" name="email" value="{{ $user->email }}" placeholder="Email:"/>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="text" class="form-control" name="whatsapp" value="{{ $user->whatsapp }}" placeholder="WhatsApp:" oninput="mascaraTelefone(this)"/>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="text" class="form-control" name="instagram" value="{{ $user->instagram }}" placeholder="Instagram"/>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="text" class="form-control" name="facebook" value="{{ $user->facebook }}" placeholder="Facebook"/>
                            </div>

                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="number" class="form-control" name="cep" value="{{ $user->cep }}" placeholder="CEP:" onblur="consultaCEP()"/>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="text" class="form-control" name="logradouro" value="{{ $user->logradouro }}" placeholder="Endereço:"/>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="text" class="form-control" name="numero" value="{{ $user->numero }}" placeholder="N°:"/>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <input type="text" class="form-control" name="bairro" value="{{ $user->bairro }}" placeholder="Bairro:"/>
                            </div>
                            <input type="hidden" class="form-control" name="cidade" value="{{ $user->cidade }}"/>
                            <input type="hidden" class="form-control" name="estado" value="{{ $user->estado }}"/>

                            <div class="col-12 col-md-3 col-lg-3 mb-3">
                                <select name="zona" class="form-control">
                                    <option value="{{ $user->zona }}" selected>Zona </option>
                                    <option value="1">Norte</option>
                                    <option value="2">Sul</option>
                                    <option value="3">Leste</option>
                                    <option value="4">Oeste</option>
                                    <option value="5">Outras</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-9 col-lg-9 mb-3">
                                <textarea name="observacao" class="form-control" rows="1" placeholder="Observações">{{ $user->observacao }}</textarea>
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
@endsection