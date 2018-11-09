<?php

namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
use gv\Http\Requests\UsuarioRequest;
use Request;
use gv\User;

class UserController extends Controller
{
    public function lista(){
        $usuarios = User::orderBy('email')->get();
        return view('usuario.listagem')->with('usuarios', $usuarios);
    }

    public function remove($id){
        $usuario = User::find($id);
        $usuario->delete();
        return redirect()->action('UserController@lista');
    }

    public function salvaAlt(UsuarioRequest $request){
        //dd($request)
        $id = $request->id;
        $usuario = User::find($id);
        /*if($usuario->LDAP == 'N'){
            $request->offsetSet('password',bcrypt($request->password));  
        }*/
        User::whereId($id)->update($request->except('_token'));
        return redirect()->action('UserController@lista')->withInput(Request::only('nome'));
    }

    public function adiciona(UsuarioRequest $request){
       // $request->offsetSet('password',bcrypt($request->password));
        /*if($request->LDAP == 'N'){
            $request->offsetSet('password',bcrypt($request->password));  
        }*/
        User::create($request->all());
        return redirect()->action('UserController@lista')->withInput(Request::only('nome'));
    }

}
