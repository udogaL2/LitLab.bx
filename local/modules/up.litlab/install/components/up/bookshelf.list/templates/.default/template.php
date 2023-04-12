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

<main class="shelf-list-main">
	<!--		Карточка полки-->
	<div class="shelf-card">
		<div class="shelf-card-description">
			<div>
				<a href="#" class="shelf-card-author">Ник автора</a><br>
				<p class="shelf-card-name">Книжная полка</p>
			</div>
			<p class="shelf-card-book-count">книг<br><span style="font-size: 42px">22</span></p>
		</div>
		<div class="shelf-card-images">
			<img src="" width="140px" height="180px">
			<img src="" width="140px" height="180px">
			<img src="" width="140px" height="180px">
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
			<p><img src="icon-like.png" height="25px" width="30px">10k</p>
			<p><img src="icon-save.png" height="30px" width="25px">100</p>
		</div>
	</div>
</main>
