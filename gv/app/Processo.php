<?php

namespace gv;

use Illuminate\Database\Eloquent\Model;

class processo extends Model
{
    protected $table = 'processos';
    public $timestamps = true;
    protected $fillable = array('nome', 'tipo', 'periodicidade', 'coordenacao','volumetria');

    public function tipo_FK(){
        return $this->belongsTo('gv\Tipo','tipo');
    }
    public function periodicidade_FK(){
        return $this->belongsTo('gv\Periodicidade','periodicidade');
    }
    public function coordenacao_FK(){
        return $this->belongsTo('gv\Coordenacao','coordenacao');
    }
    public function responsavel(){
        return $this->hasMany('gv\Responsavel');
    }
    public function atividades(){
        return $this->hasMany('gv\Atividade');
    }
    public function historico_indic(){
        return $this->hasMany('gv\Historico_indic');
    }
    public function demandaFK(){
        return $this->hasMany('gv\Demanda');
    }
    public function conclusao(){
        return $this->hasMany('gv\Conclusao');
    }
}
