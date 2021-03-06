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
use Illuminate\Pagination\Paginator;
use gv\Expurgo_Indicador;

class Historico_indicController extends Controller
{
    public function lista(){
        $filtro = null;
        $users = User::orderBy('email')->get();
        return  view('historico_indic.listagem',compact('filtro','users'));
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
            if(isset($request->filtroUsuario)){
                $userFiltro = $request->filtroUsuario;
                $filtroUsuario = User::find($userFiltro);
            }else{
                if($user->can('checkGestor')){
                    $userFiltro = '%';
                }else{
                    $userFiltro = $usuario;
                }
            }
            $filtroPrazo = $request->filtroPrazo;
            if(isset($request->filtroPrazo)){
                $statusFiltro = $request->filtroPrazo;
            }else{
                $statusFiltro = '%';
            }
            if(isset($request->page)){
                $page=$request->page;
            }else{
                $page=1;
            }
          
            $historicos = Historico_indic::where('historico_indic.data_informada','>=',date('Y-m-d', strtotime($request->data_inicial)))
            ->where('historico_indic.data_informada','<=',date('Y-m-d', strtotime($request->data_final)))
            ->where('historico_indic.user_id','like',$userFiltro)
            ->where('historico_indic.status','like',$statusFiltro)
            ->paginate(15, ['*'], 'page', $page);

            $historicos->appends(Input::except('page'));
            $filtro = count($historicos);
            return view('historico_indic.listagem',compact('historicos','periodicidades','processos','users','filtro','data_inicial','data_final','filtroUsuario','filtroPrazo'));
        }else{
            $filtro = null;
            $users = User::orderBy('email')->get();
            return  view('historico_indic.listagem',compact('filtro','users'));
        }
    }

    public function remove($id,$data_inicial=null,$data_final=null,$filtroUsuario=null,$page=null,$filtroPrazo=null){
        $historico = Historico_indic::find($id);
        $filtroUsuario = User::find($filtroUsuario);
        $historico->delete();
        $data=['data_inicial' =>$data_inicial,
               'data_final' => $data_final,
               'filtroUsuario' => $filtroUsuario,
               'page' => $page,
               'filtroPrazo' => $filtroPrazo];
        return redirect()->route('historico.filtro',$data);
    }

    public function salvaAlt(Historico_indicRequest $request){
        $id = $request->id;
        if(isset($request->page)){
            $page=$request->page;
        }else{
            $page=1;
        }
        Historico_indic::whereId($id)->update($request->except('_token','data_inicial','data_final','page','filtroUsuario','filtroPrazo'));
        $filtro = null;
        $filtroUsuario = User::find($request->filtroUsuario);
        $data_inicial = $request->data_inicial;
        $data_final = $request->data_final;
        $data=['data_inicial' =>$data_inicial,
               'data_final' => $data_final,
               'filtroUsuario' => $filtroUsuario,
               'page' => $page,
               'filtroPrazo'=> $request->filtroPrazo];
        return redirect()->route('historico.filtro',$data);
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
                   'data_final' => $data_final,
                   'filtroUsuario' => $request->filtroUsuario,
                   'page' => $request->page,
                   'filtroPrazo'=> $request->filtroPrazo];
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
        ->join('processos','processos.id','=','historico_indic.processo_id')
        ->join('coordenacaos','coordenacaos.id','=','processos.coordenacao')
        ->select(DB::raw(" user_id, coordenacaos.nome as coordenacao, count(*) as Indic, MONTH(historico_indic.data_informada) as mes, year(historico_indic.data_informada) ano"))
        ->whereBetween('historico_indic.data_informada', [$request->data_inicial, $request->data_final])
        ->where('status','=','No Prazo')
        ->groupBy('user_id','coordenacaos.nome','mes', 'ano')
        ->get()
        ;
        
        $Total= DB::table('historico_indic')
        ->join('users', 'users.id', '=', 'historico_indic.user_id')
        ->join('processos','processos.id','=','historico_indic.processo_id')
        ->join('coordenacaos','coordenacaos.id','=','processos.coordenacao')
        ->select(DB::raw(" user_id,users.email ,coordenacaos.nome as coordenacao, count(*) as Indic, MONTH(historico_indic.data_informada) as mes, year(historico_indic.data_informada) ano"))
        ->whereBetween('historico_indic.data_informada', [$request->data_inicial, $request->data_final])
        ->groupBy('user_id','users.email','coordenacaos.nome','mes', 'ano')
        ->orderBy('users.email')
        ->orderBy('mes')
        ->orderBy('ano')                
        ->get();
        //->toSql();
        //dd($Total);
        foreach($Total as $T){
            $T->Indicador="0";
            foreach($NoPrazo as $P){
                if($T->user_id == $P->user_id && $T->coordenacao == $P->coordenacao && $T->mes == $P->mes && $T->ano == $P->ano){     
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
/*
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
        });*/

        $dados = DB::table('historico_indic')
            ->join('processos', 'historico_indic.processo_id', '=', 'processos.id')
            ->join('users', 'users.id', '=', 'historico_indic.user_id')
            ->join('coordenacaos','coordenacaos.id','=','processos.coordenacao')
            ->leftjoin(DB::raw("(select user_id, 
                                        processo_id,
                                        count(*) as Prazo 
                                from historico_indic 
                                where status = 'No Prazo'     
                                and data_informada between '" . $request->data_inicial . "'  and  '" .  $request->data_final .  "'  
                                group by user_id, processo_id) as NoPrazo"), function($join) {$join->on('historico_indic.user_id', '=', 'NoPrazo.user_id'); $join->on('historico_indic.processo_id', '=', 'NoPrazo.processo_id'); })
            ->leftjoin(DB::raw("(select user_id, 
                                        processo_id,
                                        count(*) as Total 
                                from historico_indic 
                                where data_informada between '" . $request->data_inicial . "'  and  '" .  $request->data_final .  "' 
                                group by user_id, processo_id) as Total"), function($join) {$join->on('historico_indic.user_id', '=', 'Total.user_id'); $join->on('historico_indic.processo_id', '=', 'Total.processo_id'); })                     
            ->leftjoin(DB::raw("(select user_id, 
                                count(*) as Total_Geral
                        from historico_indic 
                        where data_informada between '" . $request->data_inicial . "'  and  '" .  $request->data_final .  "' 
                        group by user_id) as Geral"), function($join) {$join->on('historico_indic.user_id', '=', 'Geral.user_id'); })                     
            ->select(DB::raw("distinct users.email as email, coordenacaos.nome as coordenacao, processos.nome as processo, month(data_informada) as mes, year(data_informada) as ano, REPLACE(CAST(round(ifnull(NoPrazo.Prazo,0) / ifnull(Total.Total,0) * 100 * ifnull(Total.Total,0) / ifnull(Geral.Total_Geral,0),2) AS CHAR), '.', ',')  as indicador "))
            ->whereBetween('historico_indic.data_informada', [$request->data_inicial, $request->data_final])
            ->orderby('email')
            ->orderby('processo')
            ->orderby('ano')
            ->orderby('mes')
            ->get();
        $dados= json_decode( json_encode($dados), true);
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
