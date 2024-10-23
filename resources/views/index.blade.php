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
                        
                        <h4 class="mb-2">Bem-vindo(a)! 👋</h4>
                        <p class="mb-4">Faça login para ter acesso aos beneficios da sua conta.</p>

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="formAuthentication" class="mb-3" action="{{ route('logon') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="text" class="form-control" name="email" placeholder="Seu Email:" autofocus/>
                            </div>
                            <div class="mb-3">
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="Senha"/>
                                    <span class="input-group-text cursor-pointer toggle-password"><i class="bx bx-lock-alt"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Acessar</button>
                            </div>
                        </form>

                        <p class="text-center"> 
                            <a href="{{ route('forgout') }}"> <small>Esqueceu a senha?</small> </a>
                        </p>

                        <p class="text-center">
                            Desenvolvido por <a href="#"> BSM INFORMÁTICA </a>
                        </p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordField = document.getElementById('password');
            const togglePassword = document.querySelector('.toggle-password');
            
            togglePassword.addEventListener('click', function () {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                togglePassword.querySelector('i').classList.toggle('bx-lock-alt');
                togglePassword.querySelector('i').classList.toggle('bx-lock-open-alt')
            });
        });
    </script>
@endsection