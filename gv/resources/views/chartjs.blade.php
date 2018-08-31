@extends('layout.principal')


@section('conteudo')

<br>
<div class="containerFiltro">
    <div class="form-group">
        <form action="{{ route('home.tempo') }}" method="post">
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <div class="dataIniChart">    
                <label>Data Inicial</label>
                <input id = "dataInicial" type="date"  name="data_inicial" class="form-control" @if(isset($data_inicial)) value="{{{$data_inicial}}}" @else value = "{{{date('Y-m-d', strtotime('-15 day', strtotime(date('Y-m-d'))))}}}" @endif placeholder="dd/mm/aaaa"/>
            </div>
            <div class="dataFimChart">    
                <label>Data Final</label>
                <input id = "dataFinal" type="date" name="data_final" class="form-control" @if(isset($data_final)) value="{{{$data_final}}}" @else value = "{{{date('Y-m-d')}}}" @endif placeholder="dd/mm/aaaa"/>
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
            <div class= "botaoFiltroChart">
                <button type="submit" class="btn waves-effect light-green accent-3"> Filtrar</button>
            </div>
        </form>
        <div class= "buttonVoltar">
                <button class = "btn waves-effect light-green accent-3" id='buttonVoltar'>Voltar</button> 
        </div>
    </div>
</div>
<div class="grafico">
    <div id = "nome_usuario"> </div>
    <div id = "data"> </div>
    <div class="chart-container" style="position: relative; height:40vh; width:75vw">
        <canvas id="myChart"></canvas>
       
    </div>
    <!--<div class="TESTE_FILTRO">
        <form action="">
            <select id = "usuariosID" name="example" multiple="multiple" class="form-control">
            </select>
        </form>
    </div>
    -->
</div>
@endsection