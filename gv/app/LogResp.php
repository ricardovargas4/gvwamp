<?php

namespace gv;

use Illuminate\Database\Eloquent\Model;

class LogResp extends Model
{
    protected $table = 'logs_responsavels';
    public $timestamps = true;
    protected $fillable = array('id_processo','usuario','tipo');

    public function processo_FK(){
        return $this->belongsTo('gv\Processo','ID_PROCESSO');
    }
    public function user_FK(){
        return $this->belongsTo('gv\User','USUARIO');
    }
}
