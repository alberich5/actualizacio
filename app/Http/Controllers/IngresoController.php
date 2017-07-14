<?php

namespace Omar\Http\Controllers;

use Illuminate\Http\Request;

use Omar\Http\Requests;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Omar\Http\Requests\IngresoFormRequest;
use Omar\Ingreso;
use Omar\Log;
use Omar\DetalleIngreso;
use DB;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class IngresoController extends Controller
{
    //constructor 
    public function __construct()
    {
         $this->middleware('auth');
    }

    //funcion del index
    public function index(Request $request)
    {
        if ($request)
        {
           $query=trim($request->get('searchText'));
           $ingresos=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
            ->where('i.num_comprobante','LIKE','%'.$query.'%')
            ->orderBy('i.idingreso','desc')
            ->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
            ->paginate(7);

            
            return view('administrador.compras.ingreso.index',["ingresos"=>$ingresos,"searchText"=>$query]);
        }
    }

    //Metodo para mostrar la vista de crear un ingreoso
    public function create()
    {
    	$personas=DB::table('persona')->where('tipo_persona','=','Proveedor')->get();
     $articulos = DB::table('articulo as art')
            ->select(DB::raw('CONCAT(art.codigo,art.nombre) AS articulo'),'art.idarticulo')
            ->where('art.estado','=','Activo')
            ->get();
        return view("administrador.compras.ingreso.create",["personas"=>$personas,"articulos"=>$articulos]);
    }

    //funcion que permite guardar un ingreso
    public function store (IngresoFormRequest $request)
    {
       
     
     	   	 DB::beginTransaction();
	         $ingreso = new Ingreso;
	         $ingreso->idproveedor=$request->get('idproveedor');
	         $ingreso->tipo_comprobante=$request->get('tipo_comprobante');
	         $ingreso->serie_comprobante=$request->get('serie_comprobante');
	         $ingreso->num_comprobante=$request->get('num_comprobante');
	         
	         $mytime = Carbon::now('America/Lima');
	         $ingreso->fecha_hora=$mytime->toDateTimeString();
	         $ingreso->impuesto='18';
	         $ingreso->estado='A';
	         $ingreso->save();


	         $idarticulo = $request->get('idarticulo');
	         $cantidad = $request->get('cantidad');
	         $precio_compra = $request->get('precio_compra');
	         $cont = 0;

	         while($cont < count($idarticulo)){
                //verifico si se inserto el primer dato
	             $detalle = new DetalleIngreso();
	             $detalle->idingreso= $ingreso->idingreso; 
	             $detalle->idarticulo= $idarticulo[$cont];
	             $detalle->cantidad= $cantidad[$cont];
	             $detalle->precio_compra= $precio_compra[$cont];
                 $detalle->precio_venta= $precio_compra[$cont];
	             $detalle->save();


                 //guardo el log de actividad
                    $log=new Log;
                    $log->id_user=Auth::user()->id;
                    $log->id_articulo=$idarticulo[$cont];
                    $log->tipo='Ingreso_Articulo';
                    $log->save();

                 
	             $cont=$cont+1;

                 
                            
	         }

           DB::commit();
     	     
              
     	   return Redirect::to('almacen-ingreso'); 

    }

    //Funcion para mostrar todos los ingresos realizados
     public function show($id)
    {
        $ingreso=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
            ->where('i.idingreso','=',$id)
            ->first();

        $detalles=DB::table('detalle_ingreso as d')
             ->join('articulo as a','d.idarticulo','=','a.idarticulo')
             ->select('a.nombre as articulo','d.cantidad','d.precio_compra','d.precio_venta')
             ->where('d.idingreso','=',$id)
             ->get();
        return view("administrador.compras.ingreso.show",["ingreso"=>$ingreso,"detalles"=>$detalles]);
    }

    //funcion para cancelar
     public function destroy($id)
    {
       $ingreso=Ingreso::findOrFail($id);
       $ingreso->Estado='C';
       $ingreso->update();
       return Redirect::to('almacen-ingreso');
    }
}
