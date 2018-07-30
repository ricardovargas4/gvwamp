<?php

namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use gv\Processo;
use gv\Conclusao;
use gv\Demanda;
use gv\Classificacao;
use gv\Http\Requests\AtividadeRequest;
use Request;
use Auth;
use gv\Atividade;
use gv\Volumetria;
use gv\Observacao;
use gv\User;
use Carbon\Carbon;

class AtividadeController extends Controller
{
    public function home(){
        $usuario_id= Auth::user()->id;

        $aberta = DB::table('atividades')
        ->join('processos','atividades.id_processo','=','processos.id')
        ->where('atividades.usuario','=',Auth::user()->id)
        ->where('atividades.hora_fim','=',null)
        ->get();
        if(!$aberta->isEmpty()){
            $classificacoes =Classificacao::where('id_processo','=',$aberta[0]->id)->get();
        }
        $processosVol= 0;
        if(!$aberta->isEmpty()){
            $processosVol =Processo::where('id','=',$aberta[0]->id_processo)
                                    ->where('volumetria','=','S')->get();
        }
        if(!$aberta->isEmpty()){
            $demanda =Demanda::where('id_processo','=',$aberta[0]->id_processo)
                              ->where('data_conclusao','=',null)
                              ->where('id_responsavel','=', $usuario_id)
                              ->get();
        }
         $atividades = DB::table('responsavels')
        ->join('processos', 'responsavels.id_processo', '=', 'processos.id')
        ->join('periodicidades', 'periodicidades.id', '=', 'processos.periodicidade')
        ->join('users', 'users.id', '=', 'responsavels.usuario')
        ->join('tipos', 'tipos.id', '=', 'processos.tipo')
        ->leftjoin(DB::raw('(select id_processo, data_conciliada from atividades where hora_fim is null) atividades'), function($join) {$join->on('atividades.id_processo', '=', 'processos.id'); })
        ->leftjoin(DB::raw('(select id_processo, max(data_conciliada) ultima_data from conclusoes group by id_processo) conclusoes'), function($join) {$join->on('conclusoes.id_processo', '=', 'processos.id'); })
        ->leftjoin(DB::raw('(select id, id_processo, data_final, id_responsavel from demandas where data_conclusao is null) demandas'), function($join) {$join->on('demandas.id_processo', '=', 'processos.id');$join->on('users.id', '=', 'demandas.id_responsavel'); })
        ->where('users.id','=',$usuario_id)
        ->where(function ($query) {
            $query->where(function ($query) {
                $query->where('tipos.id','=', '4' )
                    ->where('data_final','<>', null);
            })->orWhere('tipos.id','<>', '4');
        })
        ->select(DB::raw("distinct processos.id as processoId, processos.nome as processoNome, tipos.id as tipoId, 
                          tipos.nome as tipoNome, atividades.data_conciliada,
                          FLOAT_DIAS_UTEIS(now(),periodicidades.dias) data_meta, 
                          (CASE WHEN atividades.id_processo is not null then 'aberta' else '' end) as hora_fim, 
                          conclusoes.ultima_data,data_final,demandas.id as demandaID"))
        ->orderByDesc('hora_fim')                          
        ->get();
        
        foreach($atividades as $a){
            $a->aberta=0;
            if(!$aberta->isEmpty()){     
                if($a->tipoId==4 && $aberta[0]->id==$a->processoId){
                    foreach($demanda as $d){
                        if($d->data_final==$aberta[0]->data_meta and $d->id==$a->demandaID){
                            $a->aberta=1;
                        }
                    }
                }else{
                    if($aberta[0]->id==$a->processoId){
                        $a->aberta=1;
                    }
                }
            }
        }
        $atividades = $atividades->sortByDesc('aberta');
        $total=0;
        $prazo=0;
        foreach ($atividades as $atividade) {
            if(($atividade->ultima_data) >= ($atividade->data_meta)) {
                $prazo ++;
            };
            $total ++;
            }
        if($total<>0) {
            $percPrazo = ($prazo/$total*100)."%";
        }else{
            $percPrazo = 0;
        }
       
        $prazoM = 0;
        $totalM = 0;
        $PrazoMes = DB::table('historico_indic')
        ->where('historico_indic.user_id','=',$usuario_id)
        ->whereMonth('data_informada','=',date('m'))
        ->select('status')
        ->get();
        foreach ($PrazoMes as $atividade) {
            if(($atividade->status)=='No Prazo') {
                $prazoM ++;
            };
            $totalM ++;
            }
  
        if($totalM<>0) {
            $percPrazoMes = round($prazoM/$totalM*100,2)."%";
        }else{
            $percPrazoMes = 0;
        }

        $prazoA = 0;
        $totalA = 0;
        $PrazoAno = DB::table('historico_indic')
        ->where('historico_indic.user_id','=',$usuario_id)
        ->whereYear('data_informada','=',date('Y'))
        ->select('status')
        ->get();

        foreach ($PrazoAno as $atividade) {
            if(($atividade->status)=='No Prazo') {
                $prazoA ++;
            };
            $totalA ++;
            }
        if($totalA<>0) {
            $percPrazoAno = round($prazoA/$totalA*100,2)."%";
        }else{
            $percPrazoAno = 0;
        }
        return view('atividade.telaAtividades',compact('atividades','usuario_id','aberta','percPrazo','percPrazoMes','percPrazoAno','classificacoes','processosVol','demanda'));

    }

    public function iniciar(AtividadeRequest $request){

        if(count($request->data_meta)-1<$request->submit){
            $data_metaCalc = null;
        }else{
            $data_metaCalc = $request->data_meta[$request->submit];
        }
        if(count($request->ultima_data)-1<$request->submit){
            $ultima_dataCalc = null;
        }else{
            $ultima_dataCalc = $request->ultima_data[$request->submit];
        }
        Atividade::create(['id_processo'=>$request->id_processo[$request->submit],
                            'usuario'=> Auth::user()->id,
                            'data_conciliacao'=>date('Y-m-d H:i:s'),
                            'hora_inicio'=>date('Y-m-d H:i:s'),
                            'data_meta'=>$data_metaCalc,
                            'data_conciliada'=>$request->data_conciliada[$request->submit],
                            'ultima_data'=>$ultima_dataCalc]);
        return redirect()->action('AtividadeController@home');
    }
            
    public function parar(AtividadeRequest $request){
        $aberta = DB::table('atividades')
        ->where('atividades.usuario','=',Auth::user()->id)
        ->where('atividades.hora_fim','=',null)
        ->where('atividades.id_processo','=',$request->id_processo[substr($request->submit,1,10)])
        ->first();
        $opcao = substr($request->submit,0,1);
        if( ! empty($request['tolerancia']) && date('H') < 10){
            $data_conciliacao = DB::table(DB::raw('DUAL'))->select(DB::raw("FLOAT_DIAS_UTEIS(now(),-1) data"))->first([DB::raw(1)]);
            $data_conciliacao = $data_conciliacao->data;
        }else{
            $data_conciliacao = date('Y-m-d H:i:s');
        }
        if($opcao =='C'){
            if($request->tipoId[substr($request->submit,1,10)]==3){
                $id_conclusao=Conclusao::where('id_processo','=',$request->id_processo[substr($request->submit,1,10)])
                                       ->where('data_conciliada','=',$request->data_conciliada[substr($request->submit,1,10)])->get();                               

                if(!$id_conclusao->count()>0){
                    Conclusao::create(['id_processo'=>$request->id_processo[substr($request->submit,1,10)],
                                       'data_conciliada'=>$request->data_conciliada[substr($request->submit,1,10)],
                                       'data_conciliacao'=>$data_conciliacao]);
                }

            }
            if($request->tipoId[substr($request->submit,1,10)]==4){
                $id_demanda=Demanda::where('id_processo','=',$request->id_processo[substr($request->submit,1,10)])
                                    ->where('data_final','=',$request->data_meta[substr($request->submit,1,10)])
                                    ->where('data_conclusao','=',null)
                                    ->first(); 
                $demandaSave = Demanda::find($id_demanda->id);
                $demandaSave->data_conclusao = date('Y-m-d H:i:s');
                $demandaSave->save();
            }
        }
        $atividade = Atividade::find($aberta->id);
        
        $atividade->hora_fim = date('Y-m-d H:i:s');
        
        $atividade->save();

        if(!$request->observacao == null or !$request->classificacao == null) {
            Observacao::create(['id_atividade'=>$aberta->id,
                               'observacao'=>$request->observacao,
                               'classificacao'=>$request->classificacao]);
        }
        if(!$request->volumetria[0] == null) {
            Volumetria::create(['id_atividade'=>$aberta->id,
                               'volumetria'=>$request->volumetria[0]]);
        }
        
        return redirect()->action('AtividadeController@home');
    }
    public function lista(){
        $filtro = null;
        return  view('atividade.listagem',compact('filtro'));
    }
    public function filtro(AtividadeRequest $request,$data=null){
        if(!is_null($request->data_inicial)){
            $data_inicial = $request->data_inicial;
            $data_final = $request->data_final;
            $usuario =  Auth::user()->id;
            $processos = Processo::all();
            $users = User::all();
            $classificacoes =Classificacao::all();
            $atividades = DB::table('atividades')
            ->join('processos', 'processos.id', '=', 'atividades.id_processo')
            ->join('users', 'users.id', '=', 'atividades.usuario')
            ->leftjoin('observacoes','atividades.id','=','observacoes.id_atividade')
            ->leftjoin('classificacoes','observacoes.classificacao','=','classificacoes.id')
            ->leftjoin('volumetrias','atividades.id','=','volumetrias.id_atividade')
            ->select(DB::raw("atividades.id as id, processos.id as processo_ID, processos.nome as processo_Nome, 
                            users.id as user_Id, users.email as user_Email,atividades.data_conciliacao, atividades.hora_inicio,
                            atividades.hora_fim, atividades.data_meta,data_conciliada,ultima_data, observacoes.id observacao_ID, 
                            observacoes.observacao, classificacoes.id class_ID, classificacoes.opcao class_Opcao, volumetrias.volumetria" ))
            ->where('atividades.hora_inicio','>=',$request->data_inicial)
            ->where('atividades.hora_fim','<=',$request->data_final." 23:59:59")
            ->orderBy('hora_inicio', 'ASC')
            ->paginate(15);
            $atividades->appends(Input::except('page'));
            $filtro = count($atividades);
            return  view('atividade.listagem',compact('atividades','users','processos','usuario','filtro','data_inicial','data_final','classificacoes'));
        }
        else{
            $filtro = null;
            return  view('atividade.listagem',compact('filtro'));
        }
       
    }
    
    public function remove($id,$data_inicial=null,$data_final=null){
        $atividade = Atividade::find($id);
        $atividade->delete();
        $filtro = null;
        $data=['data_inicial' =>$data_inicial,
        'data_final' => $data_final];
        return redirect()->route('atividade.filtro',$data);   
    }
    
    public function salvaAlt(AtividadeRequest $request){
        
        $id = $request->id;
        Atividade::whereId($id)->update($request->except('_token','data_inicial','data_final','observacao','classificacao','volumetria'));
        $filtro = null;
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        $data=['data_inicial' =>$data_inicial,
               'data_final' => $data_final];
        
        $id_obs=Observacao::where('id_atividade','=',$id)->get();
        if($id_obs->count()>0){
            if(!$request->observacao == null or !$request->classificacao ==null) {
                $obs = Observacao::find($id_obs[0]->id);
                $obs->observacao = $request->observacao;
                $obs->classificacao = $request->classificacao;
                $obs->save();
            }else{
                $obs = Observacao::find($id_obs[0]->id);
                $obs->delete();
            }    
        }elseif(!$request->observacao == null or !$request->classificacao == null){
            Observacao::create(['id_atividade'=> $id,
                                'observacao'=>$request->observacao,
                                'classificacao'=>$request->classificacao]);
        }

        $id_volumetria=Volumetria::where('id_atividade','=',$id)->get();
        if($id_volumetria->count()>0){
            if(!$request->volumetria == null) {
                $volumetria = Volumetria::find($id_volumetria[0]->id);
                $volumetria->volumetria = $request->volumetria;
                $volumetria->save();
            }else{
                $volumetria = Volumetria::find($id_volumetria[0]->id);
                $volumetria->delete();
            }    
        }elseif(!$request->volumetria == null){
            Volumetria::create(['id_atividade'=> $id,
                                'volumetria'=>$request->volumetria]);
        }
        
        return redirect()->route('atividade.filtro',$data);  
    }

    public function adiciona(AtividadeRequest $request){

        $atividade = Atividade::create($request->all());
        $filtro = null;
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        $data=['data_inicial' =>$data_inicial,
               'data_final' => $data_final];
        
        if(!$request->observacao == null or !$request->classificacao == null) {
            Observacao::create(['id_atividade'=>$atividade->id,
                                'observacao'=>$request->observacao,
                                'classificacao'=>$request->classificacao
                                ]);
        }
        if(!$request->volumetria == null) {
            Volumetria::create(['id_atividade'=>$atividade->id,
                                'volumetria'=>$request->volumetria]);
        }

        return redirect()->route('atividade.filtro',$data);  
    }    
}
