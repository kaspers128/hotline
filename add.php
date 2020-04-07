<!Doctype html>
<head>
	<title>�������� ������</title>
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
		<label for="snils">�����</label>
		<input type="text" id="snils" placeholder="123-456-789 99" name="snils" required>
	</div>
	<div class="add__item">	
		<label for="full_name">���</label>
		<input type="text" id="full_name" placeholder="��������� ������� �����������" name="full_name" required>
		<div class="no-snils">����� �� ������!</div>
	</div>
	<div class="add__item">
		<label for="district">�����</label>
		<select id="district" name="district" required>
			<option disabled="" selected="">�������� �����</option>
			<?php foreach($districts as $district) { ?>
				<option data-code="<?=$district['code'];?>" value="<?=$district['name'];?>"><?=$district['name'];?></option>
			<?php } ?>
		</select>
	</div>
	<!--div class="add__item">
		<label for="date_obr">���� ���������</label>
		<input type="text" id="date_obr" name="date_obr" value="<php echo date('d.m.Y');?>">
	</div-->
	<div class="add__item">
		<label for="topic">����</label>
		<select id="topic" name="topic" required>
			<option disabled="" selected="">�������� ����</option>
			<?php foreach($topics as $topic) { ?>
				<option data-id="<?=$topic['id'];?>" value="<?=$topic['topic_name'];?>"><?=$topic['topic_name'];?></option>
			<?php } ?>
		</select>
	</div>
	<div class="add__item">
		<label for="sub_topic">�������</label>
		<select id="sub_topic" name="sub_topic" required>
			<option disabled="" selected="">�������� �������</option>
			<option disabled="">�������� ������� ����</option>
		</select>
	</div>
	<div class="add__item">
		<label for="type_cons">��� ������������</label>
		<select id="type_cons" name="type_cons" required>
			<option disabled="" selected="">�������� ��� ������������</option>
			<option value="���������������� �� ����� ��������, �������� � ����������� ���">���������������� �� ����� ��������, �������� � ����������� ���</option>
			<option value="������������ �� ��������, �������� � ����������� ���, � ��������������� ������������ ���������� � �������������� �������� �����">������������ �� ��������, �������� � ����������� ���, � ��������������� ������������ ���������� � �������������� ��������� �����</option>
		</select>
	</div>
	<div class="add__item">
		<label for="result_cons">���������</label>
		<select id="result_cons" name="result_cons" required>
			<option disabled="" selected="">�������� ���������</option>
			<option value="������������ ������������">������������ ������������</option>
			<option value="��������� ������� �� ����� � ��������������� ����� ���">��������� ������� �� ����� � ��������������� ����� ���</option>
			<option value="������ ��������� �� ����������� ����������� ������ (���������������� ������ ���)">������ ��������� �� ����������� ����������� ������ (���������������� ������ ���)</option>
			<option value="���������� ���������� ����������� ���������� ��������� �  ��������������� ����� ���">���������� ���������� ����������� ���������� ��������� �  ��������������� ����� ���</option>
			<option value="����������� �������� ����� � ����������� (��������� ��������������)">����������� �������� ����� � ����������� (��������� ��������������)</option>
		</select>
		
	</div>
	<div class="add__item">
		<label for="duration_talk">������������ ���������</label>
		<input type="text" id="duration_talk" name="duration_talk" placeholder="��-��" required>
	</div>
	<div class="add__item">
		<label for="operator_name">��������</label>
		<select id="operator_name" name="operator_name" required>
			<option disabled="" selected="">�������� ���������</option>
			<?php foreach($operators as $operator) { ?>
				<option data-id="<?=$operator['id'];?>" value="<?=$operator['name'];?>"><?=$operator['name'];?></option>
			<?php } ?>
		</select>
	</div>
	<div style="display: none;">
		<input type="hidden" name="operator_code" value="���������� ���������������� ������ ���, �������������� ������ ��������� ������� �����">
	</div>
	<!--div class="add__item">
		<label for="operator_code">��� ���������</label>
		<select id="operator_code" name="operator_code" >
			<option disabled="" selected="">�������� ��� ���������</option>
			<option value="�������� �������-������">�������� �������-������</option>
			<option value="���������� ���������������� ������ ���, �������������� ������ ��������� ������� �����">���������� ���������������� ������ ���, �������������� ������ ��������� �������� �����</option>
		</select>
	</div-->
</div>
<div class="valid">
	<input type="checkbox" name="valid" />
	<span>��������������</span>
</div>
<div class="add__btn">
	<input type="submit" class="btn success" value="��������">
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
			//������� �� �������� �����
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
				console.log('����� �� ������ � ���� ���');
			}
		},
		complete: function(){
			console.log("����� � ���� ��� ��������");
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
			//������� �� �������� �����
		},
		success: function(json){
			if (json.result){
				$("#sub_topic").html(json.html)
			}else{
				console.log(json.result);
			}
		},
		complete: function(){
			//���������
		}
	});
})

</script>
</body>
</html>