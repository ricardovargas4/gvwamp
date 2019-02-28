@extends('layout.principal')

@section('conteudo')
<br>
<div class="containerFiltro">
    <div class="form-group">
        <form action="{{ route('processo.filtro') }}" method="post">
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <div class="form-group">
                <div class = "filtroProcProcesso">
                    <label for="filtroProcesso">Nome Processo</label>
                    <select name="filtroProcesso" class="form-control">
                        <option @if(isset($filtroProcesso)) value="{{{$filtroProcesso->id}}}" @else value = "" @endif>@if(isset($filtroProcesso)) {{$filtroProcesso->nome}} @else  @endif</option>
                        @if(isset($filtroProcesso))
                            <option value="" ></option>
                        @endif
                        @foreach($processosLista as $p)
                            @if(isset($filtroProcesso))
                                @if($filtroProcesso->id!=$p->id)
                                    <option value="{{$p->id}}">{{$p->nome}}</option>
                                @endif
                            @else
                                <option value="{{$p->id}}">{{$p->nome}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <div class = "filtroProcTipo">
                    <label for="filtroTipo">Tipo</label>
                    <select name="filtroTipo" class="form-control">
                        <option @if(isset($filtroTipo)) value="{{{$filtroTipo->id}}}" @else value = "" @endif>@if(isset($filtroTipo)) {{$filtroTipo->nome}} @else  @endif</option>
                        @if(isset($filtroTipo))
                            <option value="" ></option>
                        @endif
                        @foreach($tipos as $t)
                            @if(isset($filtroTipo))
                                @if($filtroTipo->id!=$t->id)
                                    <option value="{{$t->id}}">{{$t->nome}}</option>
                                @endif
                            @else
                                <option value="{{$t->id}}">{{$t->nome}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class = "filtroProcCoordenacao">
                    <label for="filtroCoordenacao">Coordenação</label>
                    <select name="filtroCoordenacao" class="form-control">
                        <option @if(isset($filtroCoordenacao)) value="{{{$filtroCoordenacao->id}}}" @else value = "" @endif>@if(isset($filtroCoordenacao)) {{$filtroCoordenacao->nome}} @else  @endif</option>
                        @if(isset($filtroCoordenacao))
                            <option value="" ></option>
                        @endif
                        @foreach($coordenacaos as $c)
                            @if(isset($filtroCoordenacao))
                                @if($filtroCoordenacao->id!=$c->id)
                                    <option value="{{$c->id}}">{{$c->nome}}</option>
                                @endif
                            @else
                                <option value="{{$c->id}}">{{$c->nome}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>


            <div class= "botaoFiltroProc">
                <button type="submit" class="btn waves-effect light-green accent-3"> Filtrar</button>
            </div>
        </form>
    </div>
</div>

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
                    @if(isset($filtro))
                        @if($filtro==0)
                            <div class="container">
                                Sem dados para o período selecionado.
                            </div>
                        @endif    
                    @else
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
                                                    <input type="hidden" name="filtroProcesso" @if(isset($filtroProcesso)) value="{{{$filtroProcesso}}}" @endif />
                                                    <input type="hidden" name="filtroTipo" @if(isset($filtroTipo)) value="{{{$filtroTipo}}}"  @endif />
                                                    <input type="hidden" name="filtroCoordenacao" @if(isset($filtroCoordenacao)) value="{{{$filtroCoordenacao}}}"  @endif />
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
                                            <a class="waves-effect waves-light btn red accent-4  modal-trigger" href="#modal2{{$p->id}}">DELETAR</a>
                                                <div id="modal2{{$p->id}}" class="modal">
                                                    <div class="modal-content">
                                                        <form action="{{ route('processo.remove') }}" method="post">
                                                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                        <input type="hidden" name="id" value="{{{ $p->id }}}" />
                                                        <input type="hidden" name="filtroProcesso" @if(isset($filtroProcesso)) value="{{{$filtroProcesso}}}" @endif />
                                                        <input type="hidden" name="filtroTipo" @if(isset($filtroTipo)) value="{{{$filtroTipo}}}"  @endif />
                                                        <input type="hidden" name="filtroCoordenacao" @if(isset($filtroCoordenacao)) value="{{{$filtroCoordenacao}}}"  @endif />
                                                        <input type="hidden" name="page" value="@if(isset($_GET['page'])) {{$_GET['page']}} @else 1 @endif" />
                                                            <div>
                                                                Realmente deseja excluir o registro?
                                                            </div>
                                                            <br>
                                                            <button type="submit" class="waves-effect waves-light btn red accent-4">Excluir</button>
                                                            <a href="#!" class="modal-action modal-close waves-effect waves-green btn">Cancelar</a>
                                                        </form>
                                                    </div>
                                                </div>

                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                
                            </tbody>
                        </table>
                        {{ $processos->links() }}
                    @endif
                </div>   
            </div>
        </div>
    </div>
</div>

@stop
