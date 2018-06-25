<?php

namespace gv;

use Illuminate\Database\Eloquent\Model;

class Observacao extends Model
{
    protected $table = 'observacoes';
    public $timestamps = true;
    protected $fillable = array('id_atividade','observacao','classificacao');

    public function id_atividade(){
        return $this->hasMany('gv\Atividade');
    }

}
