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
                                <th>ID Indicador </th>
                                <th>Status </th>
                                <th>Aprovador </th>
                                <th>Comentário </th>
                                <th>Excluir </th>
                            </tr>
                        </thead>
                          <tbody>
                          @foreach ($expurgos as $e)
                            <tr>
                                <td scope="row">{{$e->id}}</td>
                                <td> {{$e->id_historico_indic}} </td>
                                <td>{{$e->STATUS}}</td>
                                <td>{{$e->id_usuario_aprovador}}</td>
                                <td>{{$e->comentario}}</td>
                                <td>
                                    <div class="row">
                                        <a class="waves-effect waves-light btn red accent-4" href="javascript:(confirm('Deletar esse registro?') ? window.location.href='{{action('Expurgo_IndicadorController@remove', $e->id)}}' : false)">Deletar</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
            
                        </tbody>
                    </table>
                </div>   
            </div>
        </div>
    </div>
</div>

@stop
