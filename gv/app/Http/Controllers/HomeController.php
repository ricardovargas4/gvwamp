<?php

namespace gv\Http\Controllers;
use View;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Http\Request;
use gv\Http\Requests\TempoRequest;
use gv\Coordenacao;
use gv\Processo;
use gv\Http\Requests\IndicadorRequest;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('layouts\app');
    }
    public function hello()
    {
        return view('hello');
    }

    
    public function chartjs() 
    {
        return view('chartjs');
    }

    public function tempo(TempoRequest $request){
        if(isset($request->filtroProc)){
            $filtroProc = $request->filtroProc;
         }else{
            $filtroProc=null; 
         }     
         if(isset($request->tipo_relatorio)){
            $tipo_relatorio = $request->tipo_relatorio;
         }else{
            $tipo_relatorio=null; 
         }  
         //dd($request->filtroProc->to_array());
        $coordenacaos = Coordenacao::orderBy('nome')->get();
        if(isset($request->coordenacao)){
            $coordenacao = Coordenacao::find($request->coordenacao);
            $processos = Processo::where('coordenacao',$request->coordenacao)->orderby('nome')->get();
        }else{
            $coordenacao = null;
            $processos = Processo::orderby('nome')->get();
        }    
        if(isset($request->data_inicial)){
            $data_inicial = $request->data_inicial;
            $data_final = $request->data_final;
        }else{
            $data_inicial = date('Y-m-d', strtotime('-15 day', strtotime(date('Y-m-d'))));
            $data_final = date('Y-m-d');
        }    
        return view('relatorios.tempo',compact('data_inicial','data_final','coordenacao','coordenacaos','processos','filtroProc','tipo_relatorio'));
       // $coordenacaos = null;
       // return view('coordenacao.listagem')->with('coordenacaos', $coordenacaos);
    }

    public function dadosTempos(TempoRequest $request)//($dataInicial, $dataFinal,$coordenacaoID)//(TempoRequest $request)
    {
        if($request->coordenacaoID==0){
            $coordenacaoID = '%';
        }else{
            $coordenacaoID = $request->coordenacaoID;
        }
        $processosSelecEx = array_map('intval', explode(',', $request->processosSelec));
        $processosSelec = $processosSelecEx;
//        dd($processosSelec);
        $data = DB::table('atividades')
        ->join('processos', 'atividades.id_processo', '=', 'processos.id')
        ->join('users', 'users.id', '=', 'atividades.usuario')
        ->select(DB::raw("users.email as arg, sum(TIMESTAMPDIFF(second,hora_inicio,hora_fim)/3600) as val, '' as parentID, '' as parentID2"))
        //->whereBetween('atividades.data_conciliacao', [$request->data_inicial, $request->data_final])
        ->whereBetween('atividades.data_conciliacao', [$request->dataInicial, $request->dataFinal])
        ->where('processos.coordenacao','like',$coordenacaoID)
        ->whereIn('processos.id',$processosSelec)
        ->groupby('arg')
        ->get();
        if($request->tipo_relatorio == 2){
            $data2 = DB::table('atividades')
            ->join('processos', 'atividades.id_processo', '=', 'processos.id')
            ->join('users', 'users.id', '=', 'atividades.usuario')
            ->select(DB::raw("atividades.data_conciliacao as arg, sum(TIMESTAMPDIFF(second,hora_inicio,hora_fim)/3600) as val, users.email as parentID, '' as parentID2"))
            //->whereBetween('atividades.data_conciliacao', [$request->data_inicial, $request->data_final])
            ->whereBetween('atividades.data_conciliacao', [$request->dataInicial, $request->dataFinal])
            ->where('processos.coordenacao','like',$coordenacaoID)
            ->whereIn('processos.id',$processosSelec )
            //->whereIn('processos.id',$request->processosSelec )
            ->groupby('arg', 'parentID')
            ->get();
            
            $data3 = DB::table('atividades')
            ->join('processos', 'atividades.id_processo', '=', 'processos.id')
            ->join('users', 'users.id', '=', 'atividades.usuario')
            ->select(DB::raw("processos.nome as arg, sum(TIMESTAMPDIFF(second,hora_inicio,hora_fim)/3600) as val, users.email as parentID, atividades.data_conciliacao as parentID2 "))
            //->whereBetween('atividades.data_conciliacao', [$request->data_inicial, $request->data_final])
            ->whereBetween('atividades.data_conciliacao', [$request->dataInicial, $request->dataFinal])
            ->where('processos.coordenacao','like',$coordenacaoID)
            ->whereIn('processos.id',$processosSelec )
            //->whereIn('processos.id',$request->processosSelec )
            ->groupby('arg', 'parentID', 'parentID2')
            ->orderby('val')
            ->get();
        }else{
            $data2 = DB::table('atividades')
            ->join('processos', 'atividades.id_processo', '=', 'processos.id')
            ->join('users', 'users.id', '=', 'atividades.usuario')
            ->select(DB::raw("processos.nome as arg, sum(TIMESTAMPDIFF(second,hora_inicio,hora_fim)/3600) as val, users.email as parentID, '' as parentID2"))
            //->whereBetween('atividades.data_conciliacao', [$request->data_inicial, $request->data_final])
            ->whereBetween('atividades.data_conciliacao', [$request->dataInicial, $request->dataFinal])
            ->where('processos.coordenacao','like',$coordenacaoID)
            ->whereIn('processos.id',$processosSelec )
            //->whereIn('processos.id',$request->processosSelec )
            ->groupby('arg', 'parentID')
            ->get();
            
            $data3 = DB::table('atividades')
            ->join('processos', 'atividades.id_processo', '=', 'processos.id')
            ->join('users', 'users.id', '=', 'atividades.usuario')
            ->select(DB::raw("atividades.data_conciliacao as arg, sum(TIMESTAMPDIFF(second,hora_inicio,hora_fim)/3600) as val, users.email as parentID, processos.nome as parentID2 "))
            //->whereBetween('atividades.data_conciliacao', [$request->data_inicial, $request->data_final])
            ->whereBetween('atividades.data_conciliacao', [$request->dataInicial, $request->dataFinal])
            ->where('processos.coordenacao','like',$coordenacaoID)
            ->whereIn('processos.id',$processosSelec )
            //->whereIn('processos.id',$request->processosSelec )
            ->groupby('arg', 'parentID', 'parentID2')
            ->orderby('arg')
            ->get();
        }
        
        foreach($data2 as $value){
            $data->push($value);
        }
        foreach($data3 as $value){
            $data->push($value);
        }
        
        //dd($data[10000]);
        return $data;
    }

    public function indicador(IndicadorRequest $request){
        if(isset($request->filtroProc)){
            $filtroProc = $request->filtroProc;
         }else{
            $filtroProc=null; 
         }     
         if(isset($request->tipo_relatorio)){
            $tipo_relatorio = $request->tipo_relatorio;
         }else{
            $tipo_relatorio=null; 
         }  
         //dd($request->filtroProc->to_array());
        $coordenacaos = Coordenacao::orderBy('nome')->get();
        if(isset($request->coordenacao)){
            $coordenacao = Coordenacao::find($request->coordenacao);
            $processos = Processo::where('coordenacao',$request->coordenacao)->orderby('nome')->get();
        }else{
            $coordenacao = null;
            $processos = Processo::orderby('nome')->get();
        }    
        if(isset($request->data_inicial)){
            $data_inicial = $request->data_inicial;
            $data_final = $request->data_final;
        }else{
            $data_inicial = date('Y-m-d', strtotime('-15 day', strtotime(date('Y-m-d'))));
            $data_final = date('Y-m-d');
        }    
        return view('relatorios.indicador',compact('data_inicial','data_final','coordenacao','coordenacaos','processos','filtroProc','tipo_relatorio'));
       // $coordenacaos = null;
       // return view('coordenacao.listagem')->with('coordenacaos', $coordenacaos);
    }

    public function dadosIndicador(IndicadorRequest $request)//($dataInicial, $dataFinal,$coordenacaoID)//(TempoRequest $request)
    {
        if($request->coordenacaoID==0){
            $coordenacaoID = '%';
        }else{
            $coordenacaoID = $request->coordenacaoID;
        }
        $processosSelecEx = array_map('intval', explode(',', $request->processosSelec));
        $processosSelec = $processosSelecEx;
//        dd($processosSelec);

        /*$data = DB::table('historico_indic')
        ->join('processos', 'responsavels.id_processo', '=', 'processos.id')
        ->join('periodicidades', 'periodicidades.id', '=', 'processos.periodicidade')
        ->join('users', 'users.id', '=', 'responsavels.usuario')
        ->join('tipos', 'tipos.id', '=', 'processos.tipo')
        ->leftjoin(DB::raw('(select id_processo, data_conciliada, usuario from atividades where hora_fim is null) atividades'), function($join) {$join->on('atividades.id_processo', '=', 'processos.id'); $join->on('atividades.usuario', '=', 'users.id'); })
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
                          conclusoes.ultima_data,data_final,demandas.id as demandaID, conclusoes.ultima_data - FLOAT_DIAS_UTEIS(now(),periodicidades.dias) as Ordem, (case when tipos.id = 3 then 2 when tipos.id = 4 then 1 else 0 end )concilicao "))
        ->orderBy('hora_fim','DESC')  
        ->orderBy('concilicao','DESC')
        ->orderBy('Ordem','ASC')
        ->orderBy('tipoNome','ASC')
        ->orderBy('processoNome','ASC')            
        ->get();
        */

        $NoPrazo = DB::table('historico_indic')
        ->select('user_id', DB::raw('count(*) as Prazo'))
        ->where('status', 'No Prazo')
        ->groupBy('user_id')
        ->get();

         //dd($NoPrazo);   
           // ('(select user_id, count(*) as Prazo from historico_indic where status = "No Prazo" group by user_id) as NoPrazo'), function($join) {$join->on('historico_indic.user_id', '=', 'NoPrazo.user_id'); })

        $data = DB::table('historico_indic')
        ->join('processos', 'historico_indic.processo_id', '=', 'processos.id')
        ->join('users', 'users.id', '=', 'historico_indic.user_id')
        ->leftJoinSub($NoPrazo,'NoPrazo', function ($join) {$join->on('historico_indic.user_id', '=', 'NoPrazo.user_id');})
        ->select(DB::raw("users.email as arg, round(ifnull(NoPrazo.Prazo / count(historico_indic.status) * 100,0),2)  as val, '' as parentID, '' as parentID2"))
        ->whereBetween('historico_indic.data_informada', [$request->dataInicial, $request->dataFinal])
        ->where('processos.coordenacao','like',$coordenacaoID)
        ->whereIn('processos.id',$processosSelec)
        ->groupby('arg')
        ->get();

/* 29/11 18:35
        $data = DB::table('historico_indic')
        ->join('processos', 'historico_indic.processo_id', '=', 'processos.id')
        ->join('users', 'users.id', '=', 'historico_indic.user_id')
        ->leftjoin(DB::raw("(select user_id, count(*) as Prazo from historico_indic where status = 'No Prazo' group by user_id) as NoPrazo"), function($join) {$join->on('historico_indic.user_id', '=', 'NoPrazo.user_id'); })
        ->select(DB::raw("users.email as arg, round(ifnull(NoPrazo.Prazo / count(historico_indic.status) * 100,0),2)  as val, '' as parentID, '' as parentID2"))
        ->whereBetween('historico_indic.data_informada', [$request->dataInicial, $request->dataFinal])
        ->where('processos.coordenacao','like',$coordenacaoID)
        ->whereIn('processos.id',$processosSelec)
        ->groupby('arg')
        ->get();
        */
        //   ->toSql();

        dd($data);
        if($request->tipo_relatorio == 2){
            $data2 = DB::table('atividades')
            ->join('processos', 'atividades.id_processo', '=', 'processos.id')
            ->join('users', 'users.id', '=', 'atividades.usuario')
            ->select(DB::raw("atividades.data_conciliacao as arg, sum(TIMESTAMPDIFF(second,hora_inicio,hora_fim)/3600) as val, users.email as parentID, '' as parentID2"))
            //->whereBetween('atividades.data_conciliacao', [$request->data_inicial, $request->data_final])
            ->whereBetween('atividades.data_conciliacao', [$request->dataInicial, $request->dataFinal])
            ->where('processos.coordenacao','like',$coordenacaoID)
            ->whereIn('processos.id',$processosSelec )
            //->whereIn('processos.id',$request->processosSelec )
            ->groupby('arg', 'parentID')
            ->get();
            
            $data3 = DB::table('atividades')
            ->join('processos', 'atividades.id_processo', '=', 'processos.id')
            ->join('users', 'users.id', '=', 'atividades.usuario')
            ->select(DB::raw("processos.nome as arg, sum(TIMESTAMPDIFF(second,hora_inicio,hora_fim)/3600) as val, users.email as parentID, atividades.data_conciliacao as parentID2 "))
            //->whereBetween('atividades.data_conciliacao', [$request->data_inicial, $request->data_final])
            ->whereBetween('atividades.data_conciliacao', [$request->dataInicial, $request->dataFinal])
            ->where('processos.coordenacao','like',$coordenacaoID)
            ->whereIn('processos.id',$processosSelec )
            //->whereIn('processos.id',$request->processosSelec )
            ->groupby('arg', 'parentID', 'parentID2')
            ->orderby('val')
            ->get();
        }else{
            $data2 = DB::table('atividades')
            ->join('processos', 'atividades.id_processo', '=', 'processos.id')
            ->join('users', 'users.id', '=', 'atividades.usuario')
            ->select(DB::raw("processos.nome as arg, sum(TIMESTAMPDIFF(second,hora_inicio,hora_fim)/3600) as val, users.email as parentID, '' as parentID2"))
            //->whereBetween('atividades.data_conciliacao', [$request->data_inicial, $request->data_final])
            ->whereBetween('atividades.data_conciliacao', [$request->dataInicial, $request->dataFinal])
            ->where('processos.coordenacao','like',$coordenacaoID)
            ->whereIn('processos.id',$processosSelec )
            //->whereIn('processos.id',$request->processosSelec )
            ->groupby('arg', 'parentID')
            ->get();
            
            $data3 = DB::table('atividades')
            ->join('processos', 'atividades.id_processo', '=', 'processos.id')
            ->join('users', 'users.id', '=', 'atividades.usuario')
            ->select(DB::raw("atividades.data_conciliacao as arg, sum(TIMESTAMPDIFF(second,hora_inicio,hora_fim)/3600) as val, users.email as parentID, processos.nome as parentID2 "))
            //->whereBetween('atividades.data_conciliacao', [$request->data_inicial, $request->data_final])
            ->whereBetween('atividades.data_conciliacao', [$request->dataInicial, $request->dataFinal])
            ->where('processos.coordenacao','like',$coordenacaoID)
            ->whereIn('processos.id',$processosSelec )
            //->whereIn('processos.id',$request->processosSelec )
            ->groupby('arg', 'parentID', 'parentID2')
            ->orderby('arg')
            ->get();
        }
        
        foreach($data2 as $value){
            $data->push($value);
        }
        foreach($data3 as $value){
            $data->push($value);
        }
        
        //dd($data[10000]);
        return $data;
    }



}
