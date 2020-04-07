<?php 

include "../class/class.php";



if (isset($_GET['snils']) && ($_GET['snils'] != '')){
	$snils = $_GET['snils'];
}else{
	$snils = false;
}

$json = array();
$user = new NVP;
$userName = $user->getUserInfo($snils);
$userName = iconv("Windows-1251", mb_detect_encoding($userName), $userName);
//var_dump($userName);
if ($snils){
	if($userName){
		$json['result'] = true;
		$json['name'] = $userName;
		echo json_encode($json);
	}else{
		$json['result'] = false;
		$json['name'] = "Error!";
		echo json_encode($json);
	}
}else{
	$json['result'] = false;
	$json['name'] = "Snils Error!";
	echo json_encode($json);
}
?>