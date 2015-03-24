<?php
session_start();
//error_reporting(0);

// Función necesaria para evitar error al crear XLSX
ob_start();
require_once('config.php');
require_once 'lib/phpexcel/Classes/PHPExcel.php';

$query = "SELECT u.id, firstname, lastname1, lastname2, e.entidad, g.name, comm_email, inst_email, account_num, inst_name, cd.delegacion, clave_est_org, unit_faculty, username, password, fecha_reg ";
$query .= "FROM users u JOIN inst i ON u.institution = i.id ";
$query .= "LEFT JOIN preset_users pu ON u.preset_user_id = pu.id ";
$query .= "JOIN cat_deleg_imss cd ON u.deleg_imss = cd.id ";
$query .= "JOIN entidad e ON u.entidad = e.id ";
$query .= "JOIN gender g ON u.gender = g.id ";
$query .= "WHERE u.institution = 475 AND active = 0 AND rejected = 0 AND deleg_imss IS NOT NULL";
$users = $pdo->query($query)->fetchAll();
$fila = 1;

// Se crea el objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Encabezado de la tabla
	$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':M'.$fila)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':M'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':M'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('002147');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':M'.$fila)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$fila, 'ID')
				->setCellValue('B'.$fila, 'Apellido paterno')
				->setCellValue('C'.$fila, 'Apellido materno')
				->setCellValue('D'.$fila, 'Nombre')
				->setCellValue('E'.$fila, 'Entidad')
				->setCellValue('F'.$fila, 'Sexo')
				->setCellValue('G'.$fila, utf8_encode('Matrícula'))
				->setCellValue('H'.$fila, 'Correo personal')
				->setCellValue('I'.$fila, 'Correo institucional')
				->setCellValue('J'.$fila, utf8_encode('Delegación IMSS'))
				->setCellValue('K'.$fila, 'Clave Est Org')
				->setCellValue('L'.$fila, utf8_encode('Adscripción'))
				->setCellValue('M'.$fila, 'Fecha de registro');

// Se llena con los datos
foreach($users as $user) {
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.++$fila, $user['id'])
				->setCellValue('B'.$fila, $user['lastname1'])
				->setCellValue('C'.$fila, $user['lastname2'])
				->setCellValue('D'.$fila, $user['firstname'])
				->setCellValue('E'.$fila, $user['entidad'])
				->setCellValue('F'.$fila, $user['name'])
				->setCellValue('G'.$fila, $user['account_num'])
				->setCellValue('H'.$fila, $user['comm_email'])
				->setCellValue('I'.$fila, $user['inst_email'])
				->setCellValue('J'.$fila, $user['delegacion'])
				->setCellValue('K'.$fila, $user['clave_est_org'])
				->setCellValue('L'.$fila, $user['unit_faculty'])
				->setCellValue('M'.$fila, $user['fecha_reg']);
}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
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
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);


// Nombre de la hoja
$objPHPExcel->getActiveSheet()->setTitle('Registros');


// Define la primer hoja del libro como activa
$objPHPExcel->setActiveSheetIndex(0);


// Modificación de los headers para que se abra el XLSX
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="IMSS_Pendientes_'.date('d_m_Y').'"');
header('Cache-Control: max-age=0');
// Fix para IE9
header('Cache-Control: max-age=1');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

// Función necesaria para evitar error al crear XLSX
ob_clean();

// Salida del Excel
$objWriter->save('php://output');
?>