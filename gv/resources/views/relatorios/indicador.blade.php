@extends('layout.principal')

@section('conteudo')
<br>
<div class="containerDown">
    <div class="TituloRelat">
        Indicador Mensal
    </div>
    <div class="form-group">
    <form action="{{ route('indic.RelatorioIndicadorMensal') }}" method="post">
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <div class="dataIniIndic">    
            <label>Data Inicial</label>
            <input type="date"  name="data_inicial" class="form-control" @if(isset($data_inicial)) value="{{{$data_inicial}}}" @else value = "{{{date('Y-m-d', strtotime('-15 day', strtotime(date('Y-m-d'))))}}}" @endif placeholder="dd/mm/aaaa"/>
        </div>
        <div class="dataFimIndic">    
            <label>Data Final</label>
            <input type="date" name="data_final" class="form-control" @if(isset($data_final)) value="{{{$data_final}}}" @else value = "{{{date('Y-m-d')}}}" @endif placeholder="dd/mm/aaaa"/>
        </div>
        <div class= "botaoDownIndic">
            <button type="submit" class="btn waves-effect light-green accent-3"> Download</button>
        </div>
    </form>
    </div>
</div>

<div class="containerDown">
    <div class="TituloRelat">
        Indicador Mensal por Processo
    </div>
    <div class="form-group">
    <form action="{{ route('indic.RelatorioIndicadorMensalProc') }}" method="post">
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <div class="dataIniIndic">    
            <label>Data Inicial</label>
            <input type="date"  name="data_inicial" class="form-control" @if(isset($data_inicial)) value="{{{$data_inicial}}}" @else value = "{{{date('Y-m-d', strtotime('-15 day', strtotime(date('Y-m-d'))))}}}" @endif placeholder="dd/mm/aaaa"/>
        </div>
        <div class="dataFimIndic">    
            <label>Data Final</label>
            <input type="date" name="data_final" class="form-control" @if(isset($data_final)) value="{{{$data_final}}}" @else value = "{{{date('Y-m-d')}}}" @endif placeholder="dd/mm/aaaa"/>
        </div>
        <div class= "botaoDownIndic">
            <button type="submit" class="btn waves-effect light-green accent-3"> Download</button>
        </div>
    </form>
    </div>
</div>

<div class="containerDown">
    <div class="TituloRelat">
        Indicador Analítico
    </div>
    <div class="form-group">
    <form action="{{ route('indic.RelatorioIndicadorAnalitico') }}" method="post">
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <div class="dataIniIndic">    
            <label>Data Inicial</label>
            <input type="date"  name="data_inicial" class="form-control" @if(isset($data_inicial)) value="{{{$data_inicial}}}" @else value = "{{{date('Y-m-d', strtotime('-15 day', strtotime(date('Y-m-d'))))}}}" @endif placeholder="dd/mm/aaaa"/>
        </div>
        <div class="dataFimIndic">    
            <label>Data Final</label>
            <input type="date" name="data_final" class="form-control" @if(isset($data_final)) value="{{{$data_final}}}" @else value = "{{{date('Y-m-d')}}}" @endif placeholder="dd/mm/aaaa"/>
        </div>
        <div class= "botaoDownIndic">
            <button type="submit" class="btn waves-effect light-green accent-3"> Download</button>
        </div>
    </form>
    </div>
</div>




@stop
