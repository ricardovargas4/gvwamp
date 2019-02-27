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
                                <form action="{{ route('processo.adiciona') }}" method="post">
                                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                    <div class="form-group">
                                        <label for="nome">Nome</label>
                                        <input name="nome" class="form-control"/>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="tipo">Tipo</label>
                                        <select name="tipo" class="form-control">
                                            <option value="" disabled selected>Selecione o Processo</option>
                                            @foreach($tipos as $t)
                                                <option value="{{$t->id}}">{{$t->nome}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="periodicidade">Periodicidade</label>
                                        <select name="periodicidade" class="form-control">
                                            <option value="" disabled selected>Selecione a Periodicidade</option>
                                            @foreach($periodicidades as $p)
                                                <option value="{{$p->id}}">{{$p->nome}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="coordenacao">Coordenacao</label>
                                        <select name="coordenacao" class="form-control">
                                            <option value="" disabled selected>Selecione a Coordenacao</option>
                                            @foreach($coordenacaos as $c)
                                                <option value="{{$c->id}}">{{$c->nome}}</option>
                                            @endforeach
                                        </select>
                                    </div> 
                                    <div class="form-group">
                                        <label for="volumetria">Volumetria</label>
                                        <select name="volumetria" class="form-control">
                                            <option value=""></option>
                                            <option value="S">Sim</option>
                                        </select>
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
                                <th> Nome </th>
                                <th> Tipo </th>
                                <th> Periodicidade </th>
                                <th> Coordenação </th>
                                <th> Volumetria </th>
                                <th> Alterar </th>
                            </tr>
                        </thead>
                          <tbody>
                          @foreach ($processos as $p)
                            <tr>
                                <td scope="row">{{$p->id}}</td>
                                <td> {{$p->nome}} </td>
                                <td> {{$p->tipo_FK->nome}} </td>
                                <td> {{$p->periodicidade_FK->nome}} </td>
                                <td> {{$p->coordenacao_FK->nome}} </td>
                                <td>
                                    @if (!$p->volumetria == "" )
                                        Sim
                                    @endif
                                </td>

                                <td>
                                    <div class="row">
                                        <a class="waves-effect waves-light btn green accent-3  modal-trigger" href="#modal1{{$p->id}}">Editar</a>
                                        <div id="modal1{{$p->id}}" class="modal">
                                            <div class="modal-content">
                                                <form action="{{ route('processo.salvaAlt') }}" method="post">
                                                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                <input type="hidden" name="id" value="{{{ $p->id }}}" />
                                                <input type="hidden" name="page" value="@if(isset($_GET['page'])) {{$_GET['page']}} @else 1 @endif" />
                                                    <!--<input type="hidden" name="_method" value="put">-->
                                                    <div class="form-group">
                                                        <label for="nome">Nome</label>
                                                        <input name="nome" class="form-control" value="{{{ $p->nome }}}"/>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="tipo">Tipo</label>
                                                        <select name="tipo" class="form-control">
                                                            <option value="{{{ $p->tipoID }}}" disabled selected>{{{$p->tipo_FK->nome}}}</option>
                                                            @foreach($tipos as $t)
                                                                <option value="{{$t->id}}">{{$t->nome}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="periodicidade">Periodicidade</label>
                                                        <select name="periodicidade" class="form-control">
                                                            <option value="{{{ $p->periodicidadeID }}}" disabled selected>{{{$p->periodicidade_FK->nome}}}</option>
                                                            @foreach($periodicidades as $per)
                                                                <option value="{{$per->id}}">{{$per->nome}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="coordenacao">Coordenacao</label>
                                                        <select name="coordenacao" class="form-control">
                                                            <option value="{{{ $p->coordenacaoID }}}" disabled selected>{{{$p->coordenacao_FK->nome}}}</option>
                                                            @foreach($coordenacaos as $c)
                                                                <option value="{{$c->id}}">{{$c->nome}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="volumetria">Volumetria</label>
                                                        <select name="volumetria" class="form-control">
                                                            <option value="{{{ $p->volumetria }}}" >
                                                                @if (!$p->volumetria == "" )
                                                                    Sim
                                                                @endif
                                                            </option>
                                                            @if (!$p->volumetria == "" )
                                                                <option value=""></option>
                                                            @else
                                                                <option value="S">Sim</option>
                                                            @endif
                                                        </select>
                                                    </div>                                        
                                                    <button type="submit" class="waves-effect waves-light btn green accent-3 ">Atualizar</button>
                                                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn">Cancelar</a>
                                                </form>
                                            </div>
                                        </div>
                                        @can('checkDev')
                                            <a class="waves-effect waves-light btn red accent-4" href="javascript:(confirm('Deletar esse registro?') ? window.location.href='{{action('ProcessoController@remove', [$p->id,empty($_GET['page']) ? 1 : $_GET['page']])}}' : false)">Deletar</a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @endforeach
            
                        </tbody>
                    </table>
                    {{ $processos->links() }}
                </div>   
            </div>
        </div>
    </div>
</div>

@stop
