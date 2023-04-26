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
		<p class="main-title"><?= Loc::getMessage('UP_LITLAB_BOOKS') ?></p>
		<div class="book-filter-block">
			<div class="book-filter-genre">
				<div class="book-filter-genre-header">
					<button class="book-filter-button">
						<?= Loc::getMessage('UP_LITLAB_GENRES') ?>
						<img style="margin-left:10px" width="20px" height="20px" src="\local\modules\up.litlab\install\templates\litlab\images\icon-menu.png">
					</button>
				</div>
				<div class="book-filter-genre-list">
					<?php
					$genres = $arResult['FormattingApi']->prepareText($arResult['BookApi']->getAllGenres());
					foreach ($genres as $id => $genre):
						?>
						<a <?= $id === $arResult['GENRE_ID'] ? 'class="active"'
							: '' ?> href="/books/?<?= $arResult['FormattingApi']->createSearchRequest(
							$id,
							$arResult['SEARCH']
						) ?>">
							<div class="book-filter-genre-item">

								<?= $genre ?>
							</div>
						</a>
					<?php
					endforeach; ?>
				</div>
			</div>
			<form action="/books/" method="get">
				<?= $arResult['GENRE_ID'] ? "<input type=\"hidden\" name=\"genre_id\" value=\"{$arResult['GENRE_ID']}\">" : '' ?>
				<div class="book-list-search">
					<p class="book-list-search-wrapper input-wrapper">
						<label>
							<input class="book-list-search-input" type="text" name="search" value="<?= $arResult['SEARCH'] ?>" placeholder="<?= Loc::getMessage('UP_LITLAB_PLACEHOLDER_FIND_BOOK') ?>">
						</label>
					</p>
					<p class="book-list-search-wrapper">
						<button class="button book-list-is-info">
							<?= Loc::getMessage('UP_LITLAB_SEARCH') ?>
						</button>
					</p>
				</div>
			</form>
		</div>
	</div>
	<div class="book-list-cards">
		<?php
		$nav = new \Bitrix\Main\UI\PageNavigation('page');
		$nav->allowAllRecords(false)->setPageSize(16)->initFromUri();

		$books = $arResult['BookApi']->getListOfBook(
					  $nav->getLimit(),
					  $nav->getOffset(),
			search:   $arResult['SEARCH'],
			genre_id: $arResult['GENRE_ID']
		);

		if (!$books[0])
		{
			$APPLICATION->IncludeComponent(
				'up:system.messeage',
				'',
				['MESSEAGE' => 'UP_LITLAB_BOOKS_MISSING'],
			);
		}
		else
		{
		$bookIds = [];

		foreach ($books as $book)
		{
			$bookIds[] = $book['ID'];
		}

		$authors = $arResult['FormattingApi']->prepareText($arResult['BookApi']->getAuthorForEachBook($bookIds));

		$nav->setRecordCount($arResult['BookApi']->getCount($arResult['SEARCH'], genre_id: $arResult['GENRE_ID']));
		foreach ($books as $book):
			$book = $arResult['FormattingApi']->prepareText($book);
			?>
			<div class="book-list-card">
				<p>
					<a href="/book/<?= $book['ID'] ?>/">
						<img height="300px" width="250px" style="margin-bottom:20px" alt="" src="<?= CFile::GetPath(
							$book['IMAGE_ID']
						) ?>">
					</a>
				</p>
				<p>
					<strong><a class="book-list-card-name" href="/book/<?= $book['ID'] ?>/"><?= $book['TITLE'] ?></a></strong>
				</p><br>
				<p class="book-list-card-author"><?= Loc::getMessage(
						'UP_LITLAB_AUTHOR'
					) ?> <?= $authors[$book['ID']] ?></p>
			</div>
		<?php
		endforeach; ?>
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
	}
	?>
</main>
