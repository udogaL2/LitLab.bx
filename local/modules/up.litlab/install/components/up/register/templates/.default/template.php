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

<?php if(!empty($arResult['ERROR']))
{
	$APPLICATION->IncludeComponent(
		'up:system.messeage',
		'',
		['MESSEAGE' => $arResult['ERROR']],
	);
}

?>

<div class="auth-form">
	<div class="auth-form-content">
		<p class="title-form"><?=Loc::getMessage('UP_LITLAB_REGISTER_TITLE')?></p>
		<form class="login-form" action="" method="post">
			<input type="hidden" name="token" value="<?=$arResult['TOKEN']?>">
			<label><?=Loc::getMessage('UP_LITLAB_LOGIN')?></label>
			<input required class="auth-input" name="login" type="text">
			<label><?=Loc::getMessage('UP_LITLAB_NICKNAME')?></label>
			<input required class="auth-input" name="username" type="text">
			<label><?=Loc::getMessage('UP_LITLAB_PASSWORD')?></label>
			<input required class="auth-input" name="pass" type="password"/>
			<input type="submit" class="auth-form-btn" value="Зарегистрироваться">
		</form>
		<a id="auth-page-btn" href="/auth/"><?=Loc::getMessage('UP_LITLAB_AUTH')?></a>
	</div>
</div>
