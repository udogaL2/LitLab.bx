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
		<h1><?= Loc::getMessage('UP_LITLAB_TITLE')?> </h1>
		<h3><?= Loc::getMessage('UP_LITLAB_UNDER_TITLE') ?></h3>
	</div>
	<form action="/" method="get">
		<div class="header-search">
			<p class="header-search-wrapper header-input-wrapper">
				<label>
					<input class="header-search-input" type="text" name="search" value="<?= $arResult['SEARCH'] ?>" placeholder="<?= Loc::getMessage('UP_LITLAB_PLACEHOLDER_FIND_BOOK') ?>">
				</label>
			</p>
			<p class="header-search-wrapper">
				<button class="button header-is-info">
					<?= Loc::getMessage('UP_LITLAB_SEARCH') ?>
				</button>
			</p>
		</div>
	</form>
</div>

<main class="shelf-list-main">

	<?php
	if ($arResult['USER']['ID'] && $arResult['USER']['ROLE'] === 'admin'):
	?>
	 <a href="/bookshelves/moderation/" class="moderation-link">
		 <p><?=Loc::getMessage('UP_LITLAB_TO_MODERATION_PAGE')?></p>
	 </a>
	<?php
		endif;
	?>
	<!--		Карточка полки-->
	<?php
	$nav = new \Bitrix\Main\UI\PageNavigation('page');
	$nav->allowAllRecords(false)->setPageSize(6)->initFromUri();

	$bookshelves = $arResult['BookshelfApi']->getListOfBookshelf(
				  $nav->getLimit(),
				  $nav->getOffset(),
		search:   $arResult['SEARCH'],
		notEmpty: true
	);

	if (!$bookshelves[0])
	{
		$APPLICATION->IncludeComponent(
			'up:system.messeage',
			'',
			['MESSEAGE' => 'UP_LITLAB_BOOKSHELVES_MISSING'],
		);
	}
	else
	{
		$bookshelfIds = [];
		$creatorIds = [];
		foreach ($bookshelves as $bookshelf)
		{
			$bookshelfIds[] = $bookshelf['ID'];
			$creatorIds[] = $bookshelf['CREATOR_ID'];
		}

		$savesCount = $arResult['BookshelfApi']->getCountOfSavedBookshelvesForEach($bookshelfIds);
		$bookshelvesTag = $arResult['BookshelfApi']->getTagsInEachBookshelf($bookshelfIds);
		$images = $arResult['BookApi']->getImages($bookshelfIds);
		$creatorNames = $arResult['UserApi']->getUserNames($creatorIds);

		$nav->setRecordCount($arResult['BookshelfApi']->getCount($arResult['SEARCH'], notEmpty: true));
		foreach ($bookshelves as $bookshelf):
			$bookshelf = $arResult['FormattingApi']->prepareText($bookshelf);
			?>
			<div class="shelf-card">
				<a class="move-to-shelf" href="/user/<?= $bookshelf['CREATOR_ID'] ?>/bookshelf/<?= $bookshelf['ID'] ?>/"><?= Loc::getMessage('UP_LITLAB_MOVE') ?></a>
				<div class="shelf-card-description">
					<div>
						<a href="/user/<?= $bookshelf['CREATOR_ID'] ?>/" class="shelf-card-author"><?= $creatorNames[$bookshelf['CREATOR_ID']] ?></a><br>
						<p class="shelf-card-name"><?= Loc::getMessage('UP_LITLAB_BOOKSHELF') ?> "<?= $bookshelf['TITLE'] ?>"</p>
					</div>
					<p class="shelf-card-book-count"><?= Loc::getMessage('UP_LITLAB_BOOKS') ?><br><span style="font-size: 42px"><?= $bookshelf['BOOK_COUNT'] ?></span>
					</p>
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
						<p><?= Loc::getMessage('UP_LITLAB_TAGS') ?>:</p>
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
					<div class="shelf-likes">
						<img class="shelf-likes-input" src="\local\modules\up.litlab\install\templates\litlab\images\icon-like.png" height="25px" width="30px">
<!--						<input class="liked" type="hidden" src="\local\modules\up.litlab\install\templates\litlab\images\icon-like-liked.png" height="25px" width="30px">-->
						<p class="likes-amount"><?= $bookshelf['LIKES'] ?></p>
					</div>
					<div class="shelf-likes">
						<img class="shelf-save-input" src="\local\modules\up.litlab\install\templates\litlab\images\icon-save.png" height="25px" width="20px">
<!--						<input class="saved" type="hidden" src="\local\modules\up.litlab\install\templates\litlab\images\icon-save-saved.png" height="25px" width="20px">-->
						<p class="save-amount"><?= $savesCount[$bookshelf['ID']][0] ? count(
								$savesCount[$bookshelf['ID']]
							) : 0 ?></p>
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
	}
	?>
</main>

