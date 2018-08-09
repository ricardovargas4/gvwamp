<?php

namespace gv;

use Illuminate\Database\Eloquent\Model;

class Expurgo_Indicador extends Model
{
    protected $table = 'expurgo_indicador';
    public $timestamps = true;
    protected $fillable = array('id_historico_indic','STATUS','id_usuario_aprovador','comentario','id_usuario_solicitante','justificativa');

    public function id_historico_indic_FK(){
        return $this->belongsTo('gv\historico_indic','id_historico_indic');
    }

    public function id_usuario_aprovador_FK(){
        return $this->belongsTo('gv\User','id_usuario_aprovador');
    }
    public function id_usuario_solicitante_FK(){
        return $this->belongsTo('gv\User','id_usuario_solicitante');
    }

}
