<?php

namespace gv;

use Illuminate\Database\Eloquent\Model;

class Volumetria extends Model
{
    protected $table = 'volumetrias';
    public $timestamps = true;
    protected $fillable = array('id_atividade','volumetria');

    public function id_atividade(){
        return $this->hasMany('gv\Atividade');
    }

}
