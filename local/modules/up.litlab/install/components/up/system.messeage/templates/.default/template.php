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

<main class="system-messeage">
	<div class="system-messeage-icon">
		<img src="\local\modules\up.litlab\install\templates\litlab\images\icon-warning.png" alt="">
	</div>
	<div class="system-messeage-text">
		<p>
			<?= Loc::getMessage($arResult['MESSEAGE']) ?>
		</p>
	</div>
</main>
