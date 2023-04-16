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

$this->addExternalCss("\local\modules\up.litlab\install\components\up\bookshelf.edit\\templates\.default\add-style.css");
$this->addExternalCss("\local\modules\up.litlab\install\components\up\bookshelf.edit\\templates\.default\add-two-style.css");
?>

<section class="bookshelf-create-main">
	<p class="bookshelf-create-main-title">Редактирование полки</p>
	<form class="bookshelf-add-form" action="post">
		<div class="bookshelf-create-name">
			<p>Название полки</p>
			<input type="text">
		</div>
		<div class="bookshelf-create-description">
			<p>Описание полки</p>
			<textarea class="bookshelf-edit-descr" type="text" style=""></textarea>
		</div>
		<div class="bookshelf-create-description">
			<p>Теги</p>
		</div>

	</form>
</section>

<div class="user-bookshelf-list bookshelf-edit-books">
	<div class="user-bookshelf">
		<img height="200px" width="150px">
		<div class="user-bookshelf-description">
			<p>Название</p>
			<p style="font-size: 18px">Автор</p>
			<textarea class="bookshelf-edit-comment" type="text" placeholder="Комментарий..."></textarea>
		</div>
		<div class="user-bookshelf-buttons">
			<input type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-trash.png" height="30px" width="25px">
			<a href="/edit/bookshelf/1/">Добавить комментарий</a>
		</div>
	</div>
	<hr>
	<div class="user-bookshelf">
		<img height="200px" width="150px">
		<div class="user-bookshelf-description">
			<p>Название</p>
			<p style="font-size: 18px">Автор</p>
			<textarea class="bookshelf-edit-comment" type="text" placeholder="Комментарий..."></textarea>
		</div>
		<div class="user-bookshelf-buttons">
			<input type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-trash.png" height="30px" width="25px">
			<a href="/edit/bookshelf/1/">Добавить комментарий</a>
		</div>
	</div>
	<hr>
	<button>Сохранить</button>
</div>