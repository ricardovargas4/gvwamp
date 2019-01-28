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
        return view('graficos.tempo',compact('data_inicial','data_final','coordenacao','coordenacaos','processos','filtroProc','tipo_relatorio'));
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
        ->orderby('arg')
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
            ->orderby('arg')
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
            ->orderby('arg')
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
            ->orderby('arg')
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
        return view('graficos.indicador',compact('data_inicial','data_final','coordenacao','coordenacaos','processos','filtroProc','tipo_relatorio'));
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
        $textoProc = "";
        foreach($processosSelec as $p){
            if($textoProc==""){     
                $textoProc = "'".$p."'";
            }else{
                $textoProc = $textoProc.",'".$p."'";
            }
        }

        $data = DB::table('historico_indic')
        ->join('processos', 'historico_indic.processo_id', '=', 'processos.id')
        ->join('users', 'users.id', '=', 'historico_indic.user_id')
        ->leftjoin(DB::raw("(select user_id, 
                                    count(*) as Prazo 
                             from historico_indic 
                             where status = 'No Prazo' 
                             and data_informada between '" . $request->dataInicial . "'  and  '" .  $request->dataFinal .  "' 
                             and processo_id in (".$textoProc.") 
                             group by user_id) as NoPrazo"), function($join) {$join->on('historico_indic.user_id', '=', 'NoPrazo.user_id'); })
        ->leftjoin(DB::raw("(select user_id, 
                                    count(*) as Total 
                             from historico_indic 
                             where data_informada between '" . $request->dataInicial . "'  and  '" .  $request->dataFinal .  "' 
                             and processo_id in (".$textoProc.") 
                             group by user_id) as Total"), function($join) {$join->on('historico_indic.user_id', '=', 'Total.user_id'); })                     
        ->select(DB::raw("distinct users.email as arg, round(ifnull(NoPrazo.Prazo,0) / ifnull(Total.Total,0) * 100,2)  as val, '' as parentID, '' as parentID2"))
        ->whereBetween('historico_indic.data_informada', [$request->dataInicial, $request->dataFinal])
        ->where('processos.coordenacao','like',$coordenacaoID)
        ->whereIn('processos.id',$processosSelec)
        ->orderby('arg')
        ->get();

        //->toSql();
        if($request->tipo_relatorio == 2){
            
            $data2 = DB::table('historico_indic')
            ->join('processos', 'historico_indic.processo_id', '=', 'processos.id')
            ->join('users', 'users.id', '=', 'historico_indic.user_id')
            ->leftjoin(DB::raw("(select user_id, 
                                        data_informada,
                                        count(*) as Prazo 
                                from historico_indic 
                                where status = 'No Prazo'   
                                and data_informada between '" . $request->dataInicial . "'  and  '" .  $request->dataFinal .  "' 
                                and processo_id in (".$textoProc.")                     
                                group by user_id, data_informada) as NoPrazo"), function($join) {$join->on('historico_indic.user_id', '=', 'NoPrazo.user_id'); $join->on('historico_indic.data_informada', '=', 'NoPrazo.data_informada'); })
            ->leftjoin(DB::raw("(select user_id, 
                                        data_informada,
                                        count(*) as Total 
                                from historico_indic 
                                where data_informada between '" . $request->dataInicial . "'  and  '" .  $request->dataFinal .  "' 
                                and processo_id in (".$textoProc.") 
                                group by user_id, data_informada) as Total"), function($join) {$join->on('historico_indic.user_id', '=', 'Total.user_id'); $join->on('historico_indic.data_informada', '=', 'Total.data_informada'); })                     
            ->select(DB::raw("distinct historico_indic.data_informada as arg, round(ifnull(NoPrazo.Prazo,0) / ifnull(Total.Total,0) * 100,2)  as val, users.email as parentID, '' as parentID2"))
            ->whereBetween('historico_indic.data_informada', [$request->dataInicial, $request->dataFinal])
            ->where('processos.coordenacao','like',$coordenacaoID)
            ->whereIn('processos.id',$processosSelec)
            ->orderby('arg')
            ->get();

            $data3 = DB::table('historico_indic')
            ->join('processos', 'historico_indic.processo_id', '=', 'processos.id')
            ->join('users', 'users.id', '=', 'historico_indic.user_id')
            ->leftjoin(DB::raw("(select user_id,
                                        processo_id, 
                                        data_informada,
                                        count(*) as Prazo 
                                from historico_indic 
                                where status = 'No Prazo' 
                                and data_informada between '" . $request->dataInicial . "'  and  '" .  $request->dataFinal .  "'  
                                and processo_id in (".$textoProc.")                                                       
                                group by user_id,data_informada,processo_id) as NoPrazo"), function($join) {$join->on('historico_indic.user_id', '=', 'NoPrazo.user_id'); $join->on('historico_indic.data_informada', '=', 'NoPrazo.data_informada'); $join->on('historico_indic.processo_id', '=', 'NoPrazo.processo_id'); })
            ->leftjoin(DB::raw("(select user_id, 
                                        processo_id,
                                        data_informada,
                                        count(*) as Total 
                                from historico_indic 
                                where data_informada between '" . $request->dataInicial . "'  and  '" .  $request->dataFinal .  "' 
                                and processo_id in (".$textoProc.")       
                                group by user_id,data_informada,processo_id) as Total"), function($join) {$join->on('historico_indic.user_id', '=', 'Total.user_id'); $join->on('historico_indic.data_informada', '=', 'Total.data_informada'); $join->on('historico_indic.processo_id', '=', 'Total.processo_id'); })                     
            ->select(DB::raw("distinct processos.nome as arg, round(ifnull(NoPrazo.Prazo,0) / ifnull(Total.Total,0) * 100,2)  as val, users.email as parentID, historico_indic.data_informada as parentID2"))
            ->whereBetween('historico_indic.data_informada', [$request->dataInicial, $request->dataFinal])
            ->where('processos.coordenacao','like',$coordenacaoID)
            ->whereIn('processos.id',$processosSelec)
            ->orderby('arg')
            ->get();

        }else{
            $data2 = DB::table('historico_indic')
            ->join('processos', 'historico_indic.processo_id', '=', 'processos.id')
            ->join('users', 'users.id', '=', 'historico_indic.user_id')
            ->leftjoin(DB::raw("(select user_id, 
                                        processo_id,
                                        count(*) as Prazo 
                                from historico_indic 
                                where status = 'No Prazo'     
                                and data_informada between '" . $request->dataInicial . "'  and  '" .  $request->dataFinal .  "'  
                                and processo_id in (".$textoProc.")                        
                                group by user_id, processo_id) as NoPrazo"), function($join) {$join->on('historico_indic.user_id', '=', 'NoPrazo.user_id'); $join->on('historico_indic.processo_id', '=', 'NoPrazo.processo_id'); })
            ->leftjoin(DB::raw("(select user_id, 
                                        processo_id,
                                        count(*) as Total 
                                from historico_indic 
                                where data_informada between '" . $request->dataInicial . "'  and  '" .  $request->dataFinal .  "' 
                                and processo_id in (".$textoProc.")       
                                group by user_id, processo_id) as Total"), function($join) {$join->on('historico_indic.user_id', '=', 'Total.user_id'); $join->on('historico_indic.processo_id', '=', 'Total.processo_id'); })                     
            ->select(DB::raw("distinct processos.nome as arg, round(ifnull(NoPrazo.Prazo,0) / ifnull(Total.Total,0) * 100,2)  as val, users.email as parentID, '' as parentID2"))
            ->whereBetween('historico_indic.data_informada', [$request->dataInicial, $request->dataFinal])
            ->where('processos.coordenacao','like',$coordenacaoID)
            ->whereIn('processos.id',$processosSelec)
            ->orderby('arg')
            ->get();

            $data3 = DB::table('historico_indic')
            ->join('processos', 'historico_indic.processo_id', '=', 'processos.id')
            ->join('users', 'users.id', '=', 'historico_indic.user_id')
            ->leftjoin(DB::raw("(select user_id,
                                        processo_id, 
                                        data_informada,
                                        count(*) as Prazo 
                                from historico_indic 
                                where status = 'No Prazo'   
                                and data_informada between '" . $request->dataInicial . "'  and  '" .  $request->dataFinal .  "' 
                                and processo_id in (".$textoProc.")                                                      
                                group by user_id,data_informada,processo_id) as NoPrazo"), function($join) {$join->on('historico_indic.user_id', '=', 'NoPrazo.user_id'); $join->on('historico_indic.data_informada', '=', 'NoPrazo.data_informada'); $join->on('historico_indic.processo_id', '=', 'NoPrazo.processo_id'); })
            ->leftjoin(DB::raw("(select user_id, 
                                        processo_id,
                                        data_informada,
                                        count(*) as Total 
                                from historico_indic 
                                where data_informada between '" . $request->dataInicial . "'  and  '" .  $request->dataFinal .  "'  
                                and processo_id in (".$textoProc.")      
                                group by user_id,data_informada,processo_id) as Total"), function($join) {$join->on('historico_indic.user_id', '=', 'Total.user_id'); $join->on('historico_indic.data_informada', '=', 'Total.data_informada'); $join->on('historico_indic.processo_id', '=', 'Total.processo_id'); })                     
            ->select(DB::raw("distinct historico_indic.data_informada as arg, round(ifnull(NoPrazo.Prazo,0) / ifnull(Total.Total,0) * 100,2)  as val, users.email as parentID, processos.nome as parentID2"))
            ->whereBetween('historico_indic.data_informada', [$request->dataInicial, $request->dataFinal])
            ->where('processos.coordenacao','like',$coordenacaoID)
            ->whereIn('processos.id',$processosSelec)
            ->orderby('arg')
            ->get();

        }
        //dd($data3[0]);
        foreach($data2 as $value){
            $data->push($value);
        }
        foreach($data3 as $value){
            $data->push($value);
        }
      
        //dd($data[1000]);
        return $data;
    }



}
