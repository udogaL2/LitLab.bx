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
$this->addExternalCss("\local\modules\up.litlab\install\components\up\book.create\\templates\.default\add-style.css");
?>

<main class="bookshelf-create-main">
	<p class="bookshelf-create-main-title">Заявка на добавление новой книги</p>
	<form class="bookshelf-add-form" action="post">
		<div class="bookshelf-create-name">
			<p>Название книги</p>
			<input type="text">
		</div>
		<div class="bookshelf-create-description">
			<p>Описание книги</p>
			<textarea class="bookshelf-edit-descr" type="text" style=""></textarea>
		</div>
		<button>Сохранить</button>
	</form>
</main>