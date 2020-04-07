<?php 

//Если я пока не использую MVC-модель, то вот такое подключение файлов на каждой странице неизбежно?
include '../class/class.php';
$users = new Users;

if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST'){
	
	date_default_timezone_set("Etc/GMT-3");
	$date_obr = date('Y-m-d H:i:s');
	
	if (isset($_POST['valid']) && ($_POST['valid'] != '')){
		$valid = '1';
	}else{
		$valid = '0';
	}

	if (isset($_POST['full_name']) && ($_POST['full_name'] != '')){
		$full_name = $_POST['full_name'];
	}else{
		$full_name = false;
	}
	
	if (isset($_POST['snils']) && ($_POST['snils'] != '')){
		$snils = $_POST['snils'];
	}else{
		$snils = false;
	}
	
	if (isset($_POST['district']) && ($_POST['district'] != '')){
		$district = $_POST['district'];
	}else{
		$district = false;
	}
	
	if (isset($_POST['topic']) && ($_POST['topic'] != '')){
		$topic = $_POST['topic'];
	}else{
		$topic = false;
	}
	
	if (isset($_POST['sub_topic']) && ($_POST['sub_topic'] != '')){
		$sub_topic = $_POST['sub_topic'];
	}else{
		$sub_topic = false;
	}
	
	if (isset($_POST['type_cons']) && ($_POST['type_cons'] != '')){
		$type_cons = $_POST['type_cons'];
	}else{
		$type_cons = false;
	}
	
	if (isset($_POST['result_cons']) && ($_POST['result_cons'] != '')){
		$result_cons = $_POST['result_cons'];
	}else{
		$result_cons = false;
	}
	
	if (isset($_POST['duration_talk']) && ($_POST['duration_talk'] != '')){
		$duration_talk = $_POST['duration_talk'];
	}else{
		$duration_talk = false;
	}
	
	if (isset($_POST['operator_name']) && ($_POST['operator_name'] != '')){
		$operator_name = $_POST['operator_name'];
	}else{
		$operator_name = false;
	}
	
	if (isset($_POST['operator_code']) && ($_POST['operator_code'] != '')){
		$operator_code = $_POST['operator_code'];
	}else{
		$operator_code = false;
	}



}else{
	echo json_encode(array('result'=>'false2', 'text'=>"Error1"));
}

if ($full_name){
	$json = array();
	$json['result'] = true;
	$json['text'] = "Success!";
	$users->addUser($date_obr, $full_name, $snils, $district, $topic, $sub_topic, $type_cons, $result_cons, $duration_talk, $operator_name, $operator_code, $valid);
	echo json_encode($json);
}else{
	//И еще есть вопрос по обработке всех ошибок
	echo json_encode(array('result'=>false, 'text'=>"Error2!"));
}



?>