<?php 

$sovet = array('Шома тигр!','Как жизнь?', 'Набери Шоме!');

$rand = rand(0, count($sovet) - 1);

$now_sovet = $sovet[$rand];
?>
<header>
	<div class="container">
		<div class="top">			
			<div class="logo">ГУ ОПФР по РД</div>
			<nav class="navmenu">
				<ul class="menu">
					<li><a href="/hotline-report/">Реестр</a></li>
					<li><a href="add.php">Добавить</a></li>
					<li><a href="report.php">Получить отчет</a></li>
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