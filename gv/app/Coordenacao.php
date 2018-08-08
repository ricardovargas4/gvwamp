<?php

namespace gv;

use Illuminate\Database\Eloquent\Model;

class Coordenacao extends Model
{
    protected $table = 'coordenacaos';
    public $timestamps = true;
    protected $fillable = array('nome','id_gestor');

    public function processos(){
        return $this->hasMany('gv\Processo');
    }
    public function id_gestor_FK(){
        return $this->belongsTo('gv\User','id_gestor');
    }

}
