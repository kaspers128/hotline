<?php 

$sovet = array('���� ����!','��� �����?', '������ ����!');

$rand = rand(0, count($sovet) - 1);

$now_sovet = $sovet[$rand];
?>
<header>
	<div class="container">
		<div class="top">			
			<div class="logo">�� ���� �� ��</div>
			<nav class="navmenu">
				<ul class="menu">
					<li><a href="/hotline-report/">������</a></li>
					<li><a href="add.php">��������</a></li>
					<li><a href="report.php">�������� �����</a></li>
				</ul>
			</nav>
		</div>
	</div>
</header>
<div class="loading" id="loading">
	<div class="loader" id="loader">
		<span></span>
		<span></span>
		<span></span>
		<span></span>
	</div>
	<div class="sovet"><?=$now_sovet;?></div>
</div>
<div class="loading mini">
	<div class="loader">
		<span></span>
		<span></span>
		<span></span>
		<span></span>
	</div>
</div>