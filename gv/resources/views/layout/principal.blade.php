<!DOCTYPE html>
<html>
<head>
    <?php $AppVersion = "1.0.0.8"; ?>

    <link href="{{ asset('lib/materialize/dist/css/materialize.css') }} " rel="stylesheet">
    <link href="{{ asset('css/app.css') }} " rel="stylesheet">
    <link href="{{ asset('css/font-awesome.css') }} " rel="stylesheet">
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--<link href="/css/style.css?v=<?php echo $AppVersion; ?>" rel="stylesheet">-->
    <link href="{{ asset('css/style.css') }}?v={{$AppVersion}}" rel="stylesheet">

    <title>Gestão à Vista</title>
</head>
<body>
  <div class="navBar">

    <nav>
      <div class="nav-wrapper navBarMenu">
        <div class="container">
          <a class="brand-logo" href="{{ route('atividade.home') }}">Gestão à Vista</a>
          <ul id="nav-mobile" class="right hide-on-med-and-down">
          @if (Auth::guest())
            <li><a href="/login">Login</a></li>
          @else
            <li>{{ Auth::user()->name }} </li>
            <li><a href="{{ route('logout') }}">Logout</a></li>
          @endif
            <!-- Dropdown Trigger -->
            <ul id="dropdown1" class="dropdown-content">
              <li><a href="{{ route('atividade.filtro') }}">Atividades</a></li>
              <li><a href="{{ route('responsavel.lista') }}">Responsaveis</a></li>
              <li><a href="{{ route('historico.filtro') }}">Histórico Indicadores</a></li>
              <li><a href="{{ route('expurgo.tela') }}">Expurgo Indicador</a></li>
              <li><a href="{{ route('expurgo.lista') }}">Expurgo Indicador Lista</a></li>
              <li><a href="{{ route('demanda.lista') }}">Demandas</a></li>
              @can('checkGestor')
                <li><a href="{{ route('processo.lista') }}">Processos</a></li>
                <li><a href="{{ route('tipo.lista') }}">Tipos</a></li>
                <li><a href="{{ route('periodicidade.lista') }}">Periodicidades</a></li>
                <li><a href="{{ route('coordenacao.lista') }}">Coordenações</a></li>
                <li><a href="{{ route('classificacao.lista') }}">Classificações</a></li>
                <li><a href="{{ route('usuario.lista') }}">Usuários</a></li>
              @endcan
              <!--<li class="divider"></li>-->
            </ul>

            <!-- Dropdown Trigger -->
            <li><a class='dropdown-button' href='#' data-activates='dropdown1'>Manutenção<i class="material-icons right">arrow_drop_down</i></a></li>
          </ul>
        </div>
      </div>
    </nav>
</div>
  <div id="container">
    @yield('conteudo')
  </div>

</body>
<!--
<div class = "footerteste">
      <footer class="page-footer light-green <accent-2></accent-2>" >
        <div class="container">
          <div class="row">
            <div class="col l6 s12">
              <h5 class="white-text">Confederação Sicredi</h5>
              <p class="grey-text text-lighten-4">Gestão à Vista.</p>
              <p>
                <a href="#"><i class="white-text mdi mdi-facebook mdi-24px"></i></a>
                <a href="#"><i class="white-text mdi mdi-twitter mdi-24px"></i></a>
                <a href="#"><i class="white-text mdi mdi-youtube-play mdi-24px"></i></a>
              </p>
            </div>
            <div class="col l4 offset-l2 s12">
              <h5 class="white-text">Links</h5>
              <ul>
                <li><a class="grey-text text-lighten-3" href="{{ url('/home') }}">Home</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="footer-copyright">
          <div class="container">
          © 2017 Copyright Text
          <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
          </div>
        </div>
      </div>
    </footer>
  </div>
-->

    <script src="{{asset('js/Chart.js')}}"></script>
    <script src="{{asset('js/Chart.min.js')}}"></script> 
    <script src="{{asset('lib\jquery\dist\jquery.min.js')}}"></script>
    <script src="{{asset('lib\materialize\dist\js\materialize.min.js')}}"></script>
    <script src="{{asset('js/init.js')}}"></script>
    <script src="{{ asset('js/validacao.js') }}?v={{$AppVersion}}"></script>
    <script src="{{ asset('js/graficoTempo.js') }}?v={{$AppVersion}}"></script>

</html>
