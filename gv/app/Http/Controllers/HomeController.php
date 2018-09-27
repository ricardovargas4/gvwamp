<?php

namespace gv\Http\Controllers;
use View;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Http\Request;
use gv\Http\Requests\TempoRequest;
use gv\Coordenacao;
use gv\Processo;

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
        return view('chartjs',compact('data_inicial','data_final','coordenacao','coordenacaos','processos','filtroProc'));
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
