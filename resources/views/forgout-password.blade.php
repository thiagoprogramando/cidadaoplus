@extends('layout')
@section('app')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                
                <div class="card">
                    <div class="card-body">
                        
                        <div class="app-brand justify-content-center">
                            <a href="{{ route('login') }}" class="app-brand-link gap-2">
                                <span class="app-brand-text demo text-body fw-bolder">#Tô com Kleber</span>
                            </a>
                        </div>
                        
                        @if($code)
                            <form class="mb-3" action="{{ route('recoverPassword') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="code" placeholder="Código"/>
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="password" placeholder="Nova senha:" autofocus/>
                                </div>
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="passwordRepeat" placeholder="Confirme a senha:"/>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary d-grid w-100" type="submit">Atualizar</button>
                                </div>
                            </form>
                        @else
                            <h4 class="mb-2">Esqueceu algo? 🔒</h4>
                            <p class="mb-4">Não tem problema, vamos recuperar!</p>

                            <form class="mb-3" action="{{ route('forgout-password') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="whatsapp" placeholder="WhatsApp:" oninput="mascaraTelefone(this)" autofocus/>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary d-grid w-100" type="submit">Recuperar</button>
                                </div>
                            </form>
                        @endif

                        <p class="text-center">
                            <span>Já possui conta?</span>
                            <a href="{{ route('login') }}"> <span>Faça login</span> </a>
                        </p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection