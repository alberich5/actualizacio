<?php

namespace Omar\Http\Controllers;

use Illuminate\Http\Request;

use Omar\Http\Requests;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Omar\Http\Requests\VentaFormRequest;
use Omar\Venta;
use Omar\Articulo;
use Omar\DetalleVenta;
use DB;
//libreria necesaria para poder ocupar el excel en laravel
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class VentaController extends Controller
{
    //funcion constructor
    public function __construct()
    {
        $this->middleware('auth');
    }

    //funcion principal de ventas controller
    public function index(Request $request)
    {
        if ($request)
        {
           $query=trim($request->get('searchText'));
           $ventas=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta')
            ->where('v.num_comprobante','LIKE','%'.$query.'%')
            ->orderBy('v.idventa','desc')
            ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado')
            ->paginate(7);

             $final=DB::table('articulo')->where('stock','<=','2')->get();
            return view('administrador.ventas.venta.index',["ventas"=>$ventas,"searchText"=>$query,'final'=>$final]);

        }
    }

    //funcion para crear nuevas ventas
     public function create()
    {
     $personas=DB::table('persona')->where('tipo_persona','=','Cliente')->get();
     $articulos = DB::table('articulo as art')
      ->join('detalle_ingreso as di','art.idarticulo','=','di.idarticulo')
            ->select(DB::raw('CONCAT(art.codigo,art.nombre) AS articulo'),'art.idarticulo','art.stock',DB::raw('avg(di.precio_venta) as precio_promedio'))
            ->where('art.estado','=','Activo')
            ->where('art.stock','>','0')
            ->groupBy('articulo','art.idarticulo','art.stock')
            ->get();
        return view("administrador.ventas.venta.create",["personas"=>$personas,"articulos"=>$articulos]);
    }

    //funcion de ventas
    public function store (VentaFormRequest $request)
    {
        $date = Carbon::now();
        $date = $date->format('Y-m-d');
     try{
         DB::beginTransaction();
         $venta=new Venta;
         $venta->idcliente=$request->get('idcliente');
         $venta->tipo_comprobante=$request->get('tipo_comprobante');
         $venta->serie_comprobante=$request->get('serie_comprobante');
         $venta->num_comprobante=$request->get('num_comprobante');
         $venta->total_venta=$request->get('total_venta');
         
         $mytime = Carbon::now('America/Lima');
         $venta->fecha_hora=$mytime->toDateTimeString();
         $venta->impuesto='18';
         $venta->estado='A';
         $venta->save();

         $idarticulo = $request->get('idarticulo');
         $cantidad = $request->get('cantidad');
         $precio_venta = $request->get('precio_venta');

         $cont = 0;

         while($cont < count($idarticulo)){
             $detalle = new DetalleVenta();
             $detalle->idventa= $venta->idventa; 
             $detalle->idarticulo= $idarticulo[$cont];
             $detalle->cantidad= $cantidad[$cont];
             $detalle->precio_venta= $precio_venta[$cont];
             $detalle->fecha= $date;
             $detalle->save();
             $cont=$cont+1;            
         }

         DB::commit();

        }catch(\Exception $e)
        {
           DB::rollback();
        }

        return Redirect::to('almacen-venta');
    }

    //funcion para mostrar las ventas
    public function show($id)
    {
     $venta=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta')
            ->where('v.idventa','=',$id)
            ->first();

        $detalles=DB::table('detalle_venta as d')
             ->join('articulo as a','d.idarticulo','=','a.idarticulo')
             ->select('a.nombre as articulo','d.cantidad','d.descuento','d.precio_venta','a.unidad')
             ->where('d.idventa','=',$id)
             ->get();
             //traer el tamaño del arrelo
             $conte=count($detalles);
             $cadena="";
             for ($i=0; $i <$conte ; $i++) { 
                 $cadena .="nom".$i."=".$detalles[$i]->articulo."&cantidad".$i."=".$detalles[$i]->cantidad."&unidad".$i."=".$detalles[$i]->unidad."&cli".$i."=".$venta->nombre."&";
                 
             }
            $cadena .="total=".$conte;
        return view("administrador.ventas.venta.show",["venta"=>$venta,"detalles"=>$detalles,"cadena"=>$cadena]);
    }

    //funcion para eliminar las categorias
    public function destroy($id)
    {
        $venta=Venta::findOrFail($id);
        $venta->Estado='C';
        $venta->update();
        return Redirect::to('almacen-venta');
    }

     public function excel()
    {

    //optengo la fecha actual con carboi
    $date = Carbon::now();
    $date = $date->format('Y-m-d');
  
      Excel::create('Reporte de Salidas', function($excel) {
 
            $excel->sheet('Salidas', function($sheet) {
                
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
                //dd($consulta2);

                $collection = Collection::make($consulta2);
                //dd($collection);
                $sheet->fromArray($collection);
 
            });
        })->export('xls');

        
    }


}
