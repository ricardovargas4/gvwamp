@extends('layout.principal')

@section('conteudo')
<script type="text/javascript" src="\lib\jquery\dist\jquery.min.js"></script>    
<script src="/js/graficoTempo.js"></script>  


<br>
<div class="containerFiltro">
    <div class="form-group">
    <form action="/relatorio/tempo" method="post">
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <div class="dataIni">    
            <label>Data Inicial</label>
            <input type="date"  name="data_inicial" class="form-control" @if(isset($data_inicial)) value="{{{$data_inicial}}}" @else value = "{{{date('Y-m-d', strtotime('-15 day', strtotime(date('Y-m-d'))))}}}" @endif placeholder="dd/mm/aaaa"/>
        </div>
        <div class="dataFim">    
            <label>Data Final</label>
            <input type="date" name="data_final" class="form-control" @if(isset($data_final)) value="{{{$data_final}}}" @else value = "{{{date('Y-m-d')}}}" @endif placeholder="dd/mm/aaaa"/>
        </div>
        <div class= "botaoFiltro">
            <button type="submit" class="btn waves-effect light-green accent-3"> Filtrar</button>
        </div>
    </form>
    </div>
</div>

<div id = "nome_usuario"></div>
<div id = "data"> </div>
    <div class="canvas-container">
        <canvas id="myChart"></canvas>
        <button class = "btn waves-effect light-green accent-3" id='button'>Voltar</button> 
    </div>


@endsection