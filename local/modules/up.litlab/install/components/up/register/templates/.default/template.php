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

<?php if(!empty($arParams['ERROR'])): ?>
	<p><?= Loc::getMessage('UP_LITLAB_' . $arParams['ERROR']) ?></p>
<?php endif;?>

<div class="auth-form">
	<div class="auth-form-content">
		<p class="title-form">Регистрация</p>
		<form class="login-form" action="" method="post">
			<label>Логин</label>
			<input required class="auth-input" name="login" type="text">
			<label>Ник</label>
			<input required class="auth-input" name="username" type="text">
			<label>Пароль</label>
			<input required class="auth-input" name="pass" type="password"/>
			<input type="submit" class="auth-form-btn" value="Зарегистрироваться">
<!--			<input type="hidden" name="token" value="--><?//= $token?><!--">-->
		</form>
		<a id="auth-page-btn" href="/auth/">Форма авторизации</a>
	</div>
</div>
