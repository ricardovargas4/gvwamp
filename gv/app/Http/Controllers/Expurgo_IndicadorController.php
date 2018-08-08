<?php namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
use gv\Expurgo_Indicador;
use gv\Http\Requests\Expurgo_IndicadorRequest;
use Request;

class Expurgo_IndicadorController extends Controller
{

    public function lista(){
        $expurgos = Expurgo_Indicador::all();//->paginate(15);
        return view('expurgo_indicador.listagem',compact('expurgos'));
        
    }
    public function remove($id){
        $expurgos = Expurgo_Indicador::find($id);
        $expurgos->delete();
        return redirect()->action('Expurgo_IndicadorController@lista');
    }
    public function salvaAlt(Expurgo_IndicadorRequest $request){
        $id = $request->id;
        Expurgo_Indicador::whereId($id)->update($request->except('_token'));
        return redirect()->action('Expurgo_IndicadorController@lista')->withInput(Request::only('usuario'));
    }
    public function adiciona(Expurgo_IndicadorRequest $request){
        //dd($request);
        $request->request->set('STATUS', 1);
        Expurgo_Indicador::create($request->except('_token'));
        return redirect()->action('Expurgo_IndicadorController@lista')->withInput(Request::only('usuario'));
    }

}
