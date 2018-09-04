<?php

namespace gv\Console\Commands;
use Illuminate\Console\Command;

use DB;
use gv\Historico_indic;

class ProcIndicador extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ProcIndicador:indicador';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processo o Indicaodor';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*Alterar Criacao de Historico por CRUD*/
        $data = DB::select("SELECT FLOAT_DIAS_UTEIS(NOW(),'-1') data");
        $data= $data[0]->data;
        $historicos = DB::table('responsavels')
        ->join ('processos','responsavels.id_processo','=','processos.id')
        ->join ('periodicidades','periodicidades.id','=','processos.periodicidade')
        ->join ('users','users.id','=','responsavels.usuario')
        ->join ('tipos','tipos.id','=', 'processos.tipo')
        ->leftjoin (DB::raw('(select  id_processo, data_conciliada from atividades where hora_fim is null) atividades'),function($join){$join->on('atividades.id_processo', '=', 'processos.id');})
        ->leftjoin (DB::raw("(select id_processo, max(data_conciliada) ultima_data from conclusoes where data_conciliada <= '".$data."' group by id_processo) conclusoes"),function($join){$join->on('conclusoes.id_processo','=','processos.id');})
        ->select(DB::raw("distinct processos.id as processo_id, '$data' data_informada, users.id user_id,
        ultima_data, FLOAT_DIAS_UTEIS('$data',periodicidades.dias) data_meta,
        periodicidades.id periodicidade_id,
        (CASE WHEN ultima_data >= FLOAT_DIAS_UTEIS('$data',periodicidades.dias) then 'No Prazo' else 'Em Atraso' end) as status
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
    }
}


