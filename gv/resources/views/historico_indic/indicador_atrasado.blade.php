@extends('layout.principal')

@section('conteudo')
<br>
<div class="containerFiltro">
    <div class="form-group">
        <form action="/indicador_atrasado/filtro" method="post">
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

<div class="card demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid cad_card">
        <div class="card-content">
            <div class="row">
                <div class="container">
                    @if (!isset($filtro))
                        <div class="container">
                            Selecione uma range de datas.
                        </div>    
                    @elseif($filtro==0)
                        <div class="container">
                            Sem dados para o período selecionado.
                        </div>
                    @else
                        <table class="bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Processo</th>
                                    <th>Data</th>
                                    <th>Usuário</th>
                                    <th>Última Data</th>
                                    <th>Data Meta</th>
                                    <th>Periodicidade</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($historicos as $h)
                                    <tr>
                                        <td scope="row">{{$h->id}}</td>
                                        <td> {{$h->processo_id_FK->nome}} </td>
                                        <td> @if(isset($h->data_informada)) {{date('d/m/Y', strtotime($h->data_informada))}} @else {{$h->data_informada}} @endif</td>
                                        <td> {{$h->user_id_FK->email}} </td>
                                        <td> @if(isset($h->ultima_data)) {{date('d/m/Y', strtotime($h->ultima_data))}} @else {{$h->ultima_data}} @endif</td>
                                        <td> @if(isset($h->data_meta)) {{date('d/m/Y', strtotime($h->data_meta))}} @else {{$h->data_meta}} @endif</td>
                                        <td> {{$h->periodiciade_id_FK->nome}} </td>
                                        <td> {{$h->status}} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $historicos->links() }}
                    @endif
                </div>   
            </div>
        </div>
    </div>
</div>

@stop
