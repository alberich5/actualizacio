<?php

namespace Omar;

use Illuminate\Database\Eloquent\Model;

class ExistenciaInicial extends Model
{
    //hacemos referencia al tabla que vamos a manejar
    protected $table='existencia_inicial';

    
    //se defin ela llave primaria de la tabla articulo
    protected $primaryKey='id_existenciaini';

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
