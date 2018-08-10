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

        $processos = Processo::all();
        $usuarios = User::all();
        $demandas = DB::table('demandas')
        ->join('processos', 'demandas.id_processo', '=', 'processos.id')
        ->join('users', 'demandas.id_responsavel', '=', 'users.id')
        ->where('id_responsavel','like',$userFiltro)
        ->select('demandas.id','processos.nome as procNome', 'processos.id as procID', 'demandas.data_final',
                 'users.id as userID', 'users.email','data_conclusao')
        ->paginate(15);;
        return view('demanda.listagem',compact('demandas','processos','usuarios'));
    }

    public function remove($id){
        $demanda = Demanda::find($id);
        $demanda->delete();
        return redirect()->action('DemandaController@lista');
    }

    public function altera($id){
        $demanda = Demanda::find($id);
        $users = User::all();
        $processos = Processo::all();
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
