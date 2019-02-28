@extends('layout.principal')

@section('conteudo')
<div class="container">
@if (count($errors) > 0)
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
<div class="prazos">
  <div class= "box"> Prazo No Dia {{$percPrazo}} </div>
  <div class= "box"> Prazo No Mês Corrente {{$percPrazoMes}} </div>
  <div class= "box"> Prazo No Ano Corrente {{$percPrazoAno}} </div>
</div>
<h1>Atividades</h1>
  @if ($aberta->isEmpty())
    <form name='TabAtividades' action="{{ route('atividade.iniciar') }}" method="post"> 
  @else
    <form name='TabAtividades' action="{{ route('atividade.parar') }}" method="post">
  @endif
    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
    <input type="hidden" name="usuario" value="{{{ $usuario_id }}}" />      
    <table class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <th> Tipo </th>
          <th> Nome Processo </th>
          <th> Status </th>
          <th> Data Meta </th>
          <th> Data Ultima Atualização </th>
          <th> Data Conciliada </th>
          <th> Ação </th>
          @if (!$aberta->isEmpty() and !$classificacoes->isEmpty())
            <th> Classificação </th>         
          @endif 
          @if (!$aberta->isEmpty())
            <th> Observação </th>         
          @endif      
          @if (!$aberta->isEmpty() and !$processosVol->isEmpty())
            <th> Volumetria </th>         
          @endif 
        <tr>
      </thead>
      <tbody>
        @foreach ($atividades as $index => $a)
          <tr>
              <td> {{$a->tipoNome}} <input type="hidden" id='Tipo{{{ $a->processoId }}}' name="tipoId[{{{$index}}}]" value="{{{ $a->tipoId }}}" /> </td>
              <td> {{$a->processoNome}} <input type="hidden" name="id_processo[{{{$index}}}]" value="{{{ $a->processoId }}}" /> </td>
              <td>
                @if($a->tipoId == 3)
                  @if (($a->ultima_data)<($a->data_meta))
                    <span style="color: Red;"> <i class="fa fa-circle fa-lg"></i></span> 
                  @elseif (($a->ultima_data)>($a->data_meta))
                    <span style="color: Green;"> <i class="fa fa-circle fa-lg"></i></span>
                  @else
                    <span style="color: Yellow;"> <i class="fa fa-circle fa-lg"></i></span>
                  @endif
                @elseif($a->tipoId == 4)
                  @if (($a->data_final)<(date('Y-m-d')))
                    <span style="color: Red;"> <i class="fa fa-circle fa-lg"></i></span> 
                    @elseif (($a->data_final)>(date('Y-m-d')))
                    <span style="color: Green;"> <i class="fa fa-circle fa-lg"></i></span>
                  @else
                    <span style="color: Yellow;"> <i class="fa fa-circle fa-lg"></i></span>
                  @endif
                @endif
              </td>
              <td> 
                  @if($a->tipoId == 3)
                    <input type="date" name="data_meta[{{{$index}}}]" value="{{{ $a->data_meta }}}" hidden/>
                    {{$a->data_meta ? \Carbon\Carbon::parse($a->data_meta)->format('d/m/Y') : null }}
                  @elseif($a->tipoId == 4)
                    <input type="date" name="data_meta[{{{$index}}}]" value="{{{ $a->data_final }}}" hidden/> 
                    {{$a->data_final ? \Carbon\Carbon::parse($a->data_final)->format('d/m/Y') : null }}
                  @else
                    <input type="hidden" name="data_meta[{{{$index}}}]" value="" readonly/>   
                  @endif
              </td>
              <td> 
                @if($a->tipoId == 3)
                  <input type=date name="ultima_data[{{{$index}}}]" value="{{{ $a->ultima_data }}}" hidden/> 
                  {{ $a->ultima_data ? \Carbon\Carbon::parse($a->ultima_data)->format('d/m/Y') : null }}
                @else
                  <input type="hidden" name="ultima_data[{{{$index}}}]" value=""/>   
                @endif
              </td>
              @if($a->tipoId==4)
                <td> <input type="hidden" id='DtConc{{{ $a->processoId }}}' name="data_conciliada[{{{$index}}}]" value="{{{ $a->data_conciliada }}}" <?php if (!$aberta->isEmpty()){ ?> readonly <?php   } ?>/> </td>
              @else
                <td> <input type="text" class="datepicker" placeholder="DD/MM/AAAA" id='DtConc{{{ $a->processoId }}}' name="data_conciliada[{{{$index}}}]" value="{{{ $a->data_conciliada }}}" <?php if (!$aberta->isEmpty()){ ?> readonly <?php   } ?>/> </td>
              @endif
              @if (($a->aberta)==1)
                <td>  
                  <button id='BtnP{{{ $a->processoId }}}' title="Parar o processo sem encerrar o indicador." type="submit" class="waves-effect waves-light btn" name="submit" value="P{{{ $index }}}">
                    <i class="fa fa-pause" aria-hidden="true"></i>
                  </button>
                  @if($a->tipoId == 3 or $a->tipoId == 4)
                    <button id='BtnC{{{ $a->processoId }}}' title="Finalizar o processo encerrando o indicador."  type="submit" class="waves-effect waves-light btn" name="submit" value="C{{{ $index }}}">
                      <i class="fa fa-stop" aria-hidden="true"></i>
                    </button>
                    @if(date('H') < 10 and $a->tipoId == 3)
                      <div>
                        <input type="checkbox" name = "tolerancia[{{{$index}}}]" id="checkTolerancia" />
                        <label for="checkTolerancia">Atividade Concluída na Tolerância</label>
                      </div>
                    @endif
                  @endif
                </td>

                @if (($a->aberta)==1 and !$classificacoes->isEmpty())
                  <td>
                    <div class="form-group col s6">
                      <select name="classificacao" class="form-control">
                          @foreach($classificacoes as $c)
                              <option value="{{$c->id}}">{{$c->opcao}}</option>
                          @endforeach
                      </select>
                    </div>
                  </td>
                @endif
                <td><textarea name="observacao"> </textarea> </td>
                @if (!$aberta->isEmpty() and !$processosVol->isEmpty())
                  <td>  <input type="number" name = "volumetria[{{{$index}}}]" /> </td>         
                @endif 
              @else
                <td>  <button id='BtnI{{{ $a->processoId }}}' type="submit" title="Iniciar o processo." class="waves-effect waves-light btn" <?php if (!$aberta->isEmpty()){ ?> disabled <?php   } ?>  name="submit" value="{{{ $index }}}"> 
                        <i class="fa fa-play" aria-hidden="true"></i>
                      </button> </td>
                  @if (!$aberta->isEmpty() )
                      <td></td>
                  @endif
                  @if (!$aberta->isEmpty() and !$processosVol->isEmpty())
                    <td>  </td>         
                  @endif 
              @endif
            </tr>
        @endforeach
      </tbody>
    </table>
  </form>
</div>  
@stop
