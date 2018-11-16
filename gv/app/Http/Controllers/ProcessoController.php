<?php namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
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
        $processos= Processo::orderBy('nome')->paginate(15);
        
        return view('processo.listagem',compact('processos','tipos','periodicidades','coordenacaos'));
    }

    public function remove($id,$page){
        $processo = Processo::find($id);
        $processo->delete();
        return redirect()->action('ProcessoController@lista',['page'=>$page]);
        //return redirect()->action('ProcessoController@lista');
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
        Processo::whereId($id)->update($request->except('_token','page'));
        return redirect()->action('ProcessoController@lista',['page'=>$page]);
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
