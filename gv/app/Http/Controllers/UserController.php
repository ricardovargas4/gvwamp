<?php

namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
use gv\Http\Requests\UsuarioRequest;
use Request;
use gv\User;
use Excel;

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


    public function RelatorioUsuarios() {

        $dados = User::all();
        //$dados= json_decode( json_encode($dados), true);
        $tam = count($dados) + 1;
        
        Excel::create('Relatório Usuários', function($excel) use ($dados, $tam) {
    
            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Relatório GV');
            $excel->setCreator('Gestão à Vista')->setCompany('Confederação Sicredi');
            

            // Build the spreadsheet, data in the data array
            $excel->sheet('Relatório', function($sheet) use ($dados, $tam) {
                $sheet->fromArray($dados);
                $sheet->setStyle([
                    'borders' => [
                        'allborders' => [
                            'color' => [
                                'rgb' => '#000000'
                            ]
                        ]
                    ]
                ]);
                $sheet->row(1, function($row) {
                    // call cell manipulation methods
                    $row->setBackground('#808080');
                    $row->setFontWeight('bold');
                    //$row->setBorder('solid','solid','solid','solid');

                });
                //$sheet->setAllBorders('thin');
                $sheet->setBorder('A1:L'.$tam, 'thin');
                //$sheet->setAutoFilter();
                $sheet->setAutoSize(true);

                
                //dd($sheet);
            });
            
        })->download('xls');

        return true;
    }


}
