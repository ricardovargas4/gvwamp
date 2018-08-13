<?php

namespace gv;

use Illuminate\Database\Eloquent\Model;

class responsavel extends Model
{
    protected $table = 'responsavels';
    public $timestamps = true;
    protected $fillable = array('id', 'id_processo', 'usuario');

    public function id_processo_FK(){
        return $this->belongsTo('gv\processo','id_processo');
    }
    public function usuario_FK(){
        return $this->belongsTo('gv\User','usuario');
    }
    
}