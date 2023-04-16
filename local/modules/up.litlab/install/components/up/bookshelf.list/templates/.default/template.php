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


<div class="header-bookshelf">
	<div class="title">
		<h1>Создайте свою виртуальную книжную полку и найдите подборки, которые подходят именно вам</h1>
		<h3>Погрузись в мир литературы прямо сейчас</h3>
	</div>

	<div class="header-search">
		<p class="header-search-wrapper header-input-wrapper">
			<label>
				<input class="header-search-input" type="text" placeholder="Найти полку...">
			</label>
		</p>
		<p class="header-search-wrapper">
			<button class="button header-is-info">
				Поиск
			</button>
		</p>
	</div>
</div>

<main class="shelf-list-main">
	<!--		Карточка полки-->
	<div class="shelf-card">
		<a class="move-to-shelf" href="/user/1/bookshelf/1/">Перейти</a>
		<div class="shelf-card-description">
			<div>
				<a href="#" class="shelf-card-author">Ник автора</a><br>
				<p class="shelf-card-name">Книжная полка</p>
			</div>
			<p class="shelf-card-book-count">книг<br><span style="font-size: 42px">22</span></p>
		</div>
		<div class="shelf-card-images">
			<img src="" width="140px" height="180px">
			<img class="shelf-card-image2" src="" width="140px" height="180px">
			<img class="shelf-card-image3"src="" width="140px" height="180px">
		</div>
		<div class="shelf-card-tags">
			<p>Теги:</p>
			<div class="shelf-card-tags-list">
				<a href="">#Приключения</a>
				<a href="">#Рекомендую</a>
				<a href="">#ляля</a>
				<a href="">#труляля</a>
				<a href="">#что-то еще</a>
				<a href="">#что-то еще</a>
			</div>
		</div>
		<div class="shelf-card-rating">
			<!--				нужно будет организовать кнопку, а не картинки-->
			<div class="shelf-likes">
				<input class="shelf-likes-input" type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-like.png" height="25px" width="30px">
				<input class="liked" type="hidden" src="\local\modules\up.litlab\install\templates\litlab\images\icon-like-liked.png" height="25px" width="30px">
				<p class="likes-amount">21120</p>
			</div>
			<div class="shelf-likes">
				<input class="shelf-save-input" type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-save.png" height="25px" width="20px">
				<input class="saved" type="hidden" src="\local\modules\up.litlab\install\templates\litlab\images\icon-save-saved.png" height="25px" width="20px">
				<p class="save-amount">1000</p>
			</div>
		</div>
	</div>
</main>

