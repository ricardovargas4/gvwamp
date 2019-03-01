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
                                <th>Status </th>
                                <th>Solicitante </th>
                                <th>Aprovador </th>
                                <th>Comentário </th>
                                @if($nivel==1)
                                    <th>Aprovar</th><th>Reprovar</th><th>Excluir</th>
                                @elseif($nivel==2)
                                    <th>Aprovar</th><th>Reprovar</th>
                                @endif 
                            </tr>
                        </thead>
                          <tbody>
                          @foreach ($expurgos as $e)
                            <tr>
                                <td scope="row">{{$e->id}}</td>
                                <td> {{$e->id_historico_indic}}</td>
                                <td> @if(isset($e->id_historico_indic_FK->data_informada)) {{date('d/m/Y', strtotime($e->id_historico_indic_FK->data_informada))}} @else {{$e->id_historico_indic_FK->data_informada}} @endif</td>
                                <td> {{$e->id_historico_indic_FK->processo_id_FK->nome}} </td>
                                @if($e->STATUS == 1 ) 
                                    <td>Pendente</td>
                                @elseif($e->STATUS == 2 ) 
                                    <td>Aprovado</td> 
                                @elseif($e->STATUS == 3 ) 
                                    <td>Reprovado</td>
                                @else
                                    <td></td>
                                @endif
                                <td>{{$e->id_usuario_solicitante_FK->email}}</td>
                                <td>{{$e->id_usuario_aprovador_FK->email}}</td>
                                <td>@php echo $e['comentario'] @endphp</td>
                                @if($nivel<=2)
                                    <td>
                                        <div class="row">
                                            <a class="waves-effect waves-light btn green accent-3  modal-trigger" href="#modal1{{$e->id}}">Aprovar</a>
                                            <div id="modal1{{$e->id}}" class="modal">
                                                <div class="modal-content">
                                                    <form action="{{ route('expurgo.aprovar') }}" method="post">
                                                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                    <input type="hidden" name="id" value="{{{ $e->id }}}" />
                                                    <input type="hidden" name="id_historico_indic" value="{{{ $e->id_historico_indic }}}" />
                                                    <div class="form-group">
                                                        <label for="justificativa">Justificativa</label>
                                                        <textarea class="materialize-textarea" name="justificativa"></textarea>
                                                    </div>
                                                        <button type="submit" class="waves-effect waves-light btn green accent-3 ">Aprovar</button>
                                                    </form>
                                                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn">Cancelar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                @endif 
                                @if($nivel<=2)
                                    <td>
                                        <div class="row">
                                            <a class="waves-effect waves-light btn red accent-4  modal-trigger" href="#modalreprovar{{$e->id}}">Reprovar</a>
                                            <div id="modalreprovar{{$e->id}}" class="modal">
                                                <div class="modal-content">
                                                    <form action="{{ route('expurgo.reprovar') }}" method="post">
                                                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                    <input type="hidden" name="id" value="{{{ $e->id }}}" />
                                                    <div class="form-group">
                                                        <label for="justificativa">Justificativa</label>
                                                        <textarea class="materialize-textarea" name="justificativa"></textarea>
                                                    </div>
                                                        <button type="submit" class="waves-effect waves-light btn red accent-4">Reprovar</button>
                                                    </form>
                                                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn">Cancelar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                @endif 
                                @if($nivel<=1)
                                    <td>
                                        <div class="row">
                                            <a class="waves-effect waves-light btn red accent-4" href="javascript:(confirm('Deletar esse registro?') ? window.location.href='{{action('Expurgo_IndicadorController@remove', $e->id)}}' : false)">Deletar</a>
                                        </div>
                                    </td>
                                @endif
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
