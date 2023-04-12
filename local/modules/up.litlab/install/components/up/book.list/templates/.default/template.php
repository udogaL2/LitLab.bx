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

<main class="book-list-main">
	<div class="main-header">
		<p class="main-title">Книги</p>
		<div class="book-list-search">
			<p class="book-list-search-wrapper input-wrapper">
				<label>
					<input class="book-list-search-input" type="text" placeholder="Найти книги...">
				</label>
			</p>
			<p class="book-list-search-wrapper">
				<button class="button book-list-is-info">
					Поиск
				</button>
			</p>
		</div>
	</div>
	<div class="book-list-cards">
		<div class="book-list-card">
			<p><a href="/book/1/"><img height="300px" width="250px" style="margin-bottom:20px"></a></p>
			<p><strong><a class="book-list-card-name" href="/book/1/">Название книги</a></strong></p><br>
			<p class="book-list-card-author">Автор</p>
		</div>
	</div>
</main>
