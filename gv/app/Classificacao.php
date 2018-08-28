<?php

namespace gv;

use Illuminate\Database\Eloquent\Model;

class Classificacao extends Model
{
    protected $table = 'classificacoes';
    public $timestamps = true;
    protected $fillable = array('id_processo','opcao');

    public function id_processo_FK(){
        return $this->belongsTo('gv\Processo','id_processo');
    }

}
