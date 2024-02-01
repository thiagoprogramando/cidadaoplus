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
                                <select name="tipo" class="form-control" required>
                                    <option value="3" selected>Tipo</option>
                                    <option value="1">Master</option>
                                    <option value="2">Liderança</option>
                                    <option value="3">Eleitor</option>
                                </select>
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
                                <select name="id_lider" class="form-control" required>
                                    <option value="0" selected>Liderança </option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->nome }}</option>
                                    @endforeach
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
                            <div class="col-12 col-md-9 col-lg-9 mb-3">
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
@endsection