@extends('layout.principal')

@section('conteudo')
<br>

<div class="containerFiltro">
    <div class="form-group">
        <form action="{{ route('responsavel.filtro') }}" method="post">
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <div class="form-group">
                <div class = "filtroId_processo">
                    <label for="filtroId_processo">Nome Processo</label>
                    <select name="filtroId_processo" class="form-control">
                        <option @if(isset($filtroId_processo)) value="{{{$filtroId_processo->id}}}" @else value = "" @endif>@if(isset($filtroId_processo)) {{$filtroId_processo->nome}} @else  @endif</option>
                        @if(isset($filtroId_processo))
                            <option value="" ></option>
                        @endif
                        @foreach($processos as $p)
                            @if(isset($filtroId_processo))
                                @if($filtroId_processo->id!=$p->id)
                                    <option value="{{$p->id}}">{{$p->nome}}</option>
                                @endif
                            @else
                                <option value="{{$p->id}}">{{$p->nome}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            @can('checkGestor')
                <div class="form-group">
                    <div class = "filtroUsuario">
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
            <div class= "botaoFiltroResp">
                <button type="submit" class="btn waves-effect light-green accent-3"> Filtrar</button>
            </div>
        </form>
    </div>
</div>
<div class="containerCard">
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
                                        <form action="{{ route('responsavel.adiciona') }}" method="post">
                                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                            <input type="hidden" name="filtroId_processo" @if(isset($filtroId_processo)) value="{{{$filtroId_processo}}}" @endif />
                                            <input type="hidden" name="filtroUsuario" @if(isset($filtroUsuario)) value="{{{$filtroUsuario}}}"  @endif />
                                            <input type="hidden" name="page" value="@if(isset($_GET['page'])) {{$_GET['page']}} @else 1 @endif" /> 
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
                                                <label for="usuario">Usuários</label>
                                                <select name="usuario" class="form-control">
                                                    <option value="" disabled selected>Selecione o usuário</option>
                                                    @foreach($users as $u)
                                                        <option value="{{$u->id}}">{{$u->email}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <button type="submit" class="btn waves-effect light-green accent-3"> Salvar</button>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        @endcan
                        @if(isset($teste) && $teste =="ERRO")
                            <div class="container">
                                O processo é de conciliação e já cadastrado para outro usuário.<BR>
                                Cadastro não realizado.
                            </div>       
                        @elseif($filtro==0)
                            <div class="container">
                                Sem dados para os filtros selecionados.
                            </div>
                        @else
                        
                            <table class="bordered">
                                <thead>
                                    <tr>
                                        <th> ID </th>
                                        <th> Processo </th>
                                        <th> Usuário </th>
                                        @if($nivel>2)
                                            <th> Direcionar </th>
                                        @endif
                                        @can('checkGestor')
                                            <th> Alterar/Excluir </th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($resp as $r)
                                    <tr>
                                        <td scope="row">{{$r->id}}</td>
                                        <td> {{$r->procNome}} </td>
                                        <td> {{$r->email}} </td>
                                        @if($nivel>2)
                                            <td>
                                                <div class="row">
                                                    <a class="waves-effect waves-light btn green accent-3  modal-trigger" href="#modal3{{$r->id}}">Alterar</a>
                                                    <div id="modal3{{$r->id}}" class="modal">
                                                        <div class="modal-content">
                                                            <form action="{{ route('responsavel.direcionar') }}" method="post">
                                                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                            <input type="hidden" name="id" value="{{{ $r->id }}}" />
                                                            <input type="hidden" name="filtroId_processo" @if(isset($filtroId_processo)) value="{{{$filtroId_processo}}}" @endif />
                                                            <input type="hidden" name="filtroUsuario" @if(isset($filtroUsuario)) value="{{{$filtroUsuario}}}"  @endif />
                                                            <input type="hidden" name="page" value="@if(isset($_GET['page'])) {{$_GET['page']}} @else 1 @endif" /> 
                                                                <!--<input type="hidden" name="_method" value="put">-->
                                                                <div class="form-group">
                                                                    <label for="id_processo">Nome Processo</label>
                                                                    <select name="id_processo" class="form-control">
                                                                        <option value="{{{ $r->id_processo }}}" selected>{{{$r->procNome}}}</option>
                                                                     </select>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="usuario">Usuários</label>
                                                                    <select name="usuario" class="form-control">
                                                                        <option value="{{{ $r->usuario }}}" disabled selected>{{{$r->email}}}</option>
                                                                        @foreach($users as $u)
                                                                            <option value="{{$u->id}}">{{$u->email}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                                            
                                                                <button type="submit" class="waves-effect waves-light btn green accent-3 ">Atualizar</button>
                                                                <a href="#!" class="modal-action modal-close waves-effect waves-green btn">Cancelar</a>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                        @can('checkGestor')
                                            <td>
                                                <div class="row">
                                                    <a class="waves-effect waves-light btn green accent-3  modal-trigger" href="#modal1{{$r->id}}">Editar</a>
                                                    <div id="modal1{{$r->id}}" class="modal">
                                                        <div class="modal-content">
                                                            <form action="{{ route('responsavel.salvaAlt') }}" method="post">
                                                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                            <input type="hidden" name="id" value="{{{ $r->id }}}" />
                                                            <input type="hidden" name="filtroId_processo" @if(isset($filtroId_processo)) value="{{{$filtroId_processo}}}" @endif />
                                                            <input type="hidden" name="filtroUsuario" @if(isset($filtroUsuario)) value="{{{$filtroUsuario}}}"  @endif />
                                                            <input type="hidden" name="page" value="@if(isset($_GET['page'])) {{$_GET['page']}} @else 1 @endif" /> 
                                                                <!--<input type="hidden" name="_method" value="put">-->
                                                                <div class="form-group">
                                                                    <label for="id_processo">Nome Processo</label>
                                                                    <select name="id_processo" class="form-control">
                                                                        <option value="{{{ $r->id_processo }}}" disabled selected>{{{$r->procNome}}}</option>
                                                                        @foreach($processos as $p)
                                                                            <option value="{{$p->id}}">{{$p->nome}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="usuario">Usuários</label>
                                                                    <select name="usuario" class="form-control">
                                                                        <option value="{{{ $r->usuario }}}" disabled selected>{{{$r->email}}}</option>
                                                                        @foreach($users as $u)
                                                                            <option value="{{$u->id}}">{{$u->email}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                                            
                                                                <button type="submit" class="waves-effect waves-light btn green accent-3 ">Atualizar</button>
                                                                <a href="#!" class="modal-action modal-close waves-effect waves-green btn">Cancelar</a>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <a class="waves-effect waves-light btn red accent-4  modal-trigger" href="#modal2{{$r->id}}">DELETAR</a>
                                                    <div id="modal2{{$r->id}}" class="modal">
                                                        <div class="modal-content">
                                                            <form action="{{ route('responsavel.remove') }}" method="post">
                                                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                            <input type="hidden" name="id" value="{{{ $r->id }}}" />
                                                            <input type="hidden" name="filtroId_processo" @if(isset($filtroId_processo)) value="{{{$filtroId_processo}}}" @endif />
                                                            <input type="hidden" name="filtroUsuario" @if(isset($filtroUsuario)) value="{{{$filtroUsuario}}}"  @endif />
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
                                                </div>
                                            </td>
                                        @endcan
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $resp->links() }}
                        @endif
                    </div>   
                </div>
            </div>
        </div>
    </div>
</div>

@stop
