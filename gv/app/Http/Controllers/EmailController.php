<?php namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Request;
use gv\User;
use gv\historico_indic;
use gv\LogResp;

class EmailController extends Controller
{

    public function envioAtividadesAcimaDoTempo(){
        $data = DB::table('atividades')
        ->select(DB::raw('distinct usuario'))
        ->groupBy('usuario', 'data_conciliacao')
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
                if ($user->nivel==4){
                    $message->to($user->email."@terceiros.sicredi.com.br", $user->nome)->subject('Atividades Com Tempo Excessivo');    
                }else{
                    $message->to($user->email."@sicredi.com.br", $user->nome)->subject('Atividades Com Tempo Excessivo');
                }
                // Set the sender
                $message->from('noreply@sicredi.com.br', 'No-Reply');
            }); 
        }
    }

    public function envioIndicadorAtrasado(){
        $data_informada = DB::table(DB::raw('DUAL'))->select(DB::raw("FLOAT_DIAS_UTEIS(now(),-1) data"))->first([DB::raw(1)]);
        $data = DB::table('historico_indic')
            ->where('data_informada','=',$data_informada->data)
            ->where('status','=','Em Atraso')
            ->select(DB::raw('distinct user_id'))
            ->groupBy('user_id')
            ->get();

        $texto = "";
        foreach ($data as $usuario) {
            $user = User::find($usuario->user_id);
           
            $gestor= DB::table('responsavels')
                ->join('processos', 'responsavels.id_processo', '=', 'processos.id')
                ->join('coordenacaos','processos.coordenacao','=','coordenacaos.id')
                ->join('users', 'users.id', '=', 'coordenacaos.id_gestor')
                ->select(DB::RAW('users.email,users.name'))
                ->where('responsavels.usuario', $user->id)
                ->first();

            $data = historico_indic::where('user_id','=',$user->id)
                ->where('data_informada','=',$data_informada->data)
                ->where('status','=','Em Atraso')
                ->get();

            $template_path = 'emails.email_template_indicador';
            Mail::send(['html'=> $template_path ], ['dados' => $data], function($message) use ($user) {
                // Set the receiver and subject of the mail.
                if ($user->nivel==4){
                    $message->to($user->email."@terceiros.sicredi.com.br", $user->nome)->subject('Atividades Com Indicador Atrasado');    
                }else{
                    $message->to($user->email."@sicredi.com.br", $user->nome)->subject('Atividades Com Indicador Atrasado');
                }
                $message->cc($gestor->email."@sicredi.com.br", $gestor->name);
                // Set the sender
                $message->from('noreply@sicredi.com.br', 'No-Reply');
            }); 
        }
    }

    public function envioAlteracaoResp(){
        $data_fim = DB::table(DB::raw('DUAL'))->select(DB::raw("DATE(now()) data"))->first([DB::raw(1)]);
        $data_fim= json_decode( json_encode($data_fim), true);
        $data_incio = DB::table(DB::raw('DUAL'))->select(DB::raw("FLOAT_DIAS_UTEIS(now(),-1) data"))->first([DB::raw(1)]);
        $data_incio= json_decode( json_encode($data_incio), true);
        $data = LogResp::where('data_alteracao','<',$data_fim)
        ->where('DATA_ALTERACAO','>=',$data_incio)
        ->select('usuario')
        ->distinct('usuario')
        ->get();
        //->toSql();
        //$data= json_decode( json_encode($data), true);
        $texto = "";
        foreach ($data as $usuario) {
            //dd($ativ);
            $user = User::find($usuario->usuario);
            
            $data = LogResp::where('data_alteracao','<',$data_fim)
                ->where('DATA_ALTERACAO','>=',$data_incio)
                ->where('usuario','=',$usuario->usuario)
                ->get();   
            $template_path = 'emails.email_template_resp';
            Mail::send(['html'=> $template_path ], ['dados' => $data], function($message) use ($user) {
                // Set the receiver and subject of the mail.
                if ($user->nivel==4){
                    $message->to($user->email."@terceiros.sicredi.com.br", $user->nome)->subject('Alteração de Responsabilidades');    
                }else{
                    $message->to($user->email."@sicredi.com.br", $user->nome)->subject('Alteração de Responsabilidades');
                }
                // Set the sender
                $message->from('noreply@sicredi.com.br', 'No-Reply');
            }); 
        }
    }
}
