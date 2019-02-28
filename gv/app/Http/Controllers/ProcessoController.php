<?php namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use gv\Processo;
use gv\Http\Requests\ProcessosRequest;
use Request;
use gv\Tipo;
use gv\Periodicidade;
use gv\Coordenacao;

class ProcessoController extends Controller {

    public function lista(){
        $tipos = Tipo::orderBy('nome')->get();
        $periodicidades = Periodicidade::orderBy('nome')->get();
        $coordenacaos =  Coordenacao::orderBy('nome')->get();
        $processosLista= Processo::orderBy('nome')->get();
        $processos= Processo::orderBy('nome')->paginate(15);
        
        return view('processo.listagem',compact('processos','tipos','periodicidades','coordenacaos','processosLista'));
    }
    public function filtro(ProcessosRequest $request,$data=null,$filtroProcesso=null,$filtroTipo=null,$filtroCoordenacao=null){
        //if(!is_null($request->data_inicial)){
            if(isset($request->filtroProcesso)){
                $filtroProcesso = json_decode($request->filtroProcesso);
                if(!isset($filtroProcesso->id)){
                    $filtroProcesso = Processo::find($filtroProcesso); 
                }else{
                    $filtroProcesso = Processo::find($filtroProcesso->id); 
                }
                $filtroProcQ = $filtroProcesso->id;
            }else{
                $filtroProcQ = '%';
            }
    
            if(isset($request->filtroTipo)){
                $filtroTipo = json_decode($request->filtroTipo);
                if(!isset($filtroTipo->id)){
                    $filtroTipo = Tipo::find($filtroTipo); 
                }else{
                    $filtroTipo = Tipo::find($filtroTipo->id); 
                }
                $filtroTipoQ = $filtroTipo->id;
            }else{
                $filtroTipoQ = '%';
            }

            if(isset($request->filtroCoordenacao)){
                $filtroCoordenacao = json_decode($request->filtroCoordenacao);
                if(!isset($filtroCoordenacao->id)){
                    $filtroCoordenacao = Coordenacao::find($filtroCoordenacao); 
                }else{
                    $filtroCoordenacao = Coordenacao::find($filtroCoordenacao->id); 
                }
                $filtroCoordenacaoQ = $filtroCoordenacao->id;
            }else{
                $filtroCoordenacaoQ = '%';
            }

            $tipos = Tipo::orderBy('nome')->get();
            $periodicidades = Periodicidade::orderBy('nome')->get();
            $coordenacaos =  Coordenacao::orderBy('nome')->get();
            $processosLista= Processo::orderBy('nome')->get();
           
            if(isset($request->page)){
                $page=$request->page;
            }else{
                $page=1;
            }
            $processos = Processo::where('processos.id','like',$filtroProcQ)
            ->where('processos.tipo','like',$filtroTipoQ)
            ->where('processos.coordenacao','like',$filtroCoordenacaoQ)
            ->orderBy('nome')
            ->paginate(15, ['*'], 'page', $page);

            $processos->appends(Input::except('page'));
           // $filtro = count($historicos);
            return view('processo.listagem',compact('processos','tipos','periodicidades','coordenacaos','processosLista','filtroProcesso','filtroTipo','filtroCoordenacao'));
    }

    public function remove(ProcessosRequest $request){
        $id = $request->id;
        $processo = Processo::find($id);
        $processo->delete();
        $data=['filtroProcesso' =>$request->filtroProcesso ,
               'filtroTipo' =>  $request->filtroTipo,
               'filtroCoordenacao' =>  $request->filtroCoordenacao,
               'page' =>  $request->page];
        return redirect()->route('processo.filtro',$data);
    }

    public function altera($id){
        $processo = Processo::find($id);
        $tipos = Tipo::orderBy('nome')->get();
        $periodicidades = Periodicidade::orderBy('nome')->get();
        $coordenacaos =  Coordenacao::orderBy('nome')->get();
        return view('processo.formulario_alteracao',compact('processo','tipos','periodicidades','coordenacaos'));
    }
    public function salvaAlt(ProcessosRequest $request){
        $page = $request->page;
        $id = $request->id;
        Processo::whereId($id)->update($request->except('_token','page','filtroProcesso','filtroTipo','filtroCoordenacao'));
        $filtroProcesso = $request->filtroProcesso;
        $filtroTipo = $request->filtroTipo;
        $filtroCoordenacao = $request->filtroCoordenacao;
        $data=['filtroProcesso' =>$filtroProcesso ,
               'filtroTipo' =>  $filtroTipo,
               'filtroCoordenacao' =>  $filtroCoordenacao,
               'page' =>  $page];
        return redirect()->route('processo.filtro',$data);
    }


    public function novo(){

        $tipos = Tipo::orderBy('nome')->get();
        $periodicidades = Periodicidade::orderBy('nome')->get();
        $coordenacaos =  Coordenacao::orderBy('nome')->get();

        return view('processo.formulario',compact('tipos','periodicidades','coordenacaos'));
    }   

    public function adiciona(ProcessosRequest $request){

        Processo::create($request->all());
        return redirect()->action('ProcessoController@lista')->withInput(Request::only('nome'));
    }

}
