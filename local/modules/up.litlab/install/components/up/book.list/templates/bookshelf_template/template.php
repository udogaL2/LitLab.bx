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

$nav = new \Bitrix\Main\UI\PageNavigation('page');
$nav->allowAllRecords(false)->setPageSize(12)->initFromUri();

$books = $arResult['BookApi']->getListOfBookByBookshelf($arResult['BOOKSHELF_ID'], $nav->getLimit(), $nav->getOffset());
?>

<main class="book-list-main">
	<div class="main-header">
		<p class="main-title"><?= $books[0] ? 'Книги на полке' : 'Книги на полке отсутствуют' ?></p>
	</div>
	<div class="book-list-cards">
		<?php
		if (!$books[0])
		{
			die();
		}

		$bookIds = [];

		foreach ($books as $book)
		{
			$bookIds[] = $book['ID'];
		}

		$authors = $arResult['FormattingApi']->prepareText($arResult['BookApi']->getAuthorForEachBook($bookIds));

		$nav->setRecordCount($arResult['BookApi']->getCountInBookshelf($arResult['BOOKSHELF_ID']));
		$comments = $arResult['FormattingApi']->prepareText($arResult['BookshelfApi']->getComments($arResult['BOOKSHELF_ID']));

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
			<?php if($comments[$book['ID']]):?>
				<div class="book-list-card-comment comment"><?= $comments[$book['ID']] ?></div>
			<?php endif;?>
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
