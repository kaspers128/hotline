<!Doctype html>
<head>
	<title>Добавить запись</title>
	<?php include 'tpl/head.php.tpl';?>
</head>
<body>
<?php include 'tpl/header.php.tpl';?>

<?php

include 'class/class.php';

//mb_internal_encoding("UTF-8");
//echo mb_internal_encoding();

//echo mb_detect_encoding(file_get_contents('add.php'));

$topics = new Topic;
$topics = $topics->getTopic();

$operators = new Operator;
$operators = $operators->getOperators();

$districts = new District;
$districts = $districts->getDistricts();
?>
<main>
<div class="container">

<form class="addUser" method="POST">

<div class="add">
	<div class="add__item">
		<label for="snils">СНИЛС</label>
		<input type="text" id="snils" placeholder="123-456-789 99" name="snils" required>
	</div>
	<div class="add__item">	
		<label for="full_name">Имя</label>
		<input type="text" id="full_name" placeholder="Магомедов Магомед Магомедович" name="full_name" required>
		<div class="no-snils">СНИЛС не найден!</div>
	</div>
	<div class="add__item">
		<label for="district">Район</label>
		<select id="district" name="district" required>
			<option disabled="" selected="">Выберите район</option>
			<?php foreach($districts as $district) { ?>
				<option data-code="<?=$district['code'];?>" value="<?=$district['name'];?>"><?=$district['name'];?></option>
			<?php } ?>
		</select>
	</div>
	<!--div class="add__item">
		<label for="date_obr">Дата обращения</label>
		<input type="text" id="date_obr" name="date_obr" value="<php echo date('d.m.Y');?>">
	</div-->
	<div class="add__item">
		<label for="topic">Тема</label>
		<select id="topic" name="topic" required>
			<option disabled="" selected="">Выберите тему</option>
			<?php foreach($topics as $topic) { ?>
				<option data-id="<?=$topic['id'];?>" value="<?=$topic['topic_name'];?>"><?=$topic['topic_name'];?></option>
			<?php } ?>
		</select>
	</div>
	<div class="add__item">
		<label for="sub_topic">Подтема</label>
		<select id="sub_topic" name="sub_topic" required>
			<option disabled="" selected="">Выберите подтему</option>
			<option disabled="">Выберите сначала тему</option>
		</select>
	</div>
	<div class="add__item">
		<label for="type_cons">Тип консультации</label>
		<select id="type_cons" name="type_cons" required>
			<option disabled="" selected="">Выберите тип консультации</option>
			<option value="консультирование по общим вопросам, входящим в компетенцию ПФР">консультирование по общим вопросам, входящим в компетенцию ПФР</option>
			<option value="консультация по вопросам, входящим в компетенцию ПФР, с предоставлением персональной информации и использованием кодового слова">консультация по вопросам, входящим в компетенцию ПФР, с предоставлением персональной информации и использованием «кодового слова»</option>
		</select>
	</div>
	<div class="add__item">
		<label for="result_cons">Результат</label>
		<select id="result_cons" name="result_cons" required>
			<option disabled="" selected="">Выберите результат</option>
			<option value="Представлена консультация">Представлена консультация</option>
			<option value="Гражданин записан на прием в территориальный орган ПФР">Гражданин записан на прием в территориальный орган ПФР</option>
			<option value="Звонок переведен на специалиста профильного отдела (территориального органа ПФР)">Звонок переведен на специалиста профильного отдела (территориального органа ПФР)</option>
			<option value="Гражданину предложено подготовить письменное обращение в  территориальный орган ПФР">Гражданину предложено подготовить письменное обращение в  территориальный орган ПФР</option>
			<option value="Планируется обратная связь с гражданином (исходящее информирование)">Планируется обратная связь с гражданином (исходящее информирование)</option>
		</select>
		
	</div>
	<div class="add__item">
		<label for="duration_talk">Длительность разговора</label>
		<input type="text" id="duration_talk" name="duration_talk" placeholder="мм-сс" required>
	</div>
	<div class="add__item">
		<label for="operator_name">Оператор</label>
		<select id="operator_name" name="operator_name" required>
			<option disabled="" selected="">Выберите оператора</option>
			<?php foreach($operators as $operator) { ?>
				<option data-id="<?=$operator['id'];?>" value="<?=$operator['name'];?>"><?=$operator['name'];?></option>
			<?php } ?>
		</select>
	</div>
	<div style="display: none;">
		<input type="hidden" name="operator_code" value="специалист территориального органа ПФР, обеспечивающий работу телефонов горячих линий">
	</div>
	<!--div class="add__item">
		<label for="operator_code">Код оператора</label>
		<select id="operator_code" name="operator_code" >
			<option disabled="" selected="">Выберите код оператора</option>
			<option value="оператор контакт-центра">оператор контакт-центра</option>
			<option value="специалист территориального органа ПФР, обеспечивающий работу телефонов горячих линий">специалист территориального органа ПФР, обеспечивающий работу телефонов «горячих линий»</option>
		</select>
	</div-->
</div>
<div class="valid">
	<input type="checkbox" name="valid" />
	<span>Обоснованность</span>
</div>
<div class="add__btn">
	<input type="submit" class="btn success" value="Добавить">
</div>

</form>

</div>

</main>
<?php include 'tpl/footer.php.tpl';?>
<script>

if ($("#snils").length > 0) {
	$("#snils").inputmask("999-999-999 99"); 
}

if ($("#duration_talk").length > 0) {
	$("#duration_talk").inputmask("99-99"); 
}	

$("#snils").change(function(){
	var snils = $(this).val();
	console.log(snils);
	jQuery.ajax({
		url: 'ajax/searchSnils.php?snils='+snils,
		type: 'post',
		data: snils,
		dataType: 'json',
		beforeSend: function(){
			//Функция до отправки формы
		},
		success: function(json){
			if (json.result){
				$("#full_name").val(json.name);
				$(".no-snils").hide();
				console.log(json.name);
			}else{
				//$("#full_name").val('')
				$(".no-snils").slideDown();
				setTimeout(function(){$(".no-snils").slideUp();}, 2000)
				console.log('СНИЛС не найден в базе НВП');
			}
		},
		complete: function(){
			console.log("Поиск в базе НВП завершен");
		}
	});
})

$("#topic").change(function(){
	var topic_id = $(this).find("option:selected").attr("data-id");
	
	jQuery.ajax({
		url: 'ajax/changeSubTopic.php?topic_id='+topic_id,
		type: 'post',
		data: topic_id,
		dataType: 'json',
		beforeSend: function(){
			//Функция до отправки формы
		},
		success: function(json){
			if (json.result){
				$("#sub_topic").html(json.html)
			}else{
				console.log(json.result);
			}
		},
		complete: function(){
			//Завершаем
		}
	});
})

</script>
</body>
</html>