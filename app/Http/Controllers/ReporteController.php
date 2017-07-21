<?php

namespace Omar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use Omar\Http\Requests;
//libreria necesaria para poder ocupar el excel en laravel
use Maatwebsite\Excel\Facades\Excel;
use Omar\Articulo;
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

    //optengo la fecha actual con carboi
    $date = Carbon::now();
    $date = $date->format('Y-m-d');
  
      Excel::create('Reporte del Kardex Final', function($excel) {
 
            $excel->sheet('Kardex2', function($sheet) {
                
                $consulta=DB::table('articulo as a')
                    ->join('detalle_venta as dv','dv.idarticulo','=','a.idarticulo')
                    ->select('a.idarticulo','a.nombre','a.unidad','dv.fecha',DB::raw('sum(dv.cantidad) as total'))
                    ->where('dv.fecha','=','2017-07-15')
                    ->groupBy('a.idarticulo','a.nombre','a.unidad','dv.fecha','dv.cantidad')
                    ->first();
                //se esta llamdo la fecha actual en el controlador
                    $date = Carbon::now();
                    $date = $date->format('Y-m-d');
                //consulta 2 para generar los reportes  de excel
                $consulta2= Articulo::join('detalle_ingreso as dv','dv.idarticulo','=','articulo.idarticulo')
                ->select('articulo.idarticulo','articulo.nombre','articulo.unidad','dv.fecha',DB::raw('sum(dv.cantidad) as total'))
                ->where('dv.fecha','=', $date)
                ->groupBy('articulo.idarticulo','articulo.nombre','articulo.unidad','dv.fecha','dv.cantidad')
                ->get();
                dd($consulta2);

                $collection = Collection::make($consulta2);
                //dd($collection);
                $sheet->fromArray($collection);
 
            });
        })->export('xls');

        
    }
}
