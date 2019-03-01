@extends('layout.principal')

@section('conteudo')
<br>
<div class="containerFiltro">
        <div class="form-group">
        <form action="{{ route('expurgo.filtro') }}" method="post">
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <div class="dataIni">       
            <label>Data Inicial</label>
            <input type="date"  name="data_inicial" class="form-control" @if(isset($data_inicial)) value="{{{$data_inicial}}}" @else value = "{{{date('Y-m-d', strtotime('-15 day', strtotime(date('Y-m-d'))))}}}" @endif placeholder="dd/mm/aaaa"/>
        </div>
        <div class="dataFim">       
            <label>Data Final</label>
            <input type="date" name="data_final" class="form-control" @if(isset($data_final)) value="{{{$data_final}}}" @else value = "{{{date('Y-m-d')}}}" @endif placeholder="dd/mm/aaaa"/>
        </div>
        @can('checkGestor')
            <div class="form-group">
                <div class = "filtroExpListaUsuario">
                    <label for="filtroUsuario">Usuários</label>
                    <select name="filtroUsuario" class="form-control">
                        <option @if(isset($filtroUsuario)) value="{{{$filtroUsuario->id}}}" @else value = "" @endif>@if(isset($filtroUsuario)) {{$filtroUsuario->email}} @else  @endif</option>
                        @if(isset($filtroUsuario))
                            <option value="" ></option>
                        @endif
                        @foreach($users as $u)
                            @if(isset($filtroUsuario->id))
                                @if($filtroUsuario->id!=$u->id)
                                    <option value="{{$u->id}}">{{$u->email}}</option>
                                @endif
                            @else
                                <option value="{{$u->id}}">{{$u->email}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        @endcan

        <div class= "botaoFiltroExpLista">
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
                            <th>ID </th>
                            <th>ID Indicador</th>
                            <th>Data</th>
                            <th>Processo </th>
                            <th>Solicitante </th>
                            <th>Status </th>
                            <th>Aprovador </th>
                            <th>Comentário </th>
                            <th>Justificativa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expurgos as $e)
                        <tr>
                            <td scope="row">{{$e->id}}</td>
                            <td> {{$e->id_historico_indic}}</td>
                            <td> @if(isset($e->id_historico_indic_FK->data_informada)) {{date('d/m/Y', strtotime($e->id_historico_indic_FK->data_informada))}} @else {{$e->id_historico_indic_FK->data_informada}} @endif</td>
                            <td> {{$e->id_historico_indic_FK->processo_id_FK->nome}} </td>
                            <td> {{$e->id_usuario_solicitante_FK->email}} </td>
                            @if($e->STATUS == 1 ) 
                                <td>Pendente</td>
                            @elseif($e->STATUS == 2 ) 
                                <td>Aprovado</td> 
                            @elseif($e->STATUS == 3 ) 
                                <td>Reprovado</td>
                            @else
                                <td></td>
                            @endif
                            
                            <td>{{$e->id_usuario_aprovador_FK->email}}</td>
                            <td>@php echo $e['comentario'] @endphp</td>
                            <td>@php echo $e['justificativa'] @endphp</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $expurgos->links() }}
                @endif
            </div>
        </div>
    </div>
</div>

@stop
