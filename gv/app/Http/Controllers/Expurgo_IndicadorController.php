<?php namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use gv\Expurgo_Indicador;
use gv\Historico_indic;
use gv\Http\Requests\Expurgo_IndicadorRequest;
use Request;
use Auth;
use gv\User;

class Expurgo_IndicadorController extends Controller
{
    public function lista(){
        $filtro = null;
        $users = User::orderBy('email')->get();
        return  view('expurgo_indicador.listagem',compact('filtro','users'));
    }
    /*
    public function lista(){
        //$nivel = Auth::user()->nivel;
        $users = User::orderBy('email')->get();
        $user = Auth::user();
        $usuario =  Auth::user()->id;
        if($user->can('checkGestor')){
            $userFiltro = '%';
        }else{
            $userFiltro = $usuario;
        }
        $expurgos = Expurgo_Indicador::orderBy('id')
        ->where('id_usuario_solicitante','like',$userFiltro)
        ->paginate(15);

        return view('expurgo_indicador.listagem',compact('expurgos','nivel','users'));

    }*/

    public function filtro(Expurgo_IndicadorRequest $request,$data=null){
        $user = Auth::user();
        $users = User::orderBy('email')->get();
        if(!is_null($request->data_inicial)){
            $page = $request->page;
            $data_inicial = $request->data_inicial;
            $data_final = $request->data_final;
            $users = User::orderBy('email')->get();
            $user = Auth::user();
            $usuario =  Auth::user()->id;
            if(isset($request->filtroUsuario)){
                $userFiltro = $request->filtroUsuario;
                $filtroUsuario = User::find($userFiltro);
            }else{
                if($user->can('checkGestor')){
                    $userFiltro = '%';
                }else{
                    $userFiltro = $usuario;
                    $filtroUsuario = User::find($userFiltro);
                }
            }
            
            $expurgos = Expurgo_Indicador::orderBy('historico_indic.id')
            ->join ('historico_indic','historico_indic.id','=','expurgo_indicador.id_historico_indic')
            ->where('id_usuario_solicitante','like',$userFiltro)
            ->where('historico_indic.data_informada','>=',$data_inicial)
            ->where('historico_indic.data_informada','<=',$data_final)
            /*->select()*/
            ->paginate(15, ['*'], 'page', $page);
            $expurgos->appends(Input::except('page'));
            $filtro = count($expurgos);
            return view('expurgo_indicador.listagem',compact('expurgos','users','filtro','data_inicial','data_final','filtroUsuario'));
        }else{
            $filtro = null;
            return  view('expurgo_indicador.listagem',compact('filtro','users'));
        }
    }

    public function tela(){
        $nivel = Auth::user()->nivel;
        
        if($nivel==2){
            $expurgos = Expurgo_Indicador::orderBy('id')
            ->where('id_usuario_aprovador',Auth::user()->id)
            ->where('status','1')
            ->paginate(15);
        }elseif($nivel==1){
            $expurgos = Expurgo_Indicador::orderBy('id')
            ->paginate(15);
        }else{            
            $expurgos = Expurgo_Indicador::orderBy('id')
            ->where('id_usuario_solicitante',Auth::user()->id)
            ->where('status','1')
            ->paginate(15);
        }
        
        return view('expurgo_indicador.tela',compact('expurgos','nivel'));

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

        if(isset($request->page)){
            $page=$request->page;
        }else{
            $page=1;
        }
        $filtroUsuario = User::find($request->filtroUsuario);
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        $data=['data_inicial' =>$data_inicial,
               'data_final' => $data_final,
               'filtroUsuario' => $filtroUsuario,
               'page' => $page];
        


        $gestor = Historico_indic::where('historico_indic.id','=',$request->id_historico_indic)
        ->first()->processo_id_FK->coordenacao_FK->id_gestor;
        $request->offsetSet('id_usuario_solicitante',Auth::user()->id);
        $request->offsetSet('id_usuario_aprovador',$gestor);
        $request->offsetSet('comentario',nl2br($request->comentario));
        $request->request->set('STATUS', 1);
        Expurgo_Indicador::create($request->except('_token','filtroUsuario','data_inicial','data_final'));
        //return redirect()->action('Expurgo_IndicadorController@tela')->withInput(Request::only('usuario'));
        return redirect()->route('historico.filtro',$data);
    }
    public function aprovar(Expurgo_IndicadorRequest $request){
        $idExpurgo = $request->id;
        $idHistorico = $request->id_historico_indic;
        $historico = Historico_indic::find($idHistorico);
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
