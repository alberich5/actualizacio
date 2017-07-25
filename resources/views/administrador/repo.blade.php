<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Headings -->
    <td><h1>Reporte de Mensual</center></h1></td>

   <div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Fecha</th>
					<th>Nombre</th>
					<th>Unidad</th>
					<th>Precio</th>
					<th>Inicio de Mes</th>
					<th>Entradas</th>
					<th>Salidas</th>
					<th>Fin de Mes</th>
					<th>Costo Final</th>
				</thead>
               @foreach ($collection as $cole)
				<tr>
					<td width="25">{{ $cole->fecha}}</td>
					<td  valign="middle">{{ $cole->nombre}}</td>
					<td width="11">{{ $cole->unidad}}</td>
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