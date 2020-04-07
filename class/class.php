<?php

/*
	Класс подключения к БД
*/
class DB{
	//Подключаю созданную мной БД
	public function connectionDB(){
		$connection = $params = FALSE;
		$connection = 'DRIVER={IBM DB2 ODBC DRIVER};DATABASE=HOTLINE;HOSTNAME=XXXXXXXX;PORT=50000;PROTOCOL=TCPIP;UID=XXXXXX;PWD=XXXXXX';

		$connection = db2_connect($connection, '', '');
		if (!$connection) {
			echo 'Не удалось подключиться к БД HOTBD';
			exit();
			return FALSE;
		}
		return $connection;
	}
	
	//Подключаю уже существующую БД, для подгрузки ФИО при вводе СНИЛСа
	public function connNVP(){
		$conn = FALSE;
		$conn = 'DRIVER={IBM DB2 ODBC DRIVER};DATABASE=ROS;HOSTNAME=XXXXXXX;PORT=50000;PROTOCOL=TCPIP;UID=XXXXX;PWD=XXXXXXX';
		
		$conn = db2_connect($conn, '', '');
		
		if (!$conn){
			echo 'Не удалось подключиться к БД HOTBD';
			exit();
			return FALSE;
		}
		
		return $conn;
	}
	
}


/*
	Класс всех добавленных записей(пользователей)
*/
class Users {

//Получаем все записи с таблицы USERS
public function getUsers(){
	//Подключение к БД через создание экземляра класса БД
	$conn = new DB;
	$conn = $conn->connectionDB();
	
	// Для пагинации, указываем сколько записей на странице и вычисляем Начальные и Конечные значения
	$limit = 15;
	$page = $_GET['page'];
	if (empty($page) || $page < 1) $page = 1;
	
	//Запрос для определения общего количества записей в таблице
	//Переделать 
	$sql2 = "SELECT * FROM USERS";
	$_stmt2 = db2_prepare($conn, $sql2);
	db2_execute($_stmt2);
	$totalPost = 0;
	while ($row2 = db2_fetch_assoc($_stmt2)) {
		$totalPost++;
	}
	if ($page > ceil($totalPost/$limit)) $page = ceil($totalPost/$limit);	
	$start = $limit * $page - $limit;
	$end = $limit * $page + 1;
	
	
	//	Запрос для вывода данных на страницу РЕЕСТР
	$sql = "SELECT * FROM (
		SELECT row_number() over() as RN, FULL_NAME, SNILS, DISTRICT, DATE_OBR, TOPIC, SUB_TOPIC, TYPE_CONS, RESULT_CONS, DURATION_TALK, OPERATOR_NAME, OPERATOR_CODE, VALID
		FROM USERS
		ORDER BY DATE_OBR DESC
	)
	WHERE RN > $start AND RN < $end ORDER BY DATE_OBR DESC";
	
	$_stmt = db2_prepare($conn, $sql);
	$i = 1;

	if ($_stmt) { 
		if (db2_execute($_stmt)) {
			while ($row = db2_fetch_assoc($_stmt)) {
				//echo "<pre>";print_r($row);echo "</pre>";
				$resQuery['user'][$i]['full_name'] = $row['FULL_NAME'];
				$resQuery['user'][$i]['snils'] = $row['SNILS'];
				$resQuery['user'][$i]['district'] = $row['DISTRICT'];
				$resQuery['user'][$i]['date_obr'] = date("d.m.Y H:i:s", strtotime($row['DATE_OBR']));
				$resQuery['user'][$i]['topic'] = $row['TOPIC'];
				$resQuery['user'][$i]['sub_topic'] = $row['SUB_TOPIC'];
				$resQuery['user'][$i]['type_cons'] = $row['TYPE_CONS'];
				$resQuery['user'][$i]['result_cons'] = $row['RESULT_CONS'];
				$resQuery['user'][$i]['duration_talk'] = $row['DURATION_TALK'];
				$resQuery['user'][$i]['operator_name'] = $row['OPERATOR_NAME'];
				$resQuery['user'][$i]['operator_code'] = $row['OPERATOR_CODE'];
				$resQuery['user'][$i]['rn'] = $row['RN'];
				$resQuery['user'][$i]['valid'] = $row['VALID'];
				$i++;
			}
		}
		db2_free_stmt($_stmt);
	}
	
	//Проверяем есть ли смысл выводить пагинацию
	$resQuery['pag'] = false;
	if ($totalPost > $limit){
		$countPage = ceil($totalPost / $limit);
		$resQuery['pag'] = $countPage;
	}
	//echo "<pre>";print_r($resQuery);echo "</pre>";
	return $resQuery;
}

//Этот метод пока можно не смотреть
public function getUserforReport(){
	$conn = new DB;
	$conn = $conn->connectionDB();
	$sql = "SELECT * FROM USERS";
	$_stmt = db2_prepare($conn, $sql);
	
	$html = "<table><thead>
		<tr>
			<th>#</th>
			<th>ФИО</th>
			<th>СНИЛС</th>
			<th>Дата обращения</th>
			<th>Тема</th>
			<th>Подтема</th>
			<th>Тип консультации</th>
			<th>Резульат консультации</th>
			<th>Оператор</th>
			<th>Код оператора</th>
		</tr>
	</thead>
	<tbody>";
	
	if ($_stmt) { 
		if (db2_execute($_stmt)) {
			while ($row = db2_fetch_assoc($_stmt)) {
				//print_r($row['FULL_NAME']);
				//$name = iconv("Windows-1251", "UTF-8".$row['FULL_NAME']);
				$html .= "<tr>";
				$html .= "<td>".$i."</td>";
				$html .= "<td>".$row['FULL_NAME']."</td>";
				$html .= "<td>".$row['SNILS']."</td>";
				$html .= "<td>".date("d.m.Y H:i:s", strtotime($row['DATE_OBR']))."</td>";
				$html .= "<td>".$row['TOPIC']."</td>";
				$html .= "<td>".$row['SUB_TOPIC']."</td>";
				$html .= "<td>".$row['TYPE_CONS']."</td>";
				$html .= "<td>".$row['RESULT_CONS']."</td>";
				$html .= "<td>".$row['OPERATOR_NAME']."</td>";
				$html .= "<td>".$row['OPERATOR_CODE']."</td>";
				$html .= "<tr>";
				$i++;
			}
		}
		db2_free_stmt($_stmt);
	}
	
	return $html;
}

//Проверяем на безопасность введеные данные
private function checkInput($str){
	$str = trim(stripcslashes(htmlspecialchars($str)));
	$str = iconv("UTF-8", "WINDOWS-1251", $str);
	return $str;
}

//Метод добавления записи в таблицу
public function addUser($date_obr, $full_name, $snils, $district, $topic, $sub_topic, $type_cons, $result_cons, $duration_talk, $operator_name, $operator_code, $valid){
	
	$full_name = $this->checkInput($full_name);
	$snils 	   = $this->checkInput($snils);
	$district  = $this->checkInput($district);
	$topic	   = $this->checkInput($topic);
	$sub_topic = $this->checkInput($sub_topic);
	$type_cons = $this->checkInput($type_cons);
	$result_cons = $this->checkInput($result_cons);
	$duration_talk = $this->checkInput($duration_talk);
	$operator_name = $this->checkInput($operator_name);
	$operator_code = $this->checkInput($operator_code);


	//$full_name = iconv("ISO-8859-1", "UTF-8", $full_name);
	$sql = "INSERT INTO USERS(DATE_OBR, FULL_NAME, SNILS, DISTRICT, TOPIC, SUB_TOPIC, TYPE_CONS, RESULT_CONS, DURATION_TALK, OPERATOR_NAME, OPERATOR_CODE, VALID) VALUES ('$date_obr', '$full_name', '$snils', '$district', '$topic', '$sub_topic', '$type_cons', '$result_cons', '$duration_talk', '$operator_name', '$operator_code', '$valid')";
	//$sql = "INSERT INTO USERS(SNILS) VALUES ('$snils')";
	$conn = new DB;
	$conn = $conn->connectionDB();
	$stmt = db2_prepare($conn, $sql);
	//var_dump(db2_execute($stmt));
	//exit;

	if ($stmt) {
		if (db2_execute($stmt)) {
			db2_free_stmt($stmt);
			unset($stmt);
			return true;
		}
		
	}
	
	return false;
}


}

//Класс для работы с Темами и Подтема
class Topic{

//Полуаем все темы для вывода на странице добавления Товара
public function getTopic(){
	
	$sql = 'SELECT * FROM TOPIC';
	$conn = new DB;
	$conn = $conn->connectionDB();
	$_stmt = db2_prepare($conn, $sql);
	$i = 1;
	
	if ($_stmt) { 
		if (db2_execute($_stmt)) {
			while ($row = db2_fetch_assoc($_stmt)) {
				$resQuery[$i]['id'] = $row['ID'];
				$resQuery[$i]['topic_name'] = $row['TOPIC_NAME'];
				$i++;
			}
		}
		db2_free_stmt($_stmt);
	}

	return $resQuery;
}

// Получаем подтемы в зависимости от выбранной темы
public function getSubTopic($topic_id){
	$sql = 'SELECT * FROM SUB_TOPIC WHERE TOPIC_ID = '.$topic_id;
	$conn = new DB;
	$conn = $conn->connectionDB();
	$_stmt = db2_prepare($conn, $sql);
	$i = 1;
	
	if ($_stmt) {
		if (db2_execute($_stmt)) {
			while ($row = db2_fetch_assoc($_stmt)) {
				$resQuery[$i]['sub_topic_name'] = $row['SUB_TOPIC_NAME'];
				$i++;
			}
		}
		db2_free_stmt($_stmt);
	}

	return $resQuery;
}

}

/*
	Класс фильтра на странице Реестра
	тут вообще хз, правильно ли так делать.
*/
class Filter{

public function filterForm($filterDate, $filterName, $filterOperator, $filterSnils){
	
	//$filterDate = iconv("UTF-8", "WINDOWS-1251", $filterDate);
	$filterOperator = iconv("UTF-8", "WINDOWS-1251", $filterOperator);
	$filterName = iconv("UTF-8", "WINDOWS-1251", $filterName);
	
	
	if (!empty($filterOperator) && ($filterOperator) != "all"){
		$filters = ' WHERE ';
		$filters .= "OPERATOR_NAME = '{$filterOperator}'";
	} 
	
	if (!empty($filterName) && ($filterName) != ""){
		if ($filters != ""){
			$filters .= " AND ";
		}else{
			$filters = ' WHERE ';
		}
		$filters .= "FULL_NAME LIKE '%{$filterName}%'";
	}
	
	if (!empty($filterSnils) && ($filterSnils) != ""){
		if ($filters != ""){
			$filters .= " AND ";
		}else{
			$filters = ' WHERE ';
		}
		$filters .= "SNILS = '{$filterSnils}'";
	}
	
	if (!empty($filterDate) && ($filterDate) != ""){
		if ($filters != ""){
			$filters .= " AND ";
		}else{
			$filters = ' WHERE ';
		}
		$filterDate = date("Y-m-d", strtotime($filterDate));
		$filters .= "DATE_OBR LIKE '%{$filterDate}%'";
	}
	//$filters = iconv("Windows-1251", mb_detect_encoding($filters), $filters);
	//return $filters;
	$sql = "SELECT * FROM USERS".$filters." ORDER BY DATE_OBR DESC";// WHERE OPERATOR_NAME = '{$filterOperator}'";
	$conn = new DB;
	$conn = $conn->connectionDB();
	$_stmt = db2_prepare($conn, $sql);
	//формируем таблицу для замены уже сущетвующей таблицы на страницы РЕЕСТР
	$html = "";
	$i = 1;
	if ($_stmt) {
		if (db2_execute($_stmt)) {
			while ($row = db2_fetch_assoc($_stmt)) {
				$html .= "<tr>";
				//$html .= "<td>".$i."</td>";
				$html .= "<td class='full_name'>".$row['FULL_NAME']."</td>";
				$html .= "<td>".$row['SNILS']."</td>";
				$html .= "<td>".$row['DISTRICT']."</td>";
				$html .= "<td>".date("d.m.Y H:i:s", strtotime($row['DATE_OBR']))."</td>";
				$html .= "<td>".$row['TOPIC']."</td>";
				$html .= "<td>".$row['SUB_TOPIC']."</td>";
				$html .= "<td>".$row['TYPE_CONS']."</td>";
				$html .= "<td>".$row['RESULT_CONS']."</td>";
				$html .= "<td>".$row['DURATION_TALK']."</td>";
				$html .= "<td>".$row['OPERATOR_NAME']."</td>";
				//$html .= "<td>".$row['OPERATOR_CODE']."</td>";
				$html .= "<td>".$row['VALID']."</td>";
				$html .= "</tr>";
				$i++;
			}
		}
		db2_free_stmt($_stmt);
	}
	if ($html == "") {
		$html = iconv("Windows-1251", "UTF-8","<div class='noresultForm'>По заданным параметрам ничего не найдено.</div>");
	} else {
		$html = iconv("Windows-1251", mb_detect_encoding($html), $html);
	}
	//echo $html;
	return $html;
}

}

/*
	Класс для подгрузки ФИО после введенного СНИЛСа
*/
class NVP{
//119-123-102 12
public function getUserInfo($snils){
	if ($snils == "100-000-000 00"){
		$resQuery['name'] = "Аноним";
		return $resQuery['name'];
	}
	
	
	$sql = "SELECT REP_UP_FIO FROM PF.MAN WHERE NPERS = '$snils'";
	
	$conn = new DB;
	$conn = $conn->connNVP();
	
	$_stmt = db2_prepare($conn, $sql);
	
	if ($_stmt) { 
		if (db2_execute($_stmt)) {
			while ($row = db2_fetch_assoc($_stmt)) {
				$resQuery['name'] = $row['REP_UP_FIO'];
			}
		}
		db2_free_stmt($_stmt);
	}
	
	return $resQuery['name'];
}

}

/*
	Класс операторов
*/
class Operator{

	public function getOperators(){
		$sql = "SELECT * FROM OPERATOR";
		$conn = new DB;
		$conn = $conn->connectionDB();
		$_stmt = db2_prepare($conn, $sql);
		$i = 1;
		
		if ($_stmt) { 
			if (db2_execute($_stmt)) {
				while ($row = db2_fetch_assoc($_stmt)) {
					$resQuery[$i]['name'] = $row['OPERATOR_NAME'];
					$i++;
				}
			}
			db2_free_stmt($_stmt);
		}
		
		//print_r($resQuery);
		return $resQuery;
		
	}

}

/*
	Класс районов
*/
class District {

	public function getDistricts(){
		$sql = "SELECT * FROM DISTRICT";
		$conn = new DB;
		$conn = $conn->connectionDB();
		$_stmt = db2_prepare($conn, $sql);
		$i = 1;
		
		if ($_stmt) { 
			if (db2_execute($_stmt)) {
				while ($row = db2_fetch_assoc($_stmt)) {
					$resQuery[$i]['code'] = $row['DISTRICT_CODE'];
					$resQuery[$i]['name'] = $row['DISTRICT_NAME'];
					$i++;
				}
			}
			db2_free_stmt($_stmt);
		}
		
		//print_r($resQuery);
		return $resQuery;
	}

}



?>