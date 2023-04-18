<?php
/**
 * @var CMain $APPLICATION
 */
use Bitrix\Main\Localization\Loc;
use Up\Litlab\API\User;

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

		<?php
			$userApi = new User;
			$userId = $userApi->getUserId($_SESSION['USER']);
		?>
		<div class="navbar-end">
			<div class="navbar-item">
				<div class="buttons">

					<?if (isset($_SESSION['USER'])):?>
					<a href="/user/<?=$userId?>/" class="button is-primary"><?=$_SESSION['USER']?></a>
					<a href="/logout/" class="button is-light">Выйти</a>
					<? else:?>
					<a class="button is-primary" href="/auth/">
						Войти
					</a>
					<a class="button is-light" href="/register/">
						Зарегистрироваться
					</a>
					<?endif;?>
				</div>
			</div>
		</div>
	</nav>
</header>
<section class="container">
