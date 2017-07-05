<?php


Route::get('/', function () {    
	return view('auth/login');
});


Route::group(['middleware'=>['auth','admin'],'prefix'=>'admin'],function()
{

	Route::get('/', function () {    
		return redirect()->to('almacen-venta');
	});

	 //ruta de categoria
Route::get('almacen-categoria', 'CategoriaController@index');
Route::get('almacen-categoria-crear', 'CategoriaController@create');
//Route::get('almacen-editar', 'CategoriaController@edit');
Route::get('almacen-categoria-editar/{id}',['uses'=> 'CategoriaController@edit', 'as'=> 'almacen-editar.index']);
Route::delete('almacen-categoria-borrar/{id}', 'CategoriaController@destroy');
Route::post('almacen-categoria-store', 'CategoriaController@store');
Route::patch('almacen-categoria-update/{id}',['uses'=> 'CategoriaController@update', 'as'=> 'almacen-categoria-update']);

//Route::resource('almacen/categoria','CategoriaController');

//ruta de articulo
//Route::resource('almacen/articulo','ArticuloController');
Route::get('almacen-articulo', 'ArticuloController@index');
Route::get('almacen-articulo-crear', 'ArticuloController@create');
Route::get('almacen-articulo-editar/{id}', 'ArticuloController@edit');
Route::delete('almacen-articulo-borrar/{id}', 'ArticuloController@destroy');
Route::patch('almacen-articulo-update', 'ArticuloController@update');
//Route::post('almacen-articulo-store', 'ArticuloController@store');
Route::post('almacen-articulo-store',['uses'=> 'ArticuloController@store', 'as'=> 'almacen-articulo-store']);

//ruta de cliente
//Route::resource('ventas/cliente','ClienteController');
Route::get('almacen-cliente', 'ClienteController@index');
Route::get('almacen-cliente-crear', 'ClienteController@create');
Route::get('almacen-cliente-editar/{id}', 'ClienteController@edit');
Route::delete('almacen-cliente-borrar/{id}', 'ClienteController@destroy');
Route::post('almacen-cliente-store', 'ClienteController@store');
Route::patch('almacen-cliente-update/{id}',['uses'=> 'ClienteController@update', 'as'=> 'almacen-cliente-update']);


//ruta del proveedor
//Route::resource('compras/proveedor','ProveedorController');
Route::get('almacen-proveedor', 'ProveedorController@index');
Route::get('almacen-proveedor-crear', 'ProveedorController@create');
Route::get('almacen-proveedor-editar/{id}', 'ProveedorController@edit');
Route::delete('almacen-proveedor-borrar/{id}', 'ProveedorController@destroy');
Route::post('almacen-proveedor-store', 'ProveedorController@store');
Route::patch('almacen-proveedor-update/{id}',['uses'=> 'ProveedorController@update', 'as'=> 'almacen-proveedor-update']);

//ruta del ingreso almacen
//Route::resource('compras/ingreso','IngresoController');
Route::get('almacen-ingreso', 'IngresoController@index');
Route::get('almacen-ingreso-crear', 'IngresoController@create');
Route::get('almacen-ingreso-mostrar/{id}', 'IngresoController@show');
Route::get('almacen-ingreso-editar', 'IngresoController@edit');
Route::delete('almacen-ingreso-borrar', 'IngresoController@destroy');
Route::post('almacen-ingreso-store', 'IngresoController@store');

//ruta de venta
//Route::resource('ventas/venta','VentaController');
Route::get('almacen-venta', 'VentaController@index');
Route::get('almacen-venta-crear', 'VentaController@create');
Route::get('almacen-venta-mostrar/{id}', 'VentaController@show');
Route::get('almacen-venta-editar', 'VentaController@edit');
Route::get('almacen-venta-borrar/{id}', 'VentaController@destroy');
Route::post('almacen-venta-store', 'VentaController@store');

//ruta de venta
//Route::resource('reporte/kardex','ReporteController');
 Route::get('reporte-kardex', 'ReporteController@index');

//Route::resource('seguridad/usuario','UsuarioController');
Route::get('seguridad-usuario', 'UsuarioController@index');
Route::get('seguridad-usuario-crear', 'UsuarioController@create');
Route::get('seguridad-usuario-editar/{id}', 'UsuarioController@edit');
Route::delete('seguridad-usuario-borrar/{id}', 'UsuarioController@destroy');
Route::post('seguridad-usuario-store', 'UsuarioController@store');
Route::patch('seguridad-usuario-update',['uses'=> 'UsuarioController@update', 'as'=> 'seguridad-usuario-update']);


});




Route::group(['middleware'=>['auth','responsable'],'prefix'=>'responsable'],function()
{
	 
	Route::get('/', function () {    
		return redirect()->to('responsable/almacen-venta');
	});

	//ruta de categoria
Route::get('almacen-categoria', 'CategoriaController@index');
Route::get('almacen-categoria-crear', 'CategoriaController@create');
Route::get('almacen-editar', 'CategoriaController@edit');

Route::post('almacen-categoria-store', 'CategoriaController@store');
Route::patch('almacen-categoria-update/{id}',['uses'=> 'CategoriaController@update', 'as'=> 'almacen-categoria-update']);

//Route::resource('almacen/categoria','CategoriaController');

//ruta de articulo
//Route::resource('almacen/articulo','ArticuloController');
Route::get('almacen-articulo', 'ArticuloController@index');
Route::get('almacen-articulo-crear', 'ArticuloController@create');

Route::patch('almacen-articulo-update', 'ArticuloController@update');
//Route::post('almacen-articulo-store', 'ArticuloController@store');
Route::post('almacen-articulo-store',['uses'=> 'ArticuloController@store', 'as'=> 'almacen-articulo-store']);

//ruta de cliente
//Route::resource('ventas/cliente','ClienteController');
Route::get('almacen-cliente', 'ClienteController@index');
Route::get('almacen-cliente-crear', 'ClienteController@create');
//Route::get('almacen-cliente-editar/{id}', 'ClienteController@edit');
//Route::delete('almacen-cliente-borrar/{id}', 'ClienteController@destroy');
Route::post('almacen-cliente-store', 'ClienteController@store');
Route::patch('almacen-cliente-update/{id}',['uses'=> 'ClienteController@update', 'as'=> 'almacen-cliente-update']);


//ruta del proveedor
//Route::resource('compras/proveedor','ProveedorController');
Route::get('almacen-proveedor', 'ProveedorController@index');
Route::get('almacen-proveedor-crear', 'ProveedorController@create');

Route::post('almacen-proveedor-store', 'ProveedorController@store');
Route::patch('almacen-proveedor-update/{id}',['uses'=> 'ProveedorController@update', 'as'=> 'almacen-proveedor-update']);

//ruta del ingreso almacen
//Route::resource('compras/ingreso','IngresoController');
Route::get('almacen-ingreso', 'IngresoController@index');
Route::get('almacen-ingreso-crear', 'IngresoController@create');
Route::get('almacen-ingreso-mostrar/{id}', 'IngresoController@show');

Route::post('almacen-ingreso-store', 'IngresoController@store');

//ruta de venta
//Route::resource('ventas/venta','VentaController');
Route::get('almacen-venta', 'VentaController@index');
Route::get('almacen-venta-crear', 'VentaController@create');
Route::get('almacen-venta-mostrar/{id}', 'VentaController@show');

Route::post('almacen-venta-store', 'VentaController@store');

//ruta de venta
//Route::resource('reporte/kardex','ReporteController');
 Route::get('reporte-kardex', 'ReporteController@index');

//Route::resource('seguridad/usuario','UsuarioController');
Route::get('seguridad-usuario', 'UsuarioController@index');
Route::get('seguridad-usuario-crear', 'UsuarioController@create');

Route::post('seguridad-usuario-store', 'UsuarioController@store');
Route::patch('seguridad-usuario-update',['uses'=> 'UsuarioController@update', 'as'=> 'seguridad-usuario-update']);




});

//Rutas para el manejo de login y usuarios
Route::auth();

Route::get('omar', 'auxiliar\ArticulosController@index');