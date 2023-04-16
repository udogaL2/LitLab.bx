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

<main class="profile-main">
	<div class="user-header">
		<p>Ваши книжные полки</p>
		<a href="/create/bookshelf/">Добавить новую полку</a>
	</div>
	<div class="user-profile">
		<div class="user-profile-card">
			<div class="user-profile-avatar">
				<img height="200px" width="200px" >
				<button>Загрузить фото</button>
			</div>
			<p class="user-profile-card-nickname">Ник автора</p>
			<hr>
			<a href="/create/book/" style="text-decoration: none">Добавить свою книгу</a>
			<hr>
			<a href="/">Выйти</a>
		</div>

		<div class="user-bookshelf-list">
			<div class="user-bookshelf">
				<img height="200px" width="150px">
				<div class="user-bookshelf-description">
					<p>Буду читать</p>
					<span>Полка, в которую вы можете добавить понравившиеся вам книги.</span>
				</div>
				<div class="user-bookshelf-buttons">
					<input type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-lock.png" height="30px" width="25px">
					<a href="/edit/bookshelf/1/">Изменить</a>
				</div>
			</div>
			<hr>
			<div class="user-bookshelf">
				<img height="200px" width="150px">
				<div class="user-bookshelf-description">
					<p>Прочитано</p>
					<span>Полка, в которую вы можете добавить книги, которые уже прочитали.</span>
				</div>
				<div class="user-bookshelf-buttons">
					<input type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-lock.png" height="30px" width="25px">
					<a href="/edit/bookshelf/2/">Изменить</a>
				</div>
			</div>
			<hr>
		</div>
	</div>
</main>
