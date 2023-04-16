<?php
/**
 * @var CMain $APPLICATION
 */
use Bitrix\Main\Localization\Loc;
?>
<!doctype html>
<html lang="<?= LANGUAGE_ID; ?>">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="/local/templates/litlab/template_styles.css" rel="stylesheet" type="text/css">

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
				<img src="\local\modules\up.litlab\install\templates\litlab\images\logo.png" style="height: 130px;" alt="logo">
			</a>
		</div>

		<div id="navbarBasicExample" class="navbar-menu">
			<a href="/" class="navbar-item " >Книжные полки</a>
			<a href="/books/" class="navbar-item" >Книги</a>
		</div>

		<div class="navbar-end">
			<div class="navbar-item">
				<div class="buttons">
					<a class="button is-primary" href="/user/1/">
						Войти
					</a>
					<a class="button is-light" href="/">
						Зарегистрироваться
					</a>
				</div>
			</div>
		</div>
	</nav>
</header>
<section class="container">
