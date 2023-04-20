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
		<?php
		$nav = new \Bitrix\Main\UI\PageNavigation('page');
		$nav->allowAllRecords(false)->setPageSize(12)->initFromUri();

		$books = $arResult['BookApi']->getListOfBook($nav->getLimit(), $nav->getOffset());

		$bookIds = [];

		foreach ($books as $book)
		{
			$bookIds[] = $book['ID'];
		}

		$authors = $arResult['FormattingApi']->prepareText($arResult['BookApi']->getAuthorForEachBook($bookIds));

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
			<p class="book-list-card-author">Автор <?= $authors[$book['ID']] ?></p>
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
