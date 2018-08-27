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
                                <form action="{{ route('usuario.adiciona') }}" method="post">
                                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

                                    <div class="form-group">
                                        <label for="name">Nome</label>
                                        <input name="name" class="form-control"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Login</label>
                                        <input name="email" class="form-control"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="nivel">Nível</label>
                                        <select name="nivel" class="form-control">
                                            <option value="" disabled selected></option>
                                                @can('checkDev')
                                                    <option value="1">Desenvolvedor</option>
                                                @endcan
                                                <option value="2">Gestor</option>
                                                <option value="3">Analista</option>
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
                                <th>ID </th>
                                <th>Nome </th>
                                <th>Login </th>
                                <th>Nível </th>
                                <th>Alterar/Excluir </th>
                            </tr>
                        </thead>
                          <tbody>
                          @foreach ($usuarios as $u)
                            <tr>
                                <td scope="row">{{$u->id}}</td>
                                <td>{{$u->name}} </td>
                                <td>{{$u->email}}</td>
                                @if($u->nivel == 1 ) 
                                    <td>Desenvolvedor</td>
                                @elseif($u->nivel == 2 )
                                    <td>Gestor</td> 
                                @elseif($u->nivel == 3 )
                                    <td>Analista</td>
                                @else
                                    <td></td>
                                @endif
                                <td>
                                    <div class="row">
                                        <a class="waves-effect waves-light btn green accent-3  modal-trigger" href="#modal1{{$u->id}}">Editar</a>
                                        <div id="modal1{{$u->id}}" class="modal">
                                            <div class="modal-content">
                                                <form action="{{ route('usuario.salvaAlt') }}" method="post">
                                                <input type="hidden" name="_token" value="{{{ csrf_token() }}}"/>
                                                <input type="hidden" name="id" value="{{{ $u->id }}}" />
                                                    <!--<input type="hidden" name="_method" value="put">-->
                                                    <div class="form-group">
                                                      <label for="name">Nome</label>
                                                      <input name="name" class="form-control" value="{{$u->name}}"/>
                                                    </div>
                                                    <div class="form-group">
                                                      <label for="email">Login</label>
                                                      <input name="email" class="form-control" value="{{$u->email}}"/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="nivel">Nível</label>
                                                        <select name="nivel" class="form-control">
                                                            <option value="{{$u->nivel}}" disabled selected> 
                                                                @if($u->nivel == 1 ) 
                                                                    Desenvolvedor
                                                                @elseif($u->nivel == 2 )
                                                                    Gestor 
                                                                @elseif($u->nivel == 3 )
                                                                    Analista
                                                                @endif
                                                            </option>
                                                                @can('checkDev')    
                                                                    <option value="1">Desenvolvedor</option>
                                                                @endcan
                                                                <option value="2">Gestor</option>
                                                                <option value="3">Analista</option>
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="waves-effect waves-light btn green accent-3 ">Atualizar</button>
                                                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn">Cancelar</a>
                                                </form>
                                            </div>
                                        </div>
                                        @can('checkDev')
                                            <a class="waves-effect waves-light btn red accent-4" href="javascript:(confirm('Deletar esse registro?') ? window.location.href='{{action('UserController@remove', $u->id)}}' : false)">Deletar</a>
                                        @endcan
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
