<?php

namespace gv;

use Illuminate\Database\Eloquent\Model;

class Demanda extends Model
{
    protected $table = 'demandas';
    public $timestamps = true;
    protected $fillable = array('id_processo','data_final','id_responsavel','data_conclusao');
    
    public function id_processo_FK(){
        return $this->belongsTo('gv\processo', 'id_processo');
    }
    public function id_responsavelFK(){
        return $this->belongsTo('gv\user','id_responsavel');
    }
}


