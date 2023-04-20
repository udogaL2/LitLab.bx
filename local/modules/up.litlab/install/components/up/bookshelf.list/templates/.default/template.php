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
	<?php
	$nav = new \Bitrix\Main\UI\PageNavigation('page');
	$nav->allowAllRecords(false)->setPageSize(3)->initFromUri();

	$bookshelves = $arResult['BookshelfApi']->getListOfBookshelf($nav->getLimit(), $nav->getOffset());
	$bookshelfIds = [];
	$creatorIds = [];
	foreach ($bookshelves as $bookshelf)
	{
		$bookshelfIds[] = $bookshelf['ID'];
		$creatorIds[] = $bookshelf['CREATOR_ID'];
	}

	$booksCount = $arResult['BookshelfApi']->getCountInEachBookshelf($bookshelfIds);
	$savesCount = $arResult['BookshelfApi']->getCountOfSavedBookshelvesForEach($bookshelfIds);
	$bookshelvesTag = $arResult['BookshelfApi']->getTagsInEachBookshelf($bookshelfIds);
	$images = $arResult['BookApi']->getImages($bookshelfIds);
	$creatorNames = $arResult['UserApi']->getUserNames($creatorIds);

	$nav->setRecordCount($arResult['BookshelfApi']->getCount());
	foreach ($bookshelves as $bookshelf):
		$bookshelf = $arResult['FormattingApi']->prepareText($bookshelf);
		?>
		<div class="shelf-card">
			<a class="move-to-shelf" href="/user/<?= $bookshelf['CREATOR_ID'] ?>/bookshelf/<?= $bookshelf['ID'] ?>/">Перейти</a>
			<div class="shelf-card-description">
				<div>
					<a href="/user/<?=$bookshelf['CREATOR_ID']?>/" class="shelf-card-author"><?= $creatorNames[$bookshelf['CREATOR_ID']] ?></a><br>
					<p class="shelf-card-name">Книжная полка "<?= $bookshelf['TITLE'] ?>"</p>
				</div>
				<p class="shelf-card-book-count">книг<br><span style="font-size: 42px"><?= $booksCount[$bookshelf['ID']]
							? count($booksCount[$bookshelf['ID']]) : 0 ?></span></p>
			</div>
			<div class="shelf-card-images">
				<?php
				if ($images[$bookshelf['ID']]):
					foreach ($images[$bookshelf['ID']] as $image):
						?>
						<img src="<?= CFile::GetPath($image) ?>" width="140px" height="180px">
				<?php
					endforeach;
				endif;
				?>
			</div>
			<?php
			if ($bookshelvesTag[$bookshelf['ID']][0]): ?>
				<div class="shelf-card-tags">
					<p>Теги:</p>
					<?php
					foreach ($arResult['FormattingApi']->prepareText($bookshelvesTag[$bookshelf['ID']]) as $tag): ?>
						<div class="shelf-card-tags-list">
							<p><?= $tag ?></p>
						</div>
					<?php
					endforeach; ?>
				</div>
			<?php
			endif; ?>
			<div class="shelf-card-rating">
				<!--				нужно будет организовать кнопку, а не картинки-->
				<div class="shelf-likes">
					<input class="shelf-likes-input" type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-like.png" height="25px" width="30px">
					<input class="liked" type="hidden" src="\local\modules\up.litlab\install\templates\litlab\images\icon-like-liked.png" height="25px" width="30px">
					<p class="likes-amount"><?= $bookshelf['LIKES'] ?></p>
				</div>
				<div class="shelf-likes">
					<input class="shelf-save-input" type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-save.png" height="25px" width="20px">
					<input class="saved" type="hidden" src="\local\modules\up.litlab\install\templates\litlab\images\icon-save-saved.png" height="25px" width="20px">
					<p class="save-amount"><?= $savesCount[$bookshelf['ID']][0] ? count($savesCount[$bookshelf['ID']])
							: 0 ?></p>
				</div>
			</div>
		</div>
	<?php
	endforeach; ?>

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

