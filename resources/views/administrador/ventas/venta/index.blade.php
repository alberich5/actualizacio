@extends ('administrador.admin')
@section ('contenido')
<div class="row" >

	@foreach ($final as $fin)
		<span class="label label-success">{{ $fin->nombre}} {{ $fin->stock}}</span>
	@endforeach
</div>
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Salidas <a href="almacen-venta-crear"><button class="btn btn-success">Nuevo Salida</button></a></h3>
		@include('ventas.venta.search')
	</div>
</div>
<div id="mover">
{!!Form::open(array('url'=>'almacen-venta-excel','method'=>'GET','autocomplete'=>'off'))!!}
{{Form::token()}}
	
	<i class="fa fa-download" aria-hidden="true"><input type="submit" name="" value="excel" class=" btn btn-info"></i>
{!!Form::close()!!}	
			
</div>
			
</div>
<div class="row" style="background-color: #FFEFD5;">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Fecha</th>
					<th>Cliente</th>
					<th>Comprobante</th>
					<th>Total</th>
					<th>Estado</th>
					<th>Opciones</th>
				</thead>
               @foreach ($ventas as $ven)
				<tr>
					<td>{{ $ven->fecha_hora}}</td>
					<td>{{ $ven->nombre}}</td>
					<td>{{ $ven->tipo_comprobante.': '.$ven->serie_comprobante.'-'.$ven->num_comprobante}}</td>
					<td>{{ $ven->total_venta}}</td>
					<td>{{ $ven->estado}}</td>
					<td>
						<a href="{{URL::action('VentaController@show',$ven->idventa)}}"><button class="btn btn-primary">Detalle</button></a>
						@if (Auth::user()->name == 'admin')
                         <a href="" data-target="#modal-delete-{{$ven->idventa}}" data-toggle="modal"><button class="btn btn-danger">Cancelar</button></a>
                        @endif
					</td>
				</tr>
				@include('ventas.venta.modal')
				@endforeach
			</table>
		</div>
		{{$ventas->render()}}
	</div>
</div>

@endsection 