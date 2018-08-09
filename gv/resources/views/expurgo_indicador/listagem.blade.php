@extends('layout.principal')

@section('conteudo')
<br>

<div class="card demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid cad_card">
        <div class="card-content">
            <div class="row">
                <div class="container">
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
                </div>   
            </div>
        </div>
    </div>
</div>

@stop
