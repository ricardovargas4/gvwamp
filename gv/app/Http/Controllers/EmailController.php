<?php namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Request;
use gv\User;

class EmailController extends Controller
{

    public function envioAtividadesAcimaDoTempo(){
        $data = DB::table('atividades')
        ->select(DB::raw('distinct usuario'))
        ->groupBy('id','usuario', 'data_conciliacao')
        ->havingRaw('sum(TIMESTAMPDIFF(second,hora_inicio,hora_fim)/3600) > 10')
        ->get();

        $texto = "";
        foreach ($data as $usuario) {
            //dd($ativ);
            $user = User::find($usuario->usuario);
            
            $gestor= DB::table('responsavels')
            ->join('processos', 'responsavels.id_processo', '=', 'processos.id')
            ->join('coordenacaos','processos.coordenacao','=','coordenacaos.id')
            ->join('users', 'users.id', '=', 'coordenacaos.id_gestor')
            ->select(DB::RAW('users.email,users.name'))
            ->where('responsavels.usuario', User::find($usuario->usuario)->id)
            ->first();

            $data = DB::table('atividades')
                ->join('users', 'users.id', '=', 'atividades.usuario')
                ->select(DB::raw("users.email, DATE_FORMAT(atividades.data_conciliacao,'%d/%m/%Y') as data_conciliacao, round(sum(TIMESTAMPDIFF(second,atividades.hora_inicio,atividades.hora_fim)/3600),2) as horas"))
                ->where('usuario','=',User::find($usuario->usuario)->id)
                ->groupBy('users.email','data_conciliacao')
                ->havingRaw('sum(TIMESTAMPDIFF(second,hora_inicio,hora_fim)/3600) > 10')
                ->get();

            $template_path = 'emails.email_template';
            Mail::send(['html'=> $template_path ], ['dados' => $data], function($message) use ($user) {
                // Set the receiver and subject of the mail.
                $message->to($user->email."@sicredi.com.br", $user->nome)->subject('Atividades Com Tempo Excessivo');
                //$message->cc($gestor->email."@sicredi.com.br", $gestor->name);
                // Set the sender
                $message->from('noreply@sicredi.com.br', 'No-Reply');
            }); 
           
        }
        
    }
}
