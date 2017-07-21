<?php

namespace Omar;

use Illuminate\Database\Eloquent\Model;

class ExistenciaFinal extends Model
{
    //hacemos referencia al tabla que vamos a manejar
    protected $table='existencia_final';

    
    //se defin ela llave primaria de la tabla articulo
    protected $primaryKey='id_existenciafina';

    public $timestamps=false;

    protected $fillable =[
    	'id_articulo',
    	'cantidad',
    	'mes',
    	'fecha'
    ];

    protected $guarded =[

    ];
}
