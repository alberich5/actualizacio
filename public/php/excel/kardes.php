<?php
	//Incluimos librería y archivo de conexión
	require 'Classes/PHPExcel.php';
	require 'conexion.php';

	$mes = date("m");
	$fecha = date("Y-m-d");
	//el volcado de informacion hacia la tabla de existencia final
	$query = "DELETE FROM existencia_final WHERE mes = ".$mes.";";
				$sql = $conn->prepare($query);
				$resultado=$sql->execute();
				//Se inserta los valores de la tablas
				$query = "INSERT INTO existencia_final (id_articulo,cantidad,mes) 
				SELECT idarticulo, stock,'".$mes."' from articulo;";
				$sql = $conn->prepare($query);
				$resultado=$sql->execute();
				//Se inserta en la tabla de inicio los productos iniciales
				if ($fecha=='2017-'.$mes.'-01') {
					$query = "DELETE FROM existencia_inicial WHERE mes = ".$mes.";";
					$sql = $conn->prepare($query);
					$resultado=$sql->execute();
					//Se inserta los valores de la tablas
					$query = "INSERT INTO existencia_inicial (id_articulo,cantidad,mes) 
					SELECT idarticulo, stock,'".$mes."' from articulo;";
					$sql = $conn->prepare($query);
					$resultado=$sql->execute();
				}


	
	
	//Consulta para traer la informacion que se va a mostrar
	$sql = "SELECT CONCAT(a.nombre, ' ' , a.descripcion) as nombre, a.unidad,di.precio_venta, (ex.cantidad) AS inicial,(fi.cantidad) AS final,count(salida.idarticulo) as sali,count(ingreso.idarticulo) as ingre,DATE(a.fecha) AS FechaIngre FROM articulo AS a
	INNER JOIN detalle_ingreso as di on a.idarticulo=di.idarticulo
	INNER JOIN existencia_inicial as ex on ex.id_articulo=a.idarticulo
	INNER JOIN existencia_final as fi on fi.id_articulo=a.idarticulo
	LEFT JOIN detalle_venta as salida on a.idarticulo=salida.idarticulo
	LEFT JOIN detalle_ingreso as ingreso on a.idarticulo=ingreso.idarticulo
    WHERE DATE(ex.fecha) >= '01-06-2017' and  DATE(fi.fecha) >= '30-07-2017'
    or DATE(ingreso.fecha) >= '01-06-2017' or DATE(ingreso.fecha) <= '30-07-2017'
    GROUP BY a.idarticulo
	;";
	$resultado = $mysqli->query($sql);
	$fila = 7; //Establecemos en que fila inciara a imprimir los datos
	
	$gdImage = imagecreatefrompng('images/logito.png');//Logotipo
	
	//Objeto de PHPExcel
	$objPHPExcel  = new PHPExcel();
	
	//Propiedades de Documento
	$objPHPExcel->getProperties()->setCreator("Omar Zarate")->setDescription("Reporte de movimientos");
	
	//Establecemos la pestaña activa y nombre a la pestaña
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle("Movimiento");
	
	$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
	$objDrawing->setName('Logotipo');
	$objDrawing->setDescription('Logotipo');
	$objDrawing->setImageResource($gdImage);
	$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
	$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
	$objDrawing->setHeight(90);
	$objDrawing->setCoordinates('A1');
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
	
	$estiloTituloReporte = array(
    'font' => array(
	'name'      => 'Arial',
	'bold'      => true,
	'italic'    => false,
	'strike'    => false,
	'size' =>13
    ),
    'fill' => array(
	'type'  => PHPExcel_Style_Fill::FILL_SOLID
	),
    'borders' => array(
	'allborders' => array(
	'style' => PHPExcel_Style_Border::BORDER_NONE
	)
    ),
    'alignment' => array(
	'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
	);
	
	$estiloTituloColumnas = array(
    'font' => array(
	'name'  => 'Arial',
	'bold'  => true,
	'size' =>10,
	'color' => array(
	'rgb' => 'FFFFFF'
	)
    ),
    'fill' => array(
	'type' => PHPExcel_Style_Fill::FILL_SOLID,
	'color' => array('rgb' => '538DD5')
    ),
    'borders' => array(
	'allborders' => array(
	'style' => PHPExcel_Style_Border::BORDER_THIN
	)
    ),
    'alignment' =>  array(
	'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
	);
	
	$estiloInformacion = new PHPExcel_Style();
	$estiloInformacion->applyFromArray( array(
    'font' => array(
	'name'  => 'Arial',
	'color' => array(
	'rgb' => '000000'
	)
    ),
    'fill' => array(
	'type'  => PHPExcel_Style_Fill::FILL_SOLID
	),
    'borders' => array(
	'allborders' => array(
	'style' => PHPExcel_Style_Border::BORDER_THIN
	)
    ),
	'alignment' =>  array(
	'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
	));
	
	$objPHPExcel->getActiveSheet()->getStyle('A1:I4')->applyFromArray($estiloTituloReporte);
	$objPHPExcel->getActiveSheet()->getStyle('A6:I6')->applyFromArray($estiloTituloColumnas);
	
	$objPHPExcel->getActiveSheet()->setCellValue('B3', 'KARDES DEL MES');
	$objPHPExcel->getActiveSheet()->mergeCells('D3:F3');
	

	$objPHPExcel->getActiveSheet()->setCellValue('A6', 'FECHA INGRESO DEL ARTICULO');
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
	$objPHPExcel->getActiveSheet()->setCellValue('B6', 'DESCRIPCION');
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(45);
	$objPHPExcel->getActiveSheet()->setCellValue('C6', 'UNIDAD');
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	$objPHPExcel->getActiveSheet()->setCellValue('D6', 'PRECIO');
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(33);
	$objPHPExcel->getActiveSheet()->setCellValue('E6', 'EXISTENCIA INICIO DE MES');
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
	$objPHPExcel->getActiveSheet()->setCellValue('F6', 'ENTRADA');
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
	$objPHPExcel->getActiveSheet()->setCellValue('G6', 'SALIDA');
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(33);
	$objPHPExcel->getActiveSheet()->setCellValue('H6', 'EXISTENCIA FINAL DE MES');
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
	$objPHPExcel->getActiveSheet()->setCellValue('I6', 'COSTO FINAL');
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(17);
	
	//Recorremos los resultados de la consulta y los imprimimos
	while($rows = $resultado->fetch_assoc()){
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $rows['FechaIngre']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $rows['nombre']);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $rows['unidad']);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $rows['precio_venta']);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $rows['inicial']);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, $rows['ingre']);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $rows['sali']);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, $rows['final']);
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, '=D'.$fila.'*G'.$fila);
		

		
		$fila++; //Sumamos 1 para pasar a la siguiente fila
	}
	
	$fila = $fila-1;
	
	$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A7:I".$fila);
	
	$filaGrafica = $fila+2;
	
	// definir origen de los valores
	$values = new PHPExcel_Chart_DataSeriesValues('Number', 'Productos!$D$7:$D$'.$fila);
	
	// definir origen de los rotulos
	$categories = new PHPExcel_Chart_DataSeriesValues('String', 'Productos!$B$7:$B$'.$fila);
	
	// definir  gráfico
	$series = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_BARCHART, // tipo de gráfico
	PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,
	array(0),
	array(),
	array($categories), // rótulos das columnas
	array($values) // valores
	);
	$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
	
	// inicializar gráfico
	$layout = new PHPExcel_Chart_Layout();
	$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
	
	// inicializar o gráfico
	$chart = new PHPExcel_Chart('exemplo', null, null, $plotarea);
	
	// definir título do gráfico
	$title = new PHPExcel_Chart_Title(null, $layout);
	$title->setCaption('Gráfico PHPExcel Chart Class');
	
	// definir posiciondo gráfico y título
	$chart->setTopLeftPosition('B'.$filaGrafica);
	$filaFinal = $filaGrafica + 10;
	$chart->setBottomRightPosition('E'.$filaFinal);
	$chart->setTitle($title);
	
	// adicionar o gráfico à folha
	$objPHPExcel->getActiveSheet()->addChart($chart);
	
	$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	
	// incluir gráfico
	$writer->setIncludeCharts(False);
	$tiempo=time();
	
	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header('Content-Disposition: attachment;filename="kardes"'.$tiempo.'".xlsx"');
	header('Cache-Control: max-age=0');
	
	$writer->save('php://output');
	
?>