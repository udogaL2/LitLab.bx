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

<main class="bookshelf-create-main">
	<p class="bookshelf-create-main-title">Добавление новой полки</p>
	<form class="bookshelf-add-form" action="post">
		<div class="bookshelf-create-name">
			<p>Название полки</p>
			<input type="text">
		</div>
		<div class="bookshelf-create-description">
			<p>Описание полки</p>
			<textarea class="bookshelf-edit-descr" type="text" style=""></textarea>
		</div>
		<button>Сохранить</button>
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


