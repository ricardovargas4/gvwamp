@extends('layout.principal')

@section('conteudo')
<br>
<div class="container">
    <div class="form-group col s6">
    <form action="/atividade/filtro" method="post">
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <label>Data Inicial</label>
        <input type="date"  name="data_inicial" class="form-control" @if(isset($data_inicial)) value="{{{$data_inicial}}}" @else value = "{{{date('Y-m-d', strtotime('-15 day', strtotime(date('Y-m-d'))))}}}" @endif placeholder="dd/mm/aaaa"/>
        <label>Data Final</label>
        <input type="date" name="data_final" class="form-control" @if(isset($data_final)) value="{{{$data_final}}}" @else value = "{{{date('Y-m-d')}}}" @endif placeholder="dd/mm/aaaa"/>
        <button type="submit" class="btn waves-effect light-green accent-3"> Filtrar</button>
    </form>
    </div>
</div>
@if (!isset($filtro))
<div class="container">
    Selecione uma range de datas.
</div>    
@elseif($filtro==0)
<div class="container">
    Sem dados para o período selecionado.
</div>
@else
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
                                <form action="/atividade/adiciona" method="post">
                                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                    <input type="hidden" name="usuario" value="{{$usuario}}" />

                                    <div class="form-group col s6">
                                        <label for="id_processo">Processo</label>
                                        <select name="id_processo" class="form-control">
                                            <option value="" disabled selected>Selecione o Processo</option>
                                            @foreach($processos as $p)
                                                <option value="{{$p->id}}">{{$p->nome}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col s6">
                                        <label>Data Conciliação</label>
                                        <input type="date" name="data_conciliacao" class="form-control" placeholder="dd/mm/aaaa"/>
                                    </div>
  
                                    <div class="form-group col s6">
                                        <label>Horário de Início</label>
                                        <input type="datetime-local" step="1" name="hora_inicio" class="form-control" placeholder="dd/mm/aaaa hh:mm:ss"/>
                                    </div>
                                    <div class="form-group col s6">
                                        <label>Horário Final</label>
                                        <input type="datetime-local" step="1" name="hora_fim" class="form-control" placeholder="dd/mm/aaaa hh:mm:ss"/>
                                    </div>                  
                                    <div class="form-group col s6">
                                        <label>Data Meta</label>
                                        <input type = "date" name="data_meta" class="form-control" placeholder="Data Meta"/>
                                    </div>                  
                                    <div class="form-group col s6">
                                        <label>Data Conciliada</label>
                                        <input type = "date" name="data_conciliada" class="form-control" placeholder="Data Conciliada"/>
                                    </div>     
                                    <div class="form-group col s6">
                                        <label>Última Data Conciliada</label>
                                        <input type = "date" name="ultima_data" class="form-control" placeholder="Última Data Conciliada"/>
                                    </div>  
                                    <div class="form-group col s6">
                                        <label>Classificação</label>
                                        <select name="classificacao" class="form-control">
                                                @foreach($classificacoes as $c)
                                                <option value="{{$c->id}}">{{$c->opcao}}</option>
                                                @endforeach
                                        </select>
                                    </div>      
                                    <div class="form-group col s6">
                                        <label>Observação</label> 
                                        <textarea name="observacao"> </textarea> 
                                    </div>   
                                    <div class="form-group col s6">
                                        <label>Volumetria</label> 
                                        <textarea name="volumetria"> </textarea> 
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
                                <th>Nome Processo </th>
                                <th>Usuário </th>
                                <th>Data Conciliação </th>
                                <th> Inicio </th>
                                <th> Fim </th>
                                <th> Data Meta </th>
                                <th> Data Conciliada </th>
                                <th> Ultima Data </th>
                                <th> Alterar/Excluir </th>
                            </tr>
                        </thead>
                          <tbody>
                          @foreach ($atividades as $a)
                            <tr>
                                <td scope="row">{{$a->id}}</td>
                                <td> {{$a->processo_Nome}} </td>
                                <td> {{$a->user_Email}} </td>
                                <td> @if(isset($a->data_conciliacao)) {{date('d/m/Y', strtotime($a->data_conciliacao))}} @else {{$a->data_conciliacao}} @endif</td>
                                <td> @if(isset($a->hora_inicio)) {{date('d/m/Y H:i:s', strtotime($a->hora_inicio))}} @else {{$a->hora_inicio}} @endif</td>
                                <td> @if(isset($a->hora_fim)) {{date('d/m/Y H:i:s', strtotime($a->hora_fim))}} @else {{$a->hora_fim}} @endif </td>
                                <td> @if(isset($a->data_meta)) {{date('d/m/Y', strtotime($a->data_meta))}} @else {{$a->data_meta}} @endif </td>
                                <td> @if(isset($a->data_conciliada)) {{date('d/m/Y', strtotime($a->data_conciliada))}} @else {{$a->data_conciliada}} @endif </td>
                                <td> @if(isset($a->ultima_data)) {{date('d/m/Y', strtotime($a->ultima_data))}}  @else {{$a->ultima_data}} @endif</td>
                                <td>
                                    <div class="row">
                                        <a class="waves-effect waves-light btn green accent-3  modal-trigger" href="#modal1{{$a->id}}">Editar</a>
                                        <div id="modal1{{$a->id}}" class="modal">
                                            <div class="modal-content">
                                                <form action="/atividade/salvaAlt" method="post">
                                                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                <input type="hidden" name="id" value="{{{ $a->id }}}" />
                                                    <!--<input type="hidden" name="_method" value="put">-->
                                                <input type="hidden" name="data_inicial" value="{{{$data_inicial}}}" />
                                                <input type="hidden" name="data_final" value="{{{$data_final}}}" />    
                                                    <div class="form-group col s6">
                                                        <label for="id_processo">Processo</label>
                                                        <select name="id_processo" class="form-control">
                                                            <option value="{{{ $a->processo_ID }}}" disabled selected>{{{$a->processo_Nome }}}</option>
                                                            @foreach($processos as $p)
                                                                <option value="{{$p->id}}">{{$p->nome}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group col s6">
                                                    <label>Usuário</label>
                                                      <select name="usuario" value="{{$a->user_Id}}" class="form-control">
                                                      <option value="{{{ $a->user_Id }}}" disabled selected>{{{$a->user_Email }}}</option>
                                                            @foreach($users as $u)
                                                            <option value="{{$u->id}}">{{$u->email}}</option>
                                                            @endforeach
                                                       </select>
                                                    </div>
                                                    <div class="form-group col s6">
                                                        <label>Data Conciliação</label>
                                                        <input type="date" name="data_conciliacao" class="form-control" placeholder="dd/mm/aaaa" value="{{$a->data_conciliacao}}"/>
                                                     </div>
  
                                                    <div class="form-group col s6">
                                                        <label>Horário de Início</label>
                                                        <input name="hora_inicio" class="form-control" placeholder="Horário de Início" value="{{$a->hora_inicio}}" />
                                                    </div>
                                                    <div class="form-group col s6">
                                                        <label>Horário Final</label>
                                                        <input name="hora_fim" class="form-control" placeholder="Horário Final" value="{{$a->hora_fim}}"/>
                                                    </div>                  
                                                    <div class="form-group col s6">
                                                        <label>Data Meta </label>
                                                        <input type="date" name="data_meta" class="form-control" placeholder="dd/mm/aaaa" value="{{$a->data_meta}}"/>
                                                    </div>                  
                                                    <div class="form-group col s6">
                                                        <label>Data Conciliada</label>
                                                        <input type="date" name="data_conciliada" class="form-control" placeholder="dd/mm/aaaa" value="{{$a->data_conciliada}}"/>
                                                    </div>     
                                                    <div class="form-group col s6">
                                                        <label>Última Data Conciliada</label>
                                                        <input type="date" name="ultima_data" class="form-control" placeholder="dd/mm/aaaa" value="{{$a->ultima_data}}"/>
                                                    </div>
                                                    <div class="form-group col s6">
                                                        <label>Classificação</label>
                                                        <select name="classificacao" value="{{ $a->class_ID }}" class="form-control">
                                                        <option value="{{ $a->class_ID }}" >{{{$a->class_Opcao}}}</option>
                                                                <option value=""></option>
                                                                @foreach($classificacoes as $c)
                                                                    <option value="{{$c->id}}">{{$c->opcao}}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>      
                                                    <div class="form-group col s6">
                                                        <label>Observação</label> 
                                                        <input name="observacao" value="{{$a->observacao}}"/> 
                                                    </div>       
                                                    <div class="form-group col s6">
                                                        <label>Volumetria</label> 
                                                        <input name="volumetria" value="{{$a->volumetria}}"/> 
                                                    </div>                                     
                                                    <button type="submit" class="waves-effect waves-light btn green accent-3 ">Atualizar</button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
                                            </div>
                                        </div>
                                        <a class="waves-effect waves-light btn red accent-4" href="javascript:(confirm('Deletar esse registro?') ? window.location.href='{{action('AtividadeController@remove',  [$a->id,$data_inicial, $data_final])}}' : false)">Deletar</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
            
                        </tbody>
                    </table>
                    {{ $atividades->links() }}
                </div>   
            </div>
        </div>
    </div>
</div>
@endif
@stop
