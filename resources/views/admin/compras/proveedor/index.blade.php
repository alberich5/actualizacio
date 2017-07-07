@extends ('admin.layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Proveedores <a href="almacen-proveedor-crear"><button class="btn btn-success">Nuevo Proveedor</button></a></h3>
		@include('compras.proveedor.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Id</th>
					<th>Nombre</th>
					<th>Tipo</th>
					<th>Telefono</th>
					<th>Email</th>
					@if (Auth::user()->name == 'admin')
					<th>Opciones</th>
					@endif
				</thead>
               @foreach ($personas as $per)
				<tr>
					<td>{{ $per->idpersona}}</td>
					<td>{{ $per->nombre}}</td>
					<td>{{ $per->tipo_documento}}</td>
					<td>{{ $per->telefono}}</td>
					<td>{{ $per->email}}</td>
					@if (Auth::user()->name == 'admin')
					<td>
						<a href="{{URL::action('ProveedorController@edit',$per->idpersona)}}"><button class="btn btn-info">Editar</button></a>
                         <a href="" data-target="#modal-delete-{{$per->idpersona}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
					</td>
					@endif
				</tr>
				@include('compras.proveedor.modal')
				@endforeach
			</table>
		</div>
		{{$personas->render()}}
	</div>
</div>

@endsection