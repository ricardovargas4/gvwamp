@extends('layout.principal')

@section('conteudo')
<br>

<div class="card demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid cad_card">
        <div class="card-content">
            <div class="row">
                <div class="container">
                    <ul class="collapsible" data-collapsible="accordion">
                        <li>
                            <div class="collapsible-header">
                                <i class="fa fa-plus-square-o fa-sm"></i>Adicionar
                            </div>
                            <div class="collapsible-body">
                                <form action="{{ route('classificacao.adiciona') }}" method="post">
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
                                        <label for="opcao">Opção de Classificação</label>
                                        <input type="text" name="opcao" class="form-control">
                                    </div>

                                    <button type="submit" class="btn waves-effect light-green accent-3"> Salvar</button>
                                </form>
                            </div>
                        </li>
                    </ul>
                    <table class="bordered">
                        <thead>
                            <tr>
                                <th> ID </th>
                                <th> Processo </th>
                                <th> Opção </th>
                                <td> Alterar/Excluir </td>
                            </tr>
                        </thead>
                          <tbody>
                          @foreach ($classificacoes as $c)
                            <tr>
                                <td scope="row">{{$c->id}}</td>
                                <td> {{$c->id_processo_FK->nome}} </td>
                                <td> {{$c->opcao}} </td>
                                <td>
                                    <div class="row">
                                        <a class="waves-effect waves-light btn green accent-3  modal-trigger" href="#modal1{{$c->id}}">Editar</a>
                                        <div id="modal1{{$c->id}}" class="modal">
                                            <div class="modal-content">
                                                <form action="{{ route('classificacao.salvaAlt') }}" method="post">
                                                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                <input type="hidden" name="id" value="{{{ $c->id }}}" />
                                                    <!--<input type="hidden" name="_method" value="put">-->
                                                    
                                                    <div class="form-group">
                                                        <label for="id_processo">Nome Processo</label>
                                                        <select name="id_processo" class="form-control">
                                                            <option value="{{{ $c->id_processo }}}" disabled selected>{{{$c->id_processo_FK->nome}}}</option>
                                                              @foreach($processos as $p)
                                                                <option value="{{$p->id}}">{{$p->nome}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                      <label>Opção</label>
                                                      <input name="opcao"  value="{{$c->opcao}}"/>
                                                    </div>

                                                    <button type="submit" class="waves-effect waves-light btn green accent-3 ">Atualizar</button>
                                                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn">Cancelar</a>
                                                </form>
                                            </div>
                                        </div>
                                        <a class="waves-effect waves-light btn red accent-4" href="javascript:(confirm('Deletar esse registro?') ? window.location.href='{{action('ClassificacaoController@remove', $c->id)}}' : false)">Deletar</a>
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
