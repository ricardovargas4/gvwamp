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
        //$responsavels = Responsavel::all();
        //dd(isset($_GET['page']));
        /*if(isset($_GET['page'])){
            $currentPage = $_GET['page'];
        }else{
            $currentPage = 1;
        }
        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });*/
    //dd( $currentPage);
        $users = User::orderBy('email')->get();
        $processos = Processo::orderBy('nome')->get();
        $resp = DB::table('responsavels')
        ->join('processos', 'responsavels.id_processo', '=', 'processos.id')
        ->join('users', 'users.id', '=', 'responsavels.usuario')
        ->select('responsavels.id','processos.nome as procNome', 'processos.id as id_processo', 'users.email', 'users.id as usuario')
        ->orderBy('processos.nome','ASC')
        ->orderBy('users.email','ASC')
        ->paginate(15);
        //$resp=Responsavel::paginate(15);
        return view('responsavel.listagem',compact('resp','users','processos'));
    }

    public function remove($id){
        $responsavel = Responsavel::find($id);
        $responsavel->delete();
        return redirect()->action('ResponsavelController@lista');
    }

    public function altera($id){
        $responsavel = Responsavel::find($id);
        $users = User::orderBy('email')->get();
        $processos = Processo::orderBy('nome')->get();
        return view('responsavel.formulario_alteracao',compact('responsavel','users','processos'));
    }
    public function salvaAlt(ResponsavelRequest $request){
        //dd($request->page);
        $page = $request->page;
        $id = $request->id;
        Responsavel::whereId($id)->update($request->except('_token','page'));
        //return redirect()->action('ResponsavelController@lista')->withInput(Request::only('page',$page));
        return redirect()->action('ResponsavelController@lista',['page'=>$page]);
    }


    public function novo(){
        $users = User::orderBy('email')->get();
        $processos = Processo::orderBy('nome')->get();
        return view('responsavel.formulario',compact('users','processos'));
    }   

    public function adiciona(ResponsavelRequest $request){
        Responsavel::create($request->all());
        return redirect()->action('ResponsavelController@lista')->withInput(Request::only('usuario'));
    }

}
