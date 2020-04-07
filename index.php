<!Doctype html>
<head>
	<title>�����. ������� �����</title>
	<?php include 'tpl/head.php.tpl';?>
</head>
<body>
<?php include 'tpl/header.php.tpl';?>

<?php

include 'class/class.php';

$users = new Users;
$users = $users->getUsers();

$operators = new Operator;
$operators = $operators->getOperators();
?>
<main>
<div class="container">
	<div id="filter" class="filter">
		<h2>������</h2>
		<form class="filterForm" method="POST">
			<div class="filter-cont">
				<div class="filter__item">
					<label for="filterDate">���� ���������</label>
					<input type="text" class="date_obr filter__input forminput" id="filterDate" name="filterDate" />
				</div>
				<div class="filter__item">
					<label for="filterName">���</label>
					<input type="text" class="filter__input forminput" id="filterName" name="filterName" />
				</div>
				<div class="filter__item">
					<label for="filterSnils">�����</label>
					<input type="text" class="snils filter__input forminput" id="filterSnils" name="filterSnils" />
				</div>
				<div class="filter__item">
					<label for="filterOperator">��������</label>
					<select id="filterOperator" name="filterOperator" class="filter__input forminput" >
						<option disabled="" selected="">�������� ���������</option>
						<option value="all">��� ���������</option>
						<?php foreach($operators as $operator) { ?>
							<option data-id="<?=$operator['id'];?>" value="<?=$operator['name'];?>"><?=$operator['name'];?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="filter-btn">
				<a onclick="$('.filterForm').trigger('reset');$('.filter__input').change();" class="btn danger">�������� ������</a>
			</div>
		</form>
	</div>
	<table class="table table-user">
	<thead>
		<tr>
			<!--th style="width: 2%;">#</th-->
			<th style="width: 12%;">���</th>
			<th style="width: 12%;">�����</th>
			<th style="width: 12%;">�����</th>
			<th style="width: 8%;">���� ���.</th>
			<th style="width: 7%;">����</th>
			<th style="width: 10%;">�������</th>
			<th style="width: 12%;">��� ����.</th>
			<th style="width: 10%;">���������</th>
			<th style="width: 3%;">��. ����.</th>
			<th style="width: 10%;">��������</th>
			<!--th style="width: 10%;">��� ���������</th-->
			<th style="width: 2%;">�����.</th>
		</tr>
	</thead>
	<tbody>
	<?
	$i = 1;
	//echo "<pre>";print_r($users);echo "</pre>";
	$pag = $users['pag'];
	foreach($users['user'] as $user){
	?>
	<tr>
		<!--td><=$user['rn'];?></td-->
		<td class="full_name"><?=$user['full_name'];?></td>
		<td><?=$user['snils'];?></td>
		<td><?=$user['district'];?></td>
		<td><?=$user['date_obr'];?></td>
		<td><?=$user['topic'];?></td>
		<td><?=$user['sub_topic'];?></td>
		<td><?=$user['type_cons'];?></td>
		<td><?=$user['result_cons'];?></td>
		<td><?=$user['duration_talk'];?></td>
		<td><?=$user['operator_name'];?></td>
		<!--td><=$user['operator_code'];?></td-->
		<td><?=$user['valid'];?></td>
	</tr>
	<?php
	$i++;
	}


	?>
	</tbody>
	</table>
	<?php if ($pag) { ?>
		<div class="pag">
			<?php for($i = 1; $i <= $pag; $i++) { ?>
				<a href="/hotline-report/index.php?page=<?=$i;?>"><?=$i;?></a>
			<?php } ?>
		</div>
	<?php } ?>

</div>
</main>
<?php include 'tpl/footer.php.tpl';?>

<script>
	var url = location.pathname+location.search;
	if (url == "/hotline-report/") $(".pag a:eq(0)").addClass("active");
	$(".pag a").each(function(){
		var th = $(this).attr("href");
		if (th == url) $(this).addClass("active");
	})
	
	if ($(".snils").length > 0) {
		$(".snils").inputmask("999-999-999 99"); 
	}
	
	$('.date_obr').datepicker({
		language: "ru",
		autoclose: true,
		todayHighlight: true,
		toggleActive: true
	});
	
	
	$(".filter__input").change(function(){
		var tdata = $(this).parents(".filterForm").serialize();
		//console.log(tdata);
		jQuery.ajax({
			url: 'ajax/filterForm.php',
			type: 'post',
			data: tdata,
			dataType: 'json',
			beforeSend: function(){
				jQuery(".loading.mini").css("display", "flex");
				jQuery(".loading.mini .loader").fadeIn("slow");
			},
			success: function(json){
				if (json.result){
					console.log(json.html);
					$(".table-user").find("tbody").html(json.html);
				}else{
					console.log(json.result);
				}
			},
			complete: function(){
				jQuery(".loading.mini").delay(0).fadeOut();
				jQuery(".loading.mini .loader").delay(0).fadeOut("slow");
			}
		});
	})
</script>
</body>
</html>