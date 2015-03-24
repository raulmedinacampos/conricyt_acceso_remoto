<?php
	set_time_limit(0);
	session_start();
	ob_start();
	require_once '../config.php';
	require_once '../lib/phpexcel/Classes/PHPExcel.php';
	
	$meses = array("", "enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
	
	$fila = 6;
	$where = "";
	$join = "";
	$orden = " ORDER BY u.firstname, u.lastname1, u.lastname2";
	
	$sede = (isset($_POST['institucion'])) ? $_POST['institucion'] : "";
	$aprobado = (isset($_POST['aprobado'])) ? $_POST['aprobado'] : "";
	
	if($sede) {
		$where = " WHERE u.institution = $sede";
	}
	
	if($aprobado) {
		$join = "INNER";
	} else {
		$join = "LEFT";
	}

	$base = "
		SELECT u.id, u.firstname, u.lastname1, u.lastname2, pu.username, pu.password, g.name AS sexo, u.account_num, u.inst_email, u.comm_email, u.preset_user_id, i.inst_name, u.unit_faculty, l.name  AS perfil, u.active, u.rejected, u.suspended
		FROM users u
		INNER JOIN gender g ON u.gender=g.id
		INNER JOIN inst i ON u.institution = i.id
		INNER JOIN level l ON u.level = l.id
		$join JOIN preset_users pu ON u.preset_user_id=pu.id";
	
	$query = $base.$where.$orden;
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_object($result);
	
	$objPHPExcel = new PHPExcel();
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('CONRICYT logo');
	$objDrawing->setDescription('CONRICYT logo');
	$objDrawing->setPath('../imgs/conricyt.png');
	$objDrawing->setHeight(70);
	$objDrawing->setCoordinates('A1');
	$objDrawing->setOffsetX(10);
	$objDrawing->setOffsetY(10);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('CONRICYT texto');
	$objDrawing->setDescription('CONRICYT texto');
	$objDrawing->setPath('../imgs/conricyt-text.png');
	$objDrawing->setHeight(36);
	$objDrawing->setCoordinates('D1');
	$objDrawing->setOffsetX(10);
	$objDrawing->setOffsetY(40);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('CONACYT logo');
	$objDrawing->setDescription('CONACYT logo');
	$objDrawing->setPath('../imgs/conacyt_banner.png');
	$objDrawing->setHeight(70);
	$objDrawing->setCoordinates('H1');
	$objDrawing->setOffsetX(10);
	$objDrawing->setOffsetY(10);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
	
	$objPHPExcel->getActiveSheet()->getStyle('A'.$fila)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$fila)->getFont()->setSize(18);
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$fila, 'Solicitudes para acceso remoto');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':D'.$fila);
	$fila++;
	
	if($sede) {
		$objPHPExcel->getActiveSheet()->getStyle('A'.$fila)->getFont()->setBold(true);
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$fila, 'Institución seleccionada: '.utf8_encode($row->inst_name));
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':D'.$fila);	
		$fila++;
	}
				
	$objPHPExcel->getActiveSheet()->getStyle('A'.$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$fila, mysql_num_rows($result).' registros al día '.date('d')." de ".$meses[date('n')]." de ".date('Y'));
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':B'.$fila);	
	$fila+=2;
	
	// Encabezado de la tabla
	$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':K'.$fila)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':K'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':K'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('002147');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':K'.$fila)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$fila, 'Nombre completo')
				->setCellValue('B'.$fila, 'Usuario')
				->setCellValue('C'.$fila, 'Contraseña')
				->setCellValue('D'.$fila, 'Sexo')
				->setCellValue('E'.$fila, 'Perfil')
				->setCellValue('F'.$fila, 'Matrícula')
				->setCellValue('G'.$fila, 'Correo institucional')
				->setCellValue('H'.$fila, 'Correo personal')
				->setCellValue('I'.$fila, 'Institución')
				->setCellValue('J'.$fila, 'Unidad')
				->setCellValue('K'.$fila, 'Estatus');
	$fila++;
				
	do {
		$estatus = "";
		if($row->active == 1) {
			$estatus = "Activo";
		}
		if($row->rejected == 1) {
			$estatus = "Rechazado";
		}
		if($row->suspended == 1) {
			$estatus = "Suspendido";
		}
		if(mysql_num_rows($result) > 0 && !$row->preset_user_id) {
			$estatus = "Pendiente";
		}
		$setColor = ($fila > 1 && $fila % 2 == 1) ? true : false;
		
		if($setColor) {
			$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':K'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CDCDCD');
		}
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, utf8_encode(trim(ucwords(strtolower($row->firstname))." ".ucwords(strtolower($row->lastname1))." ".ucwords(strtolower($row->lastname2)))))
			->setCellValue('B'.$fila, $row->username)
			->setCellValue('C'.$fila, $row->password)
			->setCellValue('D'.$fila, ucfirst($row->sexo))
			->setCellValue('E'.$fila, utf8_encode($row->perfil))
			->setCellValue('F'.$fila, $row->account_num)
			->setCellValue('G'.$fila, $row->inst_email)
			->setCellValue('H'.$fila, $row->comm_email)
			->setCellValue('I'.$fila, utf8_encode($row->inst_name))
			->setCellValue('J'.$fila, utf8_encode($row->unit_faculty))
			->setCellValue('K'.$fila, utf8_encode($estatus));
		
		$objPHPExcel->getActiveSheet()->getCell('F'.$fila)->setDataType(PHPExcel_Cell_DataType::TYPE_STRING);
		
		$fila++;
	} while($row = mysql_fetch_object($result));
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
	
	// Nombre de la hoja
	$objPHPExcel->getActiveSheet()->setTitle('Acceso remoto');
	
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="Acceso_Remoto_' . date('d_m_Y') . '.xlsx"');
	header('Cache-Control: max-age=0');
	
	mysql_free_result($result);
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	ob_clean();
	$objWriter->save('php://output');
?>