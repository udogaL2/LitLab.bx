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
	<form class="bookshelf-add-form" action="" method="post">
		<div class="bookshelf-create-name">
			<p>Название книги</p>
			<input required type="text" name="input-book-title">
		</div>
		<div class="bookshelf-create-description">
			<p>Автор книги</p>
			<input required class="bookshelf-create-author" type="text"  name="input-book-author">
		</div>
		<input class="book-create-save" type="submit" value="Сохранить">
	</form>
</main>