<?php namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
use gv\Processo;
use gv\Http\Requests\DemandaRequest;
use Request;
use gv\Demanda;
use gv\User;
use Auth;


class DemandaController extends Controller
{

    public function lista(){

        $user = Auth::user();
        $usuario =  Auth::user()->id;
        if($user->can('checkGestor')){
            $userFiltro = '%';
        }else{
            $userFiltro = $usuario;
        }

        $processos = Processo::where('tipo','=','4')->orderBy('nome')->get();
        $usuarios = User::orderBy('email')->get();
        $demandas = Demanda::where('id_responsavel','like',$userFiltro)
                ->paginate(15);
        return view('demanda.listagem',compact('demandas','processos','usuarios'));
    }

    public function remove($id){
        $demanda = Demanda::find($id);
        $demanda->delete();
        return redirect()->action('DemandaController@lista');
    }

    public function altera($id){
        $demanda = Demanda::find($id);
        $users = User::orderBy('email')->get();
        $processos = Processo::where('id','=','4')->orderBy('nome')->get();
        return view('demanda.formulario_alteracao',compact('demanda','users','processos'));
    }

    public function salvaAlt(DemandaRequest $request){
        $id = $request->id;
        Demanda::whereId($id)->update($request->except('_token'));
        return redirect()->action('DemandaController@lista')->withInput(Request::only('usuario'));
    }

    public function adiciona(DemandaRequest $request){
        Demanda::create($request->all());
        return redirect()->action('DemandaController@lista')->withInput(Request::only('usuario'));
    }

}
