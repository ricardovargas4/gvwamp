@extends('layout.principal')


@section('conteudo')
<script src="{{ asset('js/graficoTempo.js') }}?v={{ $AppVersionCons}}"></script>  
<br>
<div class="containerFiltro">
    <div class="form-group">
        <form action="{{ route('home.tempo') }}" method="post">
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <div class="filtroTipoRelatorio">
                <label>Tipo Relatório</label>
                <select name="tipo_relatorio" id = "tipo_relatorio" class="form-control">
                    @if ($tipo_relatorio == "1" )
                        <option value="1" selected>Processo</option>
                        <option value="2">Data</option>
                    @else
                        <option value="2" selected>Data</option>
                        <option value="1" >Processo</option>
                    @endif
                </select>
            </div>        
            <div class="dataIniChart">    
                <label>Data Inicial</label>
                <input id = "dataInicial" type="date"  name="data_inicial" class="form-control" value="{{{$data_inicial}}}" placeholder="dd/mm/aaaa"/>
            </div>
            <div class="dataFimChart">    
                <label>Data Final</label>
                <input id = "dataFinal" type="date" name="data_final" class="form-control" value="{{{$data_final}}}" placeholder="dd/mm/aaaa"/>
            </div>
            <div class="filtroCoordChart">
                    <label for="coordenacao">Coordenação</label>
                    <select id = "coordenacaoID" name="coordenacao" class="form-control">
                        <option @if(isset($coordenacao)) value="{{{$coordenacao->id}}}" @else value = "" @endif selected>@if(isset($coordenacao)) {{{$coordenacao->nome}}} @else Selecione a Coordenação @endif</option>
                        @if(isset($coordenacao))
                            <option value="">Remover Filtro</option>
                        @endif
                        @foreach($coordenacaos as $c)
                            @if( (isset($coordenacao) ? $coordenacao->id : "AA") != $c->id)
                            <option value="{{$c->id}}">{{$c->nome}}</option>
                            @endif
                        @endforeach
                    </select>
            </div>
            <div class="filtroProcChart">
                @foreach($processos as $p)
                    <p>
                        <input type="checkbox" id="processo{{$p->id}}" value="{{$p->id}}" name="filtroProc[]"  {{ (is_array($filtroProc) && in_array($p->id, $filtroProc)) ? ' checked' : '' }} > 
                        <label for="processo{{$p->id}}">{{$p->nome}}</label>
                    </p>
                @endforeach
            </div>
            <div class= "botaoFiltroChart">
                <button type="submit" class="btn waves-effect green accent-3"> Filtrar</button>
            </div>
        </form>
        <div class= "buttonVoltar">
                <button class = "btn waves-effect green accent-3" id='buttonVoltar'>Voltar</button> 
        </div>
    </div>
</div>
<form class="" role="form" method="POST" id="remove-step-form" action="{{ URL::route('home.dadosTempos')}}">
</form>
<div class="containerGraf">
    <div class="grafico">
        <div id = "nome_usuario"> </div>
        <div id = "data"> </div>
        <div class="chart-container" style="position: relative; height:40vh; width:75vw">
        <!--<div class="chart-container">-->
            <canvas id="myChart"></canvas>
        
    </div>
</div>    
<div class="testeGraf">
    <button id = "buttonMarcar" type="submit" class="btn waves-effect green accent-3"> Marcar</button>
    <button id = "buttonDesmarcar" type="submit" class="btn waves-effect green accent-3"> Desmarcar</button>
</div>
    <!--<div class="TESTE_FILTRO">
        <form action="">
            <select id = "usuariosID" name="example" multiple="multiple" class="form-control">
            </select>
        </form>
    </div>
    -->

@endsection