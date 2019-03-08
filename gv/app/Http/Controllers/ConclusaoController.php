<?php

namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use gv\Http\Requests\ConclusaoRequest;
use Request;
use gv\Historico_indic;
use gv\Processo;
use gv\User;
use \Datetime;
use Auth;
use Excel;
use Illuminate\Pagination\Paginator;
use gv\Expurgo_Indicador;
use gv\Conclusao;

class ConclusaoController extends Controller
{
    public function lista(){
        $filtro = null;
        $processos = Processo::where('tipo','=','3')
        ->orderBy('nome')->get();
        return  view('conclusao.listagem',compact('filtro','processos'));
    }
    public function filtro(ConclusaoRequest $request,$data=null){
        //dd($request);
        $user = Auth::user();
        if(!is_null($request->data_inicial)){
            $data_inicial = $request->data_inicial;
            $data_final = $request->data_final;
            $processos = Processo::where('tipo','=','3')
            ->orderBy('nome')->get();
            $usuario =  Auth::user()->id;
            
            if(isset($request->filtroProcesso)){
                $processoFiltro = $request->filtroProcesso;
                $filtroProcesso = Processo::find($processoFiltro);
            }else{
                $processoFiltro = '%';
            }

            if(isset($request->page)){
                $page=$request->page;
            }else{
                $page=1;
            }
            
            $conclusoes = Conclusao::leftJoin('processos','processos.id','=','conclusoes.id_processo')
            ->where('conclusoes.data_conciliacao','>=',date('Y-m-d', strtotime($request->data_inicial)))
            ->where('conclusoes.data_conciliacao','<=',date('Y-m-d', strtotime($request->data_final)))
            ->where('conclusoes.id_processo','like',$processoFiltro)
            ->select('conclusoes.*')
            ->orderBy('processos.nome')
            ->orderBy('conclusoes.data_conciliacao')
            ->orderBy('conclusoes.data_conciliada')
            ->paginate(15, ['*'], 'page', $page);
            
            $conclusoes->appends(Input::except('page'));
            $filtro = count($conclusoes);
            return view('conclusao.listagem',compact('conclusoes','processos','filtro','data_inicial','data_final','filtroProcesso'));
        }else{
            $filtro = null;
            $processos = Processo::orderBy('nome')->get();
            return  view('conclusao.listagem',compact('filtro','processos'));
        }
    }

    public function remove($id,$data_inicial=null,$data_final=null,$filtroProcesso=null,$page=null){
        $conclusao = Conclusao::find($id);
        $filtroProcesso = Processo::find($filtroProcesso);
        $conclusao->delete();
        $data=['data_inicial' =>$data_inicial,
               'data_final' => $data_final,
               'filtroProcesso' => $filtroProcesso,
               'page' => $page,];
        return redirect()->route('conclusao.filtro',$data);
    }

    public function salvaAlt(ConclusaoRequest $request){
        $id = $request->id;
        if(isset($request->page)){
            $page=$request->page;
        }else{
            $page=1;
        }
        Conclusao::whereId($id)->update($request->except('_token','data_inicial','data_final','page','filtroProcesso'));
        $filtro = null;
        $filtroProcesso = Processo::find($request->filtroProcesso);
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        $data=['data_inicial' =>$data_inicial,
               'data_final' => $data_final,
               'filtroProcesso' => $filtroProcesso,
               'page' => $page];
        return redirect()->route('conclusao.filtro',$data);
    }

    public function adiciona(ConclusaoRequest $request){
        $conclusao = Conclusao::create($request->except('_token','data_inicial','data_final','page','filtroProcesso'));
        $filtroProcesso = Processo::find($request->filtroProcesso);
        $data=['data_inicial' => $request->data_inicial,
        'data_final' => $request->data_final,
        'filtroProcesso' => $filtroProcesso,
        'page' => $request->page];
        return redirect()->route('conclusao.filtro',$data);  
    }

}
