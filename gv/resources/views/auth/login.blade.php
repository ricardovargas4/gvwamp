@extends('layouts.app')

@section('content')


<main>
    <div class="wrapper">
        <div class="image">
            <img src="{{asset('img/login/arte-login-rede.png')}}">
        </div>
        <div class="login-box-wrapper">
            <div class="login-box">
                <div class="title">
                    <h1>Insira suas credenciais</h1>
                </div>
                <form id ="formLogin" role="form" method="POST" action="{{ route('loginLdap') }}">
                    {{ csrf_field() }}
                    <div class="form-group usuario">
                        <input type="text" class="form-control" id="email" placeholder="Usuário" name="email" value="{{ old('email') }}" required autofocus>
                    </div>
                    @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                    <div class="form-group senha">
                        <input type="password" class="form-control" id="password" placeholder="Senha" name="password" required>
                    </div>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                    <div class="validate">

                        <div class="checkbox">
                            <label><input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} id="checkbox">Lembrar meu usuário</label>
                        </div>

                        
                        <!--<input type="button" id="btnSubmit" class="btn" value="Entrar">-->
                        <input class="btn" id="btnSubmit" type="submit" value="Entrar"/>
                    </div>
                </form>
            </div>
            <div class="logo">
            <img src="{{asset('img/login/logo-sicredi-login.png')}}">
            </div>
        </div>
    </div>
</main>


@endsection
