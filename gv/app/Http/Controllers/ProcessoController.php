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

        $tipos = Tipo::all();
        $periodicidades = Periodicidade::all();
        $coordenacaos =  Coordenacao::all();
        $processos= processo::paginate(15);
        
        return view('processo.listagem',compact('processos','tipos','periodicidades','coordenacaos'));
    }

    public function remove($id){
        $processo = Processo::find($id);
        $processo->delete();
        return redirect()->action('ProcessoController@lista');
    }

    public function altera($id){
        $processo = Processo::find($id);
        $tipos = Tipo::all();
        $periodicidades = Periodicidade::all();
        $coordenacaos =  Coordenacao::all();
        return view('processo.formulario_alteracao',compact('processo','tipos','periodicidades','coordenacaos'));
    }
    public function salvaAlt(ProcessosRequest $request){
        
        $id = $request->id;
        Processo::whereId($id)->update($request->except('_token'));
        return redirect()->action('ProcessoController@lista')->withInput(Request::only('nome'));
    }


    public function novo(){

        $tipos = Tipo::all();
        $periodicidades = Periodicidade::all();
        $coordenacaos =  Coordenacao::all();

        return view('processo.formulario',compact('tipos','periodicidades','coordenacaos'));
    }   

    public function adiciona(ProcessosRequest $request){

        Processo::create($request->all());
        return redirect()->action('ProcessoController@lista')->withInput(Request::only('nome'));
    }

}
