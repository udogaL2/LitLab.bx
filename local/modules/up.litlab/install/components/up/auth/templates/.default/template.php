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
	if (!empty($arResult['ERROR']))
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
		<p class="title-form"><?= Loc::getMessage('UP_LITLAB_LOGIN_TITLE') ?></p>
		<form class="login-form" action="" method="post">
			<label><?= Loc::getMessage('UP_LITLAB_LOGIN') ?></label>
			<input required class="auth-input" name="login" type="text" id="auth-login" minlength="5" maxlength="62"
				<?
				if (isset($_SESSION['NAME'])):?>
				   value="<?=$_SESSION['NAME']?>"
				<? endif;?>
				>
			<label><?= Loc::getMessage('UP_LITLAB_PASSWORD') ?></label>
			<input required class="auth-input" name="pass" type="password" id="auth-pass" minlength="8" maxlength="62"
				<?if (isset($_SESSION['PASSWORD'])):?>
					value="<?=$_SESSION['PASSWORD']?>"
				<? endif;?>>
			<input type="submit"  class="auth-form-btn" value="Войти">
		</form>
		<a id="auth-page-btn" href="/register/"><?= Loc::getMessage('UP_LITLAB_REGISTER') ?></a>
	</div>
</div>
