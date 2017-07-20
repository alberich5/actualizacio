@extends ('administrador.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Reportes</h3>
	</div>
</div>
<div class="row">
	
</div>
<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		{!!Form::open(array('url'=>'reporte-kardex-excel','method'=>'GET','autocomplete'=>'off'))!!}
			{{Form::token()}}
	
		<i class="fa fa-download" aria-hidden="true"><input type="submit" name="" value="excel" class=" btn btn-info"></i>
		{!!Form::close()!!}	
	</div>
</div>
@endsection