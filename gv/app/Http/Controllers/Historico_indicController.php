<?php

namespace gv\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use gv\Http\Requests\Historico_indicRequest;
use Request;
use gv\Historico_indic;
use gv\Processo;
use gv\Periodicidade;
use gv\User;
use \Datetime;
use Auth;
use Excel;

class Historico_indicController extends Controller
{
    public function lista(){
        $filtro = null;
        return  view('historico_indic.listagem',compact('filtro'));
    }
    public function filtro(Historico_indicRequest $request,$data=null){
        $user = Auth::user();
        if(!is_null($request->data_inicial)){
            $data_inicial = $request->data_inicial;
            $data_final = $request->data_final;
            $periodicidades = Periodicidade::orderBy('nome')->get();
            $users = User::orderBy('email')->get();
            $processos = Processo::orderBy('nome')->get();
            $usuario =  Auth::user()->id;
            if($user->can('checkGestor')){
                $userFiltro = '%';
            }else{
                $userFiltro = $usuario;
            }
            $historicos = Historico_indic::where('historico_indic.data_informada','>=',date('Y-m-d', strtotime($request->data_inicial)))
            ->where('historico_indic.data_informada','<=',date('Y-m-d', strtotime($request->data_final)))
            ->where('historico_indic.user_id','like',$userFiltro)
            ->paginate(15);
            $historicos->appends(Input::except('page'));
            $filtro = count($historicos);
            return view('historico_indic.listagem',compact('historicos','periodicidades','processos','users','filtro','data_inicial','data_final'));
        }else{
            $filtro = null;
            return  view('historico_indic.listagem',compact('filtro'));
        }
    }

    public function remove($id,$data_inicial=null,$data_final=null){
        $historico = Historico_indic::find($id);
        $historico->delete();
        $data=['data_inicial' =>$data_inicial,
               'data_final' => $data_final];
        return redirect()->route('hist.filtro',$data);
    }

    public function salvaAlt(Historico_indicRequest $request){
        $id = $request->id;
        Historico_indic::whereId($id)->update($request->except('_token','data_inicial','data_final'));
        $filtro = null;
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        $data=['data_inicial' =>$data_inicial,
               'data_final' => $data_final];
        return redirect()->route('hist.filtro',$data);
    }

    public function adiciona(Historico_indicRequest $request){
        /*Alterar Criacao de Historico por Schedule*/
        $historicos = DB::table('responsavels')
        ->join ('processos','responsavels.id_processo','=','processos.id')
        ->join ('periodicidades','periodicidades.id','=','processos.periodicidade')
        ->join ('users','users.id','=','responsavels.usuario')
        ->join ('tipos','tipos.id','=', 'processos.tipo')
        ->leftjoin (DB::raw('(select  id_processo, data_conciliada from atividades where hora_fim is null) atividades'),function($join){$join->on('atividades.id_processo', '=', 'processos.id');})
        ->leftjoin (DB::raw("(select id_processo, max(data_conciliada) ultima_data from conclusoes where data_conciliacao <= '".$request->data_informada."' group by id_processo) conclusoes"),function($join){$join->on('conclusoes.id_processo','=','processos.id');})
        ->select(DB::raw("distinct processos.id as processo_id, '$request->data_informada' data_informada, users.id user_id,
        ultima_data, FLOAT_DIAS_UTEIS('$request->data_informada',periodicidades.dias) data_meta,
        periodicidades.id periodicidade_id,
        (CASE WHEN ultima_data >= FLOAT_DIAS_UTEIS('$request->data_informada',periodicidades.dias) then 'No Prazo' else 'Em Atraso' end) as status
        "))
        ->where('tipos.id','=','3')
        ->get();
        foreach ($historicos as $historicos2) {
            
            $id_hist=Historico_indic::where('processo_id','=',$historicos2->processo_id)
                                    ->where('data_informada','=',$historicos2->data_informada)->get();   
            if(!$id_hist->count()>0){
                Historico_indic::create([
                    'processo_id' => $historicos2->processo_id,
                    'data_informada' => $historicos2->data_informada,
                    'user_id' => $historicos2->user_id,
                    'ultima_data' => $historicos2->ultima_data,
                    'data_meta' => $historicos2->data_meta,
                    'periodicidade_id' => $historicos2->periodicidade_id,
                    'status' => $historicos2->status,
                ]);
            }
         }
            $filtro = null;
            $data_inicial = $request->data_inicial;
            $data_final = $request->data_final;
            $data=['data_inicial' =>$data_inicial,
                   'data_final' => $data_final];
            return redirect()->route('historico.filtro',$data);
    }

    public function indicador_atrasado_lista(){
        $filtro = null;
        return  view('historico_indic.indicador_atrasado',compact('filtro'));
    }
    public function indicador_atrasado_filtro(Historico_indicRequest $request,$data=null){
        $user = Auth::user();
        //dd($request);
        if(!is_null($request->data_inicial)){
            $data_inicial = $request->data_inicial;
            $data_final = $request->data_final;
            $usuario =  Auth::user()->id;
            if($user->can('checkGestor')){
                $userFiltro = '%';
            }else{
                $userFiltro = $usuario;
            }
            $historicos = Historico_indic::where('historico_indic.data_informada','>=',date('Y-m-d', strtotime($request->data_inicial)))
            ->where('historico_indic.data_informada','<=',date('Y-m-d', strtotime($request->data_final)))
            ->where('historico_indic.user_id','like',$userFiltro)
            ->where('status','=','Em Atraso')
            ->paginate(15);
            $historicos->appends(Input::except('page'));
            $filtro = count($historicos);
            return view('historico_indic.indicador_atrasado',compact('historicos','periodicidades','processos','users','filtro','data_inicial','data_final'));
        }else{
            $filtro = null;
            return  view('historico_indic.indicador_atrasado',compact('filtro'));
        }
    }

    public function RelatorioIndicador() {

        //$dados = Responsavel::all();
        $dados = DB::table('responsavels')
        ->join('processos', 'responsavels.id_processo', '=', 'processos.id')
        ->join('users', 'users.id', '=', 'responsavels.usuario')
        ->join('coordenacaos', 'processos.coordenacao', '=', 'coordenacaos.id')
        ->select('processos.nome as processo', 'users.email as usuario', 'coordenacaos.nome as coordenacao')
        ->orderBy('processos.nome','ASC')
        ->orderBy('users.email','ASC')
        ->get();
        $dados= json_decode( json_encode($dados), true);
        $tam = count($dados) + 1;

        Excel::create('Relatório Responsáveis', function($excel) use ($dados, $tam) {
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
                $sheet->setBorder('A1:D'.$tam, 'thin');
                //$sheet->setAutoFilter();
                $sheet->setAutoSize(true);
            });
            
        })->download('xls');

        return true;
    }

}
