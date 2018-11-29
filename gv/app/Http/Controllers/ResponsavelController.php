<?php namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
use gv\Processo;
use gv\Http\Requests\ResponsavelRequest;
use Request;
use gv\Responsavel;
use gv\User;


class ResponsavelController extends Controller
{

    public function lista(){
        $filtro = null;
        $users = User::orderBy('email')->get();
        $processos = Processo::orderBy('nome')->get();
        return  view('responsavel.listagem',compact('filtro','users','processos'));
    }
    
    public function filtro(ResponsavelRequest $request,$filtroId_processo=null,$filtroUsuario=null){
        //dd($request->filtroId_processo->attributes);

        if(isset($request->filtroId_processo)){
            $filtroId_processo = json_decode($request->filtroId_processo);
            if(!isset($filtroId_processo->id)){
                $filtroId_processo = Processo::find($filtroId_processo); 
            }else{
                $filtroId_processo = Processo::find($filtroId_processo->id); 
            }
            $filtroProcContr = $filtroId_processo->id;
        }else{
            $filtroProcContr = '%';
        }

        if(isset($request->filtroUsuario)){
            $filtroUsuario = json_decode($request->filtroUsuario);
            if(!isset($filtroUsuario->id)){
                $filtroUsuario = User::find($filtroUsuario);
            }else{
                $filtroUsuario = User::find($filtroUsuario->id); 
            }
            $filtroUsuContr = $filtroUsuario->id;
        }else{
            $filtroUsuContr = '%';
        }
   
        if(isset($filtroId_processo) || isset($filtroUsuario)){

            $resp = DB::table('responsavels')
            ->join('processos', 'responsavels.id_processo', '=', 'processos.id')
            ->join('users', 'users.id', '=', 'responsavels.usuario')
            ->select('responsavels.id','processos.nome as procNome', 'processos.id as id_processo', 'users.email', 'users.id as usuario')
            ->where('responsavels.id_processo','like',$filtroProcContr)
            ->where('responsavels.usuario','like',$filtroUsuContr)
            ->orderBy('processos.nome','ASC')
            ->orderBy('users.email','ASC')
            ->get();
//dd($resp);
            //->paginate(15,['*'],'page',$page);
            //$resp=Responsavel::paginate(15);
            $filtro = count($resp);
        }else{
            $filtro = null;
            $resp = null;
        }

        $users = User::orderBy('email')->get();
        $processos = Processo::orderBy('nome')->get();

            return view('responsavel.listagem',compact('resp','users','processos','filtroId_processo','filtroUsuario','filtro'));
        }

    public function remove(ResponsavelRequest $request){
       
        $id = $request->id;
        $responsavel = Responsavel::find($id);
        $responsavel->delete();
      //  return redirect()->action('ResponsavelController@lista',['page'=>$page]);
        //return redirect()->action('ResponsavelController.listaPost','filtroId_processo','filtroUsuario');
        $filtroId_processo = $request->filtroId_processo;
        $filtroUsuario = $request->filtroUsuario;
        $data=['filtroId_processo' =>$filtroId_processo ,
               'filtroUsuario' =>  $filtroUsuario];
        return redirect()->route('responsavel.filtro',$data);
    }

    public function salvaAlt(ResponsavelRequest $request){
        //dd($request->page);
        $id = $request->id;
        Responsavel::whereId($id)->update($request->except('_token','filtroId_processo','filtroUsuario'));
        //return redirect()->action('ResponsavelController@lista')->withInput(Request::only('page',$page));
        //return redirect()->action('ResponsavelController@lista',['page'=>$page]);
        $filtroId_processo = $request->filtroId_processo;
        $filtroUsuario = $request->filtroUsuario;
        $data=['filtroId_processo' =>$filtroId_processo ,
               'filtroUsuario' =>  $filtroUsuario];
        return redirect()->route('responsavel.filtro',$data);

    }

    public function adiciona(ResponsavelRequest $request){
        Responsavel::create($request->all());
        //return redirect()->action('ResponsavelController@lista')->withInput(Request::only('usuario'));
        $filtroId_processo = $request->filtroId_processo;
        $filtroUsuario = $request->filtroUsuario;
        $data=['filtroId_processo' =>$filtroId_processo ,
               'filtroUsuario' =>  $filtroUsuario];
        return redirect()->route('responsavel.filtro',$data);
    }

}
