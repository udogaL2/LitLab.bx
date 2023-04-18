<?php

/**
 * @var array $arResult
 * @var array $arParams
 */

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}
?>

<?php
if (!empty($arParams['ERROR'])):?>
	<p><?= Loc::getMessage('UP_LITLAB_' . $arParams['ERROR']) ?></p>
<?php endif;?>

<main class="bookshelf-create-main">
	<p class="bookshelf-create-main-title">Добавление новой полки</p>
	<form class="bookshelf-add-form" action="" method="post">
		<div class="bookshelf-create-name">
			<p>Название полки</p>
			<input type="text" name="input-bookshelf-name">
		</div>
		<div class="bookshelf-create-description">
			<p>Описание полки</p>
			<input class="bookshelf-edit-descr" type="text" style="" name="input-bookshelf-description">
		</div>
			<input type="submit" value="Сохранить">
	</form>
</main>
<?php
// $APPLICATION->IncludeComponent(
// 	"bitrix:system.auth.registration",
// 	"",
// 	[
// 	],
// 	false
// );
// ?>


