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

    public function RelatorioIndicador(){
        $filtro = null;
        return  view('relatorios.indicador',compact('filtro'));
    }

    public function RelatorioIndicadorMensal(Historico_indicRequest $request) {

        $NoPrazo= DB::table('historico_indic')
        ->select(DB::raw(" user_id , count(*) as Indic, MONTH(historico_indic.data_informada) as mes, year(historico_indic.data_informada) ano"))
        ->whereBetween('historico_indic.data_informada', [$request->data_inicial, $request->data_final])
        ->where('status','=','No Prazo')
        ->groupBy('user_id','mes', 'ano')
        ->get()
        ;
        
        $Total= DB::table('historico_indic')
        ->join('users', 'users.id', '=', 'historico_indic.user_id')
        ->select(DB::raw(" user_id,users.email , count(*) as Indic, MONTH(historico_indic.data_informada) as mes, year(historico_indic.data_informada) ano"))
        ->whereBetween('historico_indic.data_informada', [$request->data_inicial, $request->data_final])
        ->groupBy('user_id','users.email','mes', 'ano')
        ->orderBy('users.email')
        ->orderBy('mes')
        ->orderBy('ano')                
        ->get();
        //->toSql();
        //dd($Total);
        foreach($Total as $T){
            $T->Indicador="0";
            foreach($NoPrazo as $P){
                if($T->user_id == $P->user_id && $T->mes == $P->mes && $T->ano == $P->ano){     
                    $T->Indicador = round($P->Indic / $T->Indic * 100,2);
                }
            }
        }
        $Total->transform(function($i) {
            unset($i->user_id);
            unset($i->Indic);
            return $i;
        });
        $dados= json_decode( json_encode($Total), true);
        $tam = count($dados) + 1;

        Excel::create('Relatório Indicador Mensal', function($excel) use ($dados, $tam) {
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
                $sheet->setBorder('A1:E'.$tam, 'thin');
                //$sheet->setAutoFilter();
                $sheet->setAutoSize(true);
            });
            
        })->download('xls');

        return true;
    }

    public function RelatorioIndicadorMensalProc(Historico_indicRequest $request) {

        $NoPrazo= DB::table('historico_indic')
        ->select(DB::raw(" user_id , count(*) as Indic,processo_id, MONTH(historico_indic.data_informada) as mes, year(historico_indic.data_informada) ano"))
        ->whereBetween('historico_indic.data_informada', [$request->data_inicial, $request->data_final])
        ->where('status','=','No Prazo')
        ->groupBy('user_id','mes', 'ano','processo_id')
        ->get()
        ;
        $Total= DB::table('historico_indic')
        ->join('users', 'users.id', '=', 'historico_indic.user_id')
        ->join('processos', 'historico_indic.processo_id', '=', 'processos.id')
        ->select(DB::raw(" user_id,users.email , count(*) as Indic,processo_id,processos.nome, MONTH(historico_indic.data_informada) as mes, year(historico_indic.data_informada) ano"))
        ->whereBetween('historico_indic.data_informada', [$request->data_inicial, $request->data_final])
        ->groupBy('user_id','users.email','mes', 'ano','processo_id','processos.nome')
        ->orderBy('users.email')
        ->orderBy('mes')
        ->orderBy('ano')                
        ->orderBy('processos.nome')
        ->get();
        //->toSql();

        foreach($Total as $T){
            $T->Indicador="0";
            foreach($NoPrazo as $P){
                if($T->user_id == $P->user_id && $T->processo_id == $P->processo_id && $T->mes == $P->mes && $T->ano == $P->ano){     
                    $T->Indicador = round($P->Indic / $T->Indic * 100,2);
                }
            }
        }
        $Total->transform(function($i) {
            unset($i->user_id);
            unset($i->Indic);
            unset($i->processo_id);
            return $i;
        });
        $dados= json_decode( json_encode($Total), true);
        $tam = count($dados) + 1;

        Excel::create('Relatório Indicador Mensal por Processo', function($excel) use ($dados, $tam) {
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
                $sheet->setAutoSize(true);
                $sheet->setBorder('A1:F'.$tam, 'thin');
                //$sheet->setAutoFilter();
            });
            
        })->download('xls');

        return true;
    }

    public function RelatorioIndicadorAnalitico(Historico_indicRequest $request) {

        $hist= DB::table('historico_indic')
        ->join('users', 'users.id', '=', 'historico_indic.user_id')
        ->join('processos', 'historico_indic.processo_id', '=', 'processos.id')
        ->join('periodicidades', 'historico_indic.periodicidade_id', '=', 'periodicidades.id')
        ->select(DB::raw(" user_id,users.email ,processo_id,processos.nome as processo, periodicidades.nome as periodicidade, data_informada, ultima_data, data_meta, status  "))
        ->whereBetween('historico_indic.data_informada', [$request->data_inicial, $request->data_final])
        ->orderBy('users.email')
        ->orderBy('data_informada')
        ->orderBy('processos.nome')
        ->get();
        //->toSql();
       
        $hist->transform(function($i) {
            unset($i->user_id);
            unset($i->processo_id);
            return $i;
        });
        $dados= json_decode( json_encode($hist), true);
        $tam = count($dados) + 1;

        Excel::create('Relatório Indicador Analítico', function($excel) use ($dados, $tam) {
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
                $sheet->setAutoSize(true);
                $sheet->setBorder('A1:G'.$tam, 'thin');
                //$sheet->setAutoFilter();
            });
            
        })->download('xls');

        return true;
    }

}
