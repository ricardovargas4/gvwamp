<div class="navBar">

    <nav class="">
      <div class="nav-wrapper navBarMenu">
        <div class="container">
          <a class="brand-logo navBarMenu" href="{{ route('atividade.home') }}">Gestão à Vista</a>
          <ul id="nav-mobile" class="right hide-on-med-and-down">
          @if (Auth::guest())
            <li><a href="/login">Login</a></li>
          @else
            <li><a class='dropdown-button navBarMenu' href='#' data-activates='dropdown'>{{ Auth::user()->name }}<i class="material-icons right">arrow_drop_down</i></a></li>
              <ul id="dropdown" class="dropdown-content">
                <li><a href="{{ route('logout') }}">Logout</a></li>
              </ul>
          @endif
            <!-- Dropdown Trigger -->
            <ul id="dropdown1" class="dropdown-content">
              <li><a href="{{ route('atividade.filtro') }}">Atividades</a></li>
              <li><a href="{{ route('responsavel.lista') }}">Responsaveis</a></li>
              <li><a href="{{ route('historico.filtro') }}">Histórico Indicadores</a></li>
              <li><a href="{{ route('expurgo.tela') }}">Expurgo Indicador</a></li>
              <li><a href="{{ route('expurgo.filtro') }}">Expurgo Indicador Lista</a></li>
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
            <li><a class='dropdown-button navBarMenu' href='#' data-activates='dropdown2'>Gráficos<i class="material-icons right">arrow_drop_down</i></a></li>
              <ul id="dropdown2" class="dropdown-content">
                <li><a href="{{ route('home.tempo') }}">Tempos</a></li>
                <li><a href="{{ route('home.indicador') }}">Indicador</a></li>
              </ul>
            @can('checkGestor')
              <li><a class='dropdown-button navBarMenu' href='#' data-activates='dropdown3'>Relatórios<i class="material-icons right">arrow_drop_down</i></a></li>
              <ul id="dropdown3" class="dropdown-content">
                <li><a href="{{ route('resp.RelatorioResp') }}">Download Responsáveis</a></li>
                <li><a href="{{ route('indic.RelatorioIndicador') }}">Relatórios Indicador</a></li>
                <li><a href="{{ route('atividade.RelatorioTempos') }}">Relatórios Tempos</a></li>
              </ul>
            @endcan  
            <!-- Dropdown Trigger -->
            <li><a class='dropdown-button navBarMenu' href='#' data-activates='dropdown1'>Manutenção<i class="material-icons right">arrow_drop_down</i></a></li>
          </ul>
        </div>
      </div>
    </nav>
</div>