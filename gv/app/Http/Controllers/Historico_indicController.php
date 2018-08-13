<?php

namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use gv\Http\Requests\Historico_indicRequest;
use Request;
use gv\historico_indic;
use gv\Processo;
use gv\Periodicidade;
use gv\User;
use \Datetime;
use Auth;

class Historico_indicController extends Controller
{
    public function lista(){
        $filtro = null;
        return  view('historico_indic.listagem',compact('filtro'));
    }
    public function filtro(Historico_indicRequest $request,$data=null){
        $user = Auth::user();
        if(!is_null($request->data_inicial)){
            $data_inicial = $request->data_inicial;
            $data_final = $request->data_final;
            $periodicidades = Periodicidade::all();
            $users = User::all();
            $processos = Processo::all();
            $usuario =  Auth::user()->id;
            if($user->can('checkGestor')){
                $userFiltro = '%';
            }else{
                $userFiltro = $usuario;
            }
            /*$historicos = DB::table('historico_indic')
            ->join('users', 'users.id', '=', 'historico_indic.user_id')
            ->join('periodicidades', 'periodicidades.id', '=', 'historico_indic.periodicidade_id')
            ->join('processos', 'processos.id', '=', 'historico_indic.processo_id')
            ->select(DB::raw("historico_indic.id as id, processos.id as processo_id,processos.nome as processo_nome,historico_indic.data_informada as data_informada,
                            users.email as user_id, historico_indic.ultima_data as ultima_data, historico_indic.data_meta as data_meta,
                            periodicidades.nome as periodicidade_id, historico_indic.status as status" ))
            ->where('historico_indic.data_informada','>=',date('Y-m-d', strtotime($request->data_inicial)))
            ->where('historico_indic.data_informada','<=',date('Y-m-d', strtotime($request->data_final)))
            ->where('historico_indic.user_id','like',$userFiltro)
            ->paginate(15);*/
            $historicos = historico_indic::where('historico_indic.data_informada','>=',date('Y-m-d', strtotime($request->data_inicial)))
            ->where('historico_indic.data_informada','<=',date('Y-m-d', strtotime($request->data_final)))
            ->where('historico_indic.user_id','like',$userFiltro)
            ->paginate(15);
            //dd($historicos);
            $historicos->appends(Input::except('page'));
            $filtro = count($historicos);
            return view('historico_indic.listagem',compact('historicos','periodicidades','processos','users','filtro','data_inicial','data_final'));
        }else{
            $filtro = null;
            return  view('historico_indic.listagem',compact('filtro'));
        }
    }

    public function remove($id,$data_inicial=null,$data_final=null){
        $historico = historico_indic::find($id);
        $historico->delete();
        $data=['data_inicial' =>$data_inicial,
               'data_final' => $data_final];
        return redirect()->route('hist.filtro',$data);
    }

    public function salvaAlt(Historico_indicRequest $request){
        $id = $request->id;
        historico_indic::whereId($id)->update($request->except('_token','data_inicial','data_final'));
        $filtro = null;
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        $data=['data_inicial' =>$data_inicial,
               'data_final' => $data_final];
        return redirect()->route('hist.filtro',$data);
    }

    public function adiciona(Historico_indicRequest $request){
        /*Alterar Criacao de Historico por Schedule*/
        $historicos = DB::table('responsavels')
        ->join ('processos','responsavels.id_processo','=','processos.id')
        ->join ('periodicidades','periodicidades.id','=','processos.periodicidade')
        ->join ('users','users.id','=','responsavels.usuario')
        ->join ('tipos','tipos.id','=', 'processos.tipo')
        ->leftjoin (DB::raw('(select  id_processo, data_conciliada from atividades where hora_fim is null) atividades'),function($join){$join->on('atividades.id_processo', '=', 'processos.id');})
        ->leftjoin (DB::raw("(select id_processo, max(data_conciliada) ultima_data from conclusoes where data_conciliada <= '".$request->data_informada."' group by id_processo) conclusoes"),function($join){$join->on('conclusoes.id_processo','=','processos.id');})
        ->select(DB::raw("distinct processos.id as processo_id, '$request->data_informada' data_informada, users.id user_id,
        ultima_data, FLOAT_DIAS_UTEIS('$request->data_informada',periodicidades.dias) data_meta,
        periodicidades.id periodicidade_id,
        (CASE WHEN ultima_data >= FLOAT_DIAS_UTEIS('$request->data_informada',periodicidades.dias) then 'No Prazo' else 'Em Atraso' end) as status
        "))
        ->get();
        foreach ($historicos as $historicos2) {
            
            $id_hist=historico_indic::where('processo_id','=',$historicos2->processo_id)
                                    ->where('data_informada','=',$historicos2->data_informada)->get();   
            if(!$id_hist->count()>0){
                historico_indic::create([
                    'processo_id' => $historicos2->processo_id,
                    'data_informada' => $historicos2->data_informada,
                    'user_id' => $historicos2->user_id,
                    'ultima_data' => $historicos2->ultima_data,
                    'data_meta' => $historicos2->data_meta,
                    'periodicidade_id' => $historicos2->periodicidade_id,
                    'status' => $historicos2->status,
                ]);
            }
         }
            $filtro = null;
            $data_inicial = $request->data_inicial;
            $data_final = $request->data_final;
            $data=['data_inicial' =>$data_inicial,
                   'data_final' => $data_final];
            return redirect()->route('hist.filtro',$data);
    }

}
