<?php
/**
 * @var CMain $APPLICATION
 */
use Bitrix\Main\Localization\Loc;
?><!doctype html>
<html lang="<?= LANGUAGE_ID; ?>">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php $APPLICATION->ShowTitle(); ?></title>

	<?php
	$APPLICATION->ShowHead();
	?>
</head>
<body>
<?php $APPLICATION->ShowPanel(); ?>

<header>
	<!--главный хедер-->
	<nav class="navbar" role="navigation" aria-label="main navigation">
		<div class="navbar-brand">
			<a class="navbar-item" href="/">
				<img src="logo.png" style="height: 130px;" alt="мимо">
			</a>
		</div>

		<div id="navbarBasicExample" class="navbar-menu">
			<a href="/" class="navbar-item " >Книжные полки</a>
			<a href="/" class="navbar-item" >Книги</a>
		</div>

		<div class="navbar-end">
			<div class="navbar-item">
				<div class="buttons">
					<a class="button is-primary">
						Войти
					</a>
					<a class="button is-light">
						Зарегистрироваться
					</a>
				</div>
			</div>
		</div>
	</nav>

	<!--		второй хедер-->
	<div class="title">
		<h1>Создайте свою виртуальную книжную полку и найдите подборки, которые подходят именно вам</h1>
		<h3>Погрузись в мир литературы прямо сейчас</h3>
	</div>

	<div class="search">
		<p class="search-wrapper input-wrapper">
			<label>
				<input class="search-input" type="text" placeholder="Найти полку...">
			</label>
		</p>
		<p class="search-wrapper">
			<button class="button is-info">
				Поиск
			</button>
		</p>
	</div>
</header>

<section class="section">
	<div class="container">
