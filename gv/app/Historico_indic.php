<?php

namespace gv;

use Illuminate\Database\Eloquent\Model;

class historico_indic extends Model
{
    protected $table = 'historico_indic';
    public $timestamps = true;
    protected $fillable = array('processo_id','data_informada','user_id','ultima_data','data_meta', 'periodicidade_id','status');

    public function processo_id_FK(){
        return $this->belongsTo('gv\Processo','processo_id');
    }
    public function user_id_FK(){
        return $this->belongsTo('gv\User','user_id');
    }
    public function periodiciade_id_FK(){
        return $this->belongsTo('gv\Periodicidade','periodicidade_id');
    }
    public function expurgo_Rel(){
        return $this->hasOne('gv\Expurgo_Indicador', 'id_historico_indic', 'id');
    }
}


