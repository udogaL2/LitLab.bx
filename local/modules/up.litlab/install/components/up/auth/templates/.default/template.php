<?php
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>

<?php
	if (!empty($arParams['ERROR'])):
?>

<p><?= Loc::getMessage('UP_LITLAB_' . $arParams['ERROR']) ?></p>
<?php endif;?>
<div class="auth-form">
	<div class="auth-form-content">
		<p class="title-form">Авторизация</p>
		<form class="login-form" action="" method="post">
			<label>Логин</label>
			<input required class="auth-input" name="login" type="text" id="auth-login" minlength="5" maxlength="62"
				<?
				if (isset($_SESSION['NAME'])):?>
				   value="<?=$_SESSION['NAME']?>"
				<? endif;?>
				>
<!--			<p>--><?//=($_SESSION['NAME']++);?><!--"</p>-->
			<label>Пароль</label>
			<input required class="auth-input" name="pass" type="password" id="auth-pass" minlength="8" maxlength="62"
				<?if (isset($_SESSION['PASSWORD'])):?>
					value="<?=$_SESSION['PASSWORD']?>"
				<? endif;?>>
			<input type="submit"  class="auth-form-btn" value="Войти">
		</form>
		<a id="auth-page-btn" href="/register/">Зарегистрироваться</a>
	</div>
</div>
