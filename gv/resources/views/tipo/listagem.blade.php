@extends('layout.principal')

@section('conteudo')
<br>

<div class="card demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid cad_card">
        <div class="card-content">
            <div class="row">
                <div class="container">
                    @can('checkDev')
                        <ul class="collapsible" data-collapsible="accordion">
                            <li>
                                <div class="collapsible-header">
                                    <i class="fa fa-plus-square-o fa-sm"></i>Adicionar
                                </div>
                                <div class="collapsible-body">
                                    <form action="{{ route('tipo.adiciona') }}" method="post">
                                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

                                        <div class="form-group">
                                            <label for="nome">Nome</label>
                                            <input name="nome" class="form-control"/>
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
                                <th>Nome </th>
                                @can('checkDev')
                                    <th> Alterar/Excluir </th>
                                @endcan
                            </tr>
                        </thead>
                          <tbody>
                          @foreach ($tipos as $t)
                            <tr>
                               <td scope="row">{{$t->id}}</td>
                                <td> {{$t->nome}} </td>
                                @can('checkDev')
                                    <td>
                                        <div class="row">
                                            <a class="waves-effect waves-light btn green accent-3  modal-trigger" href="#modal1{{$t->id}}">Editar</a>
                                            <div id="modal1{{$t->id}}" class="modal">
                                                <div class="modal-content">
                                                    <form action="{{ route('tipo.salvaAlt') }}" method="post">
                                                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                    <input type="hidden" name="id" value="{{{ $t->id }}}" />
                                                                                                            <div class="form-group">
                                                        <label for="nome">Nome</label>
                                                        <input name="nome" class="form-control" value="{{$t->nome}}"/>
                                                        </div>
                                                        <button type="submit" class="waves-effect waves-light btn green accent-3 ">Atualizar</button>
                                                        <a href="#!" class="modal-action modal-close waves-effect waves-green btn">Cancelar</a>
                                                    </form>
                                                </div>
                                            </div>
                                            <a class="waves-effect waves-light btn red accent-4" href="javascript:(confirm('Deletar esse registro?') ? window.location.href='{{action('TipoController@remove', $t->id)}}' : false)">Deletar</a>
                                        </div>
                                    </td>
                                @endcan
                                <!--
                            <div class="container">
                                @foreach ($tipos as $tipo)
                                    {{ $tipo->nome }}
                                @endforeach
                            </div>
                                -->
                            </tr>
                            @endforeach
            
                        </tbody>
                    </table>
                    {{ $tipos->links() }}
            
                </div>   
            </div>
        </div>
    </div>
</div>

@stop
