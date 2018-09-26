<?php namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
use gv\Http\Requests\CoordenacaoRequest;
use Request;
use gv\Coordenacao;
use gv\User;

class CoordenacaoController extends Controller {

    public function lista(){
       // $coordenacaos = Coordenacao::all();
        $users = User::where('nivel','<','3')->orderBy('email')->get();
        $coordenacaos = Coordenacao::orderBy('nome')->get();
        return view('coordenacao.listagem',compact('coordenacaos','users'));
    }

    public function remove($id){
        $coordenacao = Coordenacao::find($id);
        $coordenacao->delete();
        return redirect()->action('CoordenacaoController@lista');
    }

    public function altera($id){
        $coordenacao = Coordenacao::find($id);
        return view('coordenacao.formulario_alteracao',compact('coordenacao'));
    }
    public function salvaAlt(CoordenacaoRequest $request){
        
        $id = $request->id;
        Coordenacao::whereId($id)->update($request->except('_token'));
        return redirect()->action('CoordenacaoController@lista')->withInput(Request::only('nome'));
    }


    public function novo(){
        return view('coordenacao.formulario');
    }   

    public function adiciona(CoordenacaoRequest $request){

        Coordenacao::create($request->all());
        return redirect()->action('CoordenacaoController@lista')->withInput(Request::only('nome'));
    }

}
