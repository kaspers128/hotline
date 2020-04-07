<?php 

include '../class/class.php';
$filterForm = new Filter;

if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST'){

	if (isset($_POST['filterDate']) && ($_POST['filterDate'] != '')){
		$filterDate = $_POST['filterDate'];
	}else{
		$filterDate = false;
	}
	
	if (isset($_POST['filterName']) && ($_POST['filterName'] != '')){
		$filterName = $_POST['filterName'];
	}else{
		$filterName = false;
	}

	if (isset($_POST['filterOperator']) && ($_POST['filterOperator'] != '')){
		$filterOperator = $_POST['filterOperator'];
	}else{
		$filterOperator = false;
	}
	
	if (isset($_POST['filterSnils']) && ($_POST['filterSnils'] != '')){
		$filterSnils = $_POST['filterSnils'];
	}else{
		$filterSnils = false;
	}
	
}else{
	echo json_encode(array('result'=>'false2', 'text'=>"Error1"));
}

if ($filterName || $filterOperator || 1){
	$json = array();
	$json['result'] = true;
	$json['html'] = $filterForm->filterForm($filterDate, $filterName, $filterOperator, $filterSnils);
	echo json_encode($json);
}else{
	echo json_encode(array('result'=>false, 'text'=>"Error2!"));
}



?>