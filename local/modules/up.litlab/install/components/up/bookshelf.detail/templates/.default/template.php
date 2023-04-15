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

<section class="bookshelf-detail-main">
	<div class="bookshelf-detail-overview">
		<p class="bookshelf-detail-name">Книжная полка от <a class="bookshelf-detail-author-name" href="#">Андрюша</a></p>
		<p class="bookshelf-detail-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
			Ab accusamus aperiam autem delectus dignissimos eos esse ex harum illum,
			laudantium mollitia nostrum numquam officiis quibusdam ratione reprehenderit</p>
		<div class="book-detail-card-description-genres">
			<p style="margin-right: 20px">Теги:</p>
			<div class="book-detail-card-description-genres-links">
				<a href="">Фантастика</a>
				<a href="">Наука</a>
				<a href="">Юмор</a>
				<a href="">Приключения</a>
				<a href="">ААА</a>
			</div>
		</div>
		<div class="bookshelf-detail-info">
			<p>Создано: 22.10.2022</p>
			<p>Последнее обновление: 23.12.2022</p>
		</div>
	</div>
	<div class="bookshelf-detail-buttons">
		<button>100</button>
		<button>10</button>
		<button>10</button>
	</div>
</section>
<section class="bookshelf-detail-card-list">
	<div class="bookshelf-list-cards">
		<div class="book-list-card">
			<p><a href="/book/1/"><img height="300px" width="250px" style="margin-bottom:20px"></a></p>
			<p><strong><a class="book-list-card-name" href="/book/1/">Название книги</a></strong></p><br>
			<p class="book-list-card-author">Автор</p>
			<div class="book-list-card-comment comment">Комментарий fffff fffffff ffffffff fffffff</div>
		</div>
		<div class="book-list-card">
			<p><a href="/book/1/"><img height="300px" width="250px" style="margin-bottom:20px"></a></p>
			<p><strong><a class="book-list-card-name" href="/book/1/">Название книги</a></strong></p><br>
			<p class="book-list-card-author">Автор</p>
			<div class="book-list-card-comment comment">Комментарий fffff fffffff ffffffff fffffff</div>
		</div>
		<div class="book-list-card">
			<p><a href="/book/1/"><img height="300px" width="250px" style="margin-bottom:20px"></a></p>
			<p><strong><a class="book-list-card-name" href="/book/1/">Название книги</a></strong></p><br>
			<p class="book-list-card-author">Автор</p>
			<div class="book-list-card-comment comment">Комментарий fffff fffffff ffffffff fffffff</div>
		</div>
		<div class="book-list-card">
			<p><a href="/book/1/"><img height="300px" width="250px" style="margin-bottom:20px"></a></p>
			<p><strong><a class="book-list-card-name" href="/book/1/">Название книги</a></strong></p><br>
			<p class="book-list-card-author">Автор</p>
			<div class="book-list-card-comment comment">Комментарий fffff fffffff ffffffff fffffff</div>
		</div>
	</div>
</section>