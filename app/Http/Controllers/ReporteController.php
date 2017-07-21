<?php

namespace Omar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use Omar\Http\Requests;
//libreria necesaria para poder ocupar el excel en laravel
use Maatwebsite\Excel\Facades\Excel;
use Omar\Articulo;
use Omar\ExistenciaFinal;
use Omar\ExistenciaInicial;
use DB;
use Carbon\Carbon;


class ReporteController extends Controller
{
     //funcion index
    public function index(Request $request)
    {
        return view('administrador.reporte.kardex.index');
    }

    public function excel()
    {
        //borrar todos los regsitros de la existencia_final
         DB::table('existencia_final')->delete();
        //obtengo el mes actual
        $date = Carbon::now();
        $completa = $date->format('Y-m-d');
        $mes = $date->format('m');
    //buscar la informacion del articulo
        $articulo=Articulo::all();

        $cont = 0;
         while($cont < count($articulo)){
            //volco la informacion de articulo ala existencia
        $final=new ExistenciaFinal;
        $final->id_articulo=$articulo[$cont]->idarticulo;
        $final->cantidad=$articulo[$cont]->stock;
        $final->mes=$mes;
        $final->fecha=$completa;
        $final->save();
            $cont=$cont+1;  
        }
        //para existencia Inicial
        if($completa=='2017-'.$mes.'-01'){
            //esta sentencia solo aplica si ententa el mismo dia hacer varios click para guardar la existencia Inicial
            DB::table('existencia_inicial')->where('mes', '=', $mes)->delete();
            $cont3 = 0;
         while($cont3 < count($articulo)){
            //volco la informacion de articulo ala existencia
                $inicial=new ExistenciaInicial;
                $inicial->id_articulo=$articulo[$cont3]->idarticulo;
                $inicial->cantidad=$articulo[$cont3]->stock;
                $inicial->mes=$mes;
                $inicial->fecha=$completa;
                $inicial->save();
            $cont3=$cont3+1;  
            }
        }
    

    //optengo la fecha actual con carboi
    $date = Carbon::now();
    $date = $date->format('Y-m-d');
  
      Excel::create('Reporte del Kardex Final', function($excel) {
 
            $excel->sheet('Kardex2', function($sheet) {
                
                //obtengo el mes actual
                $date = Carbon::now();
                $date = $date->format('m');
                //consulta 2 para generar los reportes  de excel
                $consulta2= Articulo::join('detalle_ingreso as di','di.idarticulo','=','articulo.idarticulo')
                ->join('existencia_inicial as ex','ex.id_articulo','=','articulo.idarticulo')
                ->join('existencia_final as ef','ef.id_articulo','=','articulo.idarticulo')
                ->leftjoin('detalle_venta as det_ven','det_ven.idarticulo','=','articulo.idarticulo')
                ->select('articulo.fecha',DB::raw('CONCAT(articulo.nombre,articulo.descripcion) as nombre'),'articulo.unidad','di.precio_venta',DB::raw('sum(di.cantidad) as total'),'ex.cantidad as inicial','ef.cantidad as final',DB::raw('count(det_ven.cantidad) as sali'),DB::raw('count(di.cantidad) as ingre'),DB::raw('sum(det_ven.cantidad*di.precio_venta) as total'))
                ->where('ex.mes','=', $date)
                ->where('ef.mes','=',$date)
                ->groupBy('articulo.idarticulo','articulo.nombre','articulo.unidad','di.precio_venta','di.cantidad','ex.cantidad','ef.cantidad','det_ven.idarticulo')
                ->get();
                //convierto el array en una collecion
                $collection = Collection::make($consulta2);
                $sheet->fromArray($collection);
 
            });
        })->export('xls');

        
    }
}
