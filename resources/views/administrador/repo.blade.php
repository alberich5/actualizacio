<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Headings -->
    <td><h1>Reporte de Mensual</center></h1></td>

   <div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<tr>
					<td width="25">Fecha</td>
					<td>Nombre</td>
					<td width="11">Unidad</td>
					<td >Precio</td>
					<td>Inicio de Mes</td>
					<td>Entradas</td>
					<td>Salidas</td>
					<td>Fin de Mes</td>
					<td>Costo Final</td>
				</tr>
               @foreach ($collection as $cole)
				<tr>
					<td>{{ $cole->fecha}}</td>
					<td>{{ $cole->nombre}}</td>
					<td>{{ $cole->unidad}}</td>
					<td>{{ $cole->precio_venta}}</td>
					<td>{{ $cole->inicial}}</td>
					<td>{{ $cole->ingre}}</td>
					<td>{{ $cole->sali}}</td>
					<td>{{ $cole->final}}</td>
					<td>{{ $cole->total}}</td>
				</tr>
				@endforeach
			</table>
		</div>

	</div>
</div>

</html>
