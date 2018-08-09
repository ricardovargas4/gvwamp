<?php namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
use gv\Expurgo_Indicador;
use gv\historico_indic;
use gv\Http\Requests\Expurgo_IndicadorRequest;
use Request;
use Auth;

class Expurgo_IndicadorController extends Controller
{

    public function tela(){
        $nivel = Auth::user()->nivel;
        
        if($nivel==2){
            $expurgos = Expurgo_Indicador::orderBy('id')
            ->where('id_usuario_aprovador',Auth::user()->id)
            ->where('status','1')
            ->get();
        }elseif($nivel==1){
            $expurgos = Expurgo_Indicador::orderBy('id')
            ->get();
        }else{            
            $expurgos = Expurgo_Indicador::orderBy('id')
            ->where('id_usuario_solicitante',Auth::user()->id)
            ->where('status','1')
            ->get();
        }
        
        return view('expurgo_indicador.tela',compact('expurgos','nivel'));

    }

    public function lista(){
        //$nivel = Auth::user()->nivel;
        
        $expurgos = Expurgo_Indicador::orderBy('id')
        ->paginate(15);
       // $atividades->appends(Input::except('page'));

        
        return view('expurgo_indicador.listagem',compact('expurgos','nivel'));

    }
    public function remove($id){
        $expurgos = Expurgo_Indicador::find($id);
        $expurgos->delete();
        return redirect()->action('Expurgo_IndicadorController@tela');
    }
    public function salvaAlt(Expurgo_IndicadorRequest $request){
        $id = $request->id;
        Expurgo_Indicador::whereId($id)->update($request->except('_token'));
        return redirect()->action('Expurgo_IndicadorController@tela')->withInput(Request::only('usuario'));
    }
    public function adiciona(Expurgo_IndicadorRequest $request){
        $gestor = DB::table('historico_indic')
        ->join('processos','historico_indic.processo_id','=','processos.id')
        ->join('coordenacaos','processos.coordenacao','=','coordenacaos.id')
        ->where('historico_indic.id','=',$request->id_historico_indic)
        ->select('id_gestor')
        ->first();
        $request->offsetSet('id_usuario_solicitante',Auth::user()->id);
        $request->offsetSet('id_usuario_aprovador',$gestor->id_gestor);
        $request->offsetSet('comentario',nl2br($request->comentario));
        $request->request->set('STATUS', 1);
        Expurgo_Indicador::create($request->except('_token'));
        return redirect()->action('Expurgo_IndicadorController@tela')->withInput(Request::only('usuario'));
    }
    public function aprovar(Expurgo_IndicadorRequest $request){
        $idExpurgo = $request->id;
        $idHistorico = $request->id_historico_indic;
        $historico = historico_indic::find($idHistorico);
        $historico->status = 'No Prazo';
        $historico->save();

        $Expurgo = Expurgo_Indicador::find($idExpurgo);
        $Expurgo->status = '2';
        $Expurgo->justificativa = nl2br($request->justificativa);
        $Expurgo->save();
        
        return redirect()->action('Expurgo_IndicadorController@tela')->withInput(Request::only('usuario'));
    }
    public function reprovar(Expurgo_IndicadorRequest $request){
        $idExpurgo = $request->id;
        $Expurgo = Expurgo_Indicador::find($idExpurgo);
        $Expurgo->status = '3';
        $Expurgo->justificativa = nl2br($request->justificativa);
        $Expurgo->save();

        
        return redirect()->action('Expurgo_IndicadorController@tela')->withInput(Request::only('usuario'));
    }
}
