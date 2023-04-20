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

			<div class="book-filter-genre">
				<div class="book-filter-genre-header">
					<button class="book-filter-button">
						Фильтр<img style="margin-left:10px" width="20px" height="20px" src="\local\modules\up.litlab\install\templates\litlab\images\icon-menu.png">
					</button>
				</div>
				<div class="book-filter-genre-list">
					<a href="/">Жанр 1</a>
					<a href="/">Жанр 2</a>
					<a href="/">Жанр 3</a>
					<a href="/">Жанр 4</a>
					<a href="/">Жанр 5</a>
					<a href="/">Жанр 5</a>
				</div>
			</div>

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
		<?php
		$nav = new \Bitrix\Main\UI\PageNavigation('page');
		$nav->allowAllRecords(false)->setPageSize(4)->initFromUri();

		$books = $arResult['BookApi']->getListOfBook($nav->getLimit(), $nav->getOffset());

		$nav->setRecordCount($arResult['BookApi']->getCount());
		foreach ($books as $book):
		$book = $arResult['FormattingApi']->prepareText($book);
		?>
		<div class="book-list-card">
			<p>
				<a href="/book/<?= $book['ID'] ?>/">
					<img height="300px" width="250px" style="margin-bottom:20px" alt="" src="<?= CFile::GetPath($book['IMAGE_ID']) ?>">
				</a>
			</p>
			<p><strong><a class="book-list-card-name" href="/book/<?= $book['ID'] ?>/"><?= $book['TITLE'] ?></a></strong></p><br>
<!--			<p class="book-list-card-author">Автор</p>-->
		</div>
		<?php endforeach; ?>
	</div>
	<?php
	$APPLICATION->IncludeComponent(
		"bitrix:main.pagenavigation",
		"",
		[
			"NAV_OBJECT" => $nav,
			"SEF_MODE" => "Y",
		],
		false
	);
	?>
</main>
