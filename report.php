<?php //include 'tpl/header.php.tpl';?>

<?php
include 'class/PHPExcel.php';
include 'class/PHPExcel/Writer/Excel2007.php';
include 'class/class.php';

$users = new Users;
$users = $users->getUserforReport();

//include 'class/class.php';

//error_reporting(E_ALL);

//$users = new Users;
//$users = $users->getUsers();

?>
	
<?php 
//ob_end_clean();
//mb_internal_encoding("WINDOWS-1251");
//echo mb_internal_encoding();


$excel = new PHPExcel();

$excel->getProperties()->setTitle("Test22");


/*$excel->getProperties()->setSubject();
$excel->getProperties()->setCreator();
$excel->getProperties()->setManager();
$excel->getProperties()->setCompany()
$excel->getProperties()->set
$excel->getProperties()->set
$excel->getProperties()->set
$excel->getProperties()->set
$excel->getProperties()->set*/

$excel->setActiveSheetIndex(0);

$sheet = $excel->getActiveSheet();
$sheet->setTitle("Test22");
$sheet->setCellValue("A1", "PFRPFR");
$sheet->mergeCells("C2:D5");
$sheet->getDefaultStyle()->getFont()->setName('Arial');
$sheet->getDefaultStyle()->getFont()->setSize(24);
$xls_data = ob_get_clean();
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=file.xls");
echo $xls_data;
?>
<?echo $users;?>
<?

//print_r($sheet);
//header("Expires: Mon, 24 March 2020");
//header("Last-Modified: today");
//header("Cache-Control: no-cache, must-revalidate");
//header("Pragma: no-cache");

//$objWriter = PHPExcel_IOFactory::createWriter($excel, "Excel5");
//$objWriter = new PHPExcel_Writer_Excel2007($excel);
//$objWriter->save('php://output');


//exit();


?>