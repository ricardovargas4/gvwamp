@extends('layout.principal')

@section('conteudo')
<br>
<div class="containerFiltro">
    <div class="form-group">
    <form action="{{ route('conclusao.filtro') }}" method="post">
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <div class="dataIniConc">       
        <label>Data Inicial</label>
        <input type="date"  name="data_inicial" class="form-control" @if(isset($data_inicial)) value="{{{$data_inicial}}}" @else value = "{{{date('Y-m-d', strtotime('-15 day', strtotime(date('Y-m-d'))))}}}" @endif placeholder="dd/mm/aaaa"/>
    </div>
    <div class="dataFimConc">       
        <label>Data Final</label>
        <input type="date" name="data_final" class="form-control" @if(isset($data_final)) value="{{{$data_final}}}" @else value = "{{{date('Y-m-d')}}}" @endif placeholder="dd/mm/aaaa"/>
    </div>

    <div class="form-group">
        <div class = "filtroConcListaProcessos">
            <label for="filtroProcesso">Processo</label>
            <select name="filtroProcesso" class="form-control">
                <option @if(isset($filtroProcesso)) value="{{{$filtroProcesso->id}}}" @else value = "" @endif>@if(isset($filtroProcesso)) {{$filtroProcesso->nome}} @else  @endif</option>
                @if(isset($filtroProcesso))
                    <option value="" ></option>
                @endif
                @foreach($processos as $p)
                    @if(isset($filtroProcesso->id))
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

    <div class= "botaoFiltroConcLista">
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
                                <form action="{{ route('conclusao.adiciona') }}" method="post">
                                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                    <input type="hidden" name="data_inicial" @if(isset($data_inicial)) value="{{{$data_inicial}}}" @else value = "{{{date('Y-m-d', strtotime('-15 day', strtotime(date('Y-m-d'))))}}}" @endif />
                                    <input type="hidden" name="data_final" @if(isset($data_final)) value="{{{$data_final}}}" @else value = "{{{date('Y-m-d')}}}" @endif />
                                    <input type="hidden" name="filtroProcesso" value="@if(isset($filtroProcesso)) {{$filtroProcesso->id}} @else  @endif" /> 
                                    <input type="hidden" name="page" value="@if(isset($_GET['page'])) {{$_GET['page']}} @else 1 @endif" /> 

                                    <div class="form-group">
                                        <label for="id_processo">Processo</label>
                                        <select name="id_processo" class="form-control">
                                            <option value="" disabled selected>Selecione o Processo</option>
                                            @foreach($processos as $p)
                                                <option value="{{$p->id}}">{{$p->nome}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Data</label>
                                        <input type="date" name="data_conciliacao" class="form-control" placeholder="dd/mm/aaaa"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Data Conciliada</label>
                                        <input type="date" name="data_conciliada" class="form-control" placeholder="dd/mm/aaaa"/>
                                    </div>
                                    <button type="submit" class="btn waves-effect light-green accent-3"> Salvar</button>
                                </form>
                            </div>
                        </li>
                    </ul>
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
                                <th>Data Conciliada</th>
                                <th>Alterar/Excluir </th>
                            </tr>
                        </thead>
                          <tbody>
                          @foreach ($conclusoes as $c)
                            <tr>
                                <td scope="row">{{$c->id}}</td>
                                <td> {{$c->processos_FK->nome}} </td>
                                <td> @if(isset($c->data_conciliacao)) {{date('d/m/Y', strtotime($c->data_conciliacao))}} @else {{$c->data_conciliacao}} @endif</td>
                                <td> @if(isset($c->data_conciliada)) {{date('d/m/Y', strtotime($c->data_conciliada))}} @else {{$c->data_conciliada}} @endif</td>
                                <td>
                                    <div class="row">
                                        <a class="waves-effect waves-light btn green accent-3  modal-trigger" href="#modal1{{$c->id}}">Editar</a>
                                        <div id="modal1{{$c->id}}" class="modal">
                                            <div class="modal-content">
                                                <form action="{{ route('conclusao.salvaAlt') }}" method="post">
                                                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                <input type="hidden" name="id" value="{{{ $c->id }}}" />
                                                
                                                <input type="hidden" name="data_inicial" value="{{{$data_inicial}}}" />
                                                <input type="hidden" name="data_final" value="{{{$data_final}}}" />
                                                <input type="hidden" name="filtroProcesso" value="@if(isset($filtroProcesso)) {{$filtroProcesso->id}} @else  @endif" /> 
                                                <input type="hidden" name="page" value="@if(isset($_GET['page'])) {{$_GET['page']}} @else 1 @endif" /> 
                                                <div class="form-group">
                                                    <label for="processo_id">Nome Processo</label>
                                                    <select name="processo_id" class="form-control">
                                                        <option value="{{{ $c->id_processo }}}" disabled selected>{{{$c->processos_FK->nome}}}</option>
                                                            @foreach($processos as $p)
                                                                <option value="{{$p->id}}">{{$p->nome}}</option>
                                                            @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Data Conciliacao</label>
                                                    <input type="date" name="data_conciliacao" class="form-control" placeholder="dd/mm/aaaa" value="{{$c->data_conciliacao}}"/>
                                                </div>
                                                <div class="form-group">
                                                    <label>Data Conciliada</label>
                                                    <input type="date" name="data_conciliada" class="form-control" placeholder="dd/mm/aaaa" value="{{$c->data_conciliada}}"/>
                                                </div>
                                                    <button type="submit" class="waves-effect waves-light btn green accent-3 ">Atualizar</button>
                                                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn">Cancelar</a>
                                                </form>
                                            </div>
                                        </div>
                                        <a class="waves-effect waves-light btn red accent-4" href="javascript:(confirm('Deletar esse registro?') ? window.location.href='{{action('ConclusaoController@remove', [$c->id,$data_inicial, $data_final,empty($filtroProcesso) ?  : $filtroProcesso->id,empty($_GET['page']) ? 1 : $_GET['page']])}}' : false)">Deletar</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $conclusoes->links() }}
                    @endif
                </div>   
            </div>
        </div>
    </div>
</div>

@stop
