@extends('layout.principal')

@section('conteudo')
<br>

<div class="card demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid cad_card">
        <div class="card-content">
            <div class="row">
                <div class="container">
                    @can('checkGestor')
                        <ul class="collapsible" data-collapsible="accordion">
                            <li>
                                <div class="collapsible-header">
                                    <i class="fa fa-plus-square-o fa-sm"></i>Adicionar
                                </div>
                                <div class="collapsible-body">
                                    <form action="/demanda/adiciona" method="post">
                                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

                                        <div class="form-group">
                                            <label for="id_processo">Nome Processo</label>
                                            <select name="id_processo" class="form-control">
                                                <option value="" disabled selected>Selecione o Processo</option>
                                                @foreach($processos as $p)
                                                    <option value="{{$p->id}}">{{$p->nome}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="id_responsavel">Nome Responsável</label>
                                            <select name="id_responsavel" class="form-control">
                                                <option value="" disabled selected>Selecione o Responsável</option>
                                                @foreach($usuarios as $u)
                                                    <option value="{{$u->id}}">{{$u->email}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="data_final">Prazo</label>
                                            <input type = "date" name="data_final" class="form-control"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="data_conclusao">Data Conclusão</label>
                                            <input type = "date" name="data_conclusao" class="form-control"/>
                                        </div>
                                        <button type="submit" class="btn waves-effect light-green accent-3"> Salvar</button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    @endcan
                    <table class="bordered">
                        <thead>
                            <tr>
                                <th>ID </th>
                                <th>Processo </th>
                                <th>Responsável </th>
                                <th>Prazo </th>
                                <th>Data Conclusão </th>
                                @can('checkGestor')
                                    <td> Alterar/Excluir </td>
                                @endcan
                            </tr>
                        </thead>
                          <tbody>
                          @foreach ($demandas as $d)
                            <tr>
                                <td scope="row">{{$d->id}}</td>
                                <td> {{$d->procNome}} </td>
                                <td> {{$d->email}} </td>
                                <td> {{$d->data_final}} </td>
                                <td> {{$d->data_conclusao}} </td>
                                @can('checkGestor')
                                    <td>
                                        <div class="row">
                                            <a class="waves-effect waves-light btn green accent-3  modal-trigger" href="#modal1{{$d->id}}">Editar</a>
                                            <div id="modal1{{$d->id}}" class="modal">
                                                <div class="modal-content">
                                                    <form action="/demanda/salvaAlt" method="post">
                                                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                    <input type="hidden" name="id" value="{{{ $d->id }}}" />
                                                        <!--<input type="hidden" name="_method" value="put">-->
                                                        
                                                        <div class="form-group">
                                                            <label for="id_processo">Nome Processo</label>
                                                            <select name="id_processo" class="form-control">
                                                                <option value="{{{ $d->procID }}}" disabled selected>{{{$d->procNome}}}</option>
                                                                @foreach($processos as $p)
                                                                    <option value="{{$p->id}}">{{$p->nome}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="id_responsavel">Responsável</label>
                                                            <select name="id_responsavel" class="form-control">
                                                                <option value="{{{ $d->userID }}}" disabled selected>{{{$d->email}}}</option>
                                                                @foreach($usuarios as $u)
                                                                    <option value="{{$u->id}}">{{$u->email}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="data_final">Nome</label>
                                                            <input type = "date" name="data_final" value="{{ $d->data_final }}" class="form-control"/>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="data_conclusao">Data Conclusão</label>
                                                            <input type = "date" name="data_conclusao" value="{{ $d->data_conclusao }}" class="form-control"/>
                                                        </div>

                                                        <button type="submit" class="waves-effect waves-light btn green accent-3 ">Atualizar</button>
                                                        <a href="#!" class="modal-action modal-close waves-effect waves-green btn">Cancelar</a>
                                                    </form>
                                                </div>
                                            </div>
                                            <a class="waves-effect waves-light btn red accent-4" href="javascript:(confirm('Deletar esse registro?') ? window.location.href='{{action('DemandaController@remove', $d->id)}}' : false)">Deletar</a>
                                        </div>
                                    </td>
                                @endcan
                            </tr>
                            @endforeach
            
                        </tbody>
                    </table>
                    {{ $demandas->links() }}
                </div>   
            </div>
        </div>
    </div>
</div>

@stop
