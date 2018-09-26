<?php namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
use gv\Processo;
use gv\Classificacao;
use gv\Http\Requests\ClassificacaoRequest;
use Request;

class ClassificacaoController extends Controller
{

    public function lista(){
        $processos = Processo::orderBy('nome')->get();

        $classificacoes = Classificacao::orderBy('opcao')->get();//->paginate(15);
        return view('classificacao.listagem',compact('classificacoes','processos'));
    }
    public function remove($id){
        $classificacao = Classificacao::find($id);
        $classificacao->delete();
        return redirect()->action('ClassificacaoController@lista');
    }
    public function salvaAlt(ClassificacaoRequest $request){
        $id = $request->id;
        Classificacao::whereId($id)->update($request->except('_token'));
        return redirect()->action('ClassificacaoController@lista')->withInput(Request::only('usuario'));
    }
    public function adiciona(ClassificacaoRequest $request){
        Classificacao::create($request->all());
        return redirect()->action('ClassificacaoController@lista')->withInput(Request::only('usuario'));
    }

}
