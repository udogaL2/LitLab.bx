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
CJSCore::Init(array('ajax'));
$bookshelfApi = new \Up\Litlab\API\Bookshelf();
$userApi = new \Up\Litlab\API\User();
if($arResult['MESSEAGE'])
{
	$APPLICATION->IncludeComponent(
		'up:system.messeage',
		'',
		['MESSEAGE' => $arResult['MESSEAGE']],
	);
}
else{
	if(isset($_SESSION['NAME'])){
		$bookshelves = array_slice($bookshelfApi->getListOfUserBookshelf($arResult['USER_ID'], ['public', 'private', 'moderated'], null, 0), 2);
		$bookshelfIdWillRead = $arResult['bookshelfApi']->getBookshelfIdByTitle($arResult['USER_ID'], 'Буду читать');
		$bookshelfIdRead = $arResult['bookshelfApi']->getBookshelfIdByTitle($arResult['USER_ID'], 'Прочитано');
	}
?>

<main class="book-detail-main">
	<div class="book-detail-card">
		<div class="book-detail-card-image">
			<img height="500px" width="400px" alt="" src="<?= $arResult['Book']['IMG_PATH'] ?>">
			<?if(isset($_SESSION['NAME'])):?>
			<div class="book-detail-card-buttons">
				<? $arResult['Bookshelf']['ADDED']= $arResult['bookApi']->checkBookInBookshelf($arResult['Book']['ID'], $arResult['WILL_READ_ID']);
				if($arResult['Bookshelf']['ADDED']):?>
					<button id="button-add-willread"  style="background-color: #84c25c" type="submit" onclick="addBookToWillReadBookshelf(
					<?= $arResult['Book']['ID']?>,
					<?= $arResult['WILL_READ_ID']?>)">
						<?= Loc::getMessage('UP_LITLAB_WILL_READ_BUTTON') ?></button>
				<?else:?>
					<button id="button-add-willread"  style="background-color: #ededed" type="submit" onclick="addBookToWillReadBookshelf(
					<?= $arResult['Book']['ID']?>,
					<?= $arResult['WILL_READ_ID']?>)">
						<?= Loc::getMessage('UP_LITLAB_WILL_READ_BUTTON') ?></button>
				<?endif;?>

				<? $arResult['Bookshelf']['ADDED']= $arResult['bookApi']->checkBookInBookshelf($arResult['Book']['ID'], $arResult['READ_ID']);
				if($arResult['Bookshelf']['ADDED']):?>
					<button id="button-add-read" style="background-color: #84c25c" type="submit" onclick="addBookToReadBookshelf(
					<?= $arResult['Book']['ID']?>,
					<?= $arResult['READ_ID']?>)">
						<?= Loc::getMessage('UP_LITLAB_READ_BUTTON') ?></button>
				<?else:?>
					<button id="button-add-read" style="background-color: #ededed" type="submit" onclick="addBookToReadBookshelf(
					<?= $arResult['Book']['ID']?>,
					<?= $arResult['READ_ID']?>)">
						<?= Loc::getMessage('UP_LITLAB_READ_BUTTON') ?></button>
				<?endif;?>
			</div>
			<button style="padding: 10px 50px; background-color: #7fb255; border: 1px solid #65B95E" class="popup" onclick="">+ <?= Loc::getMessage('UP_LITLAB_ADD_BUTTON') ?></button>
			<main id="listUserBookshelves" class="list-user-bookshelf" style="display: none; flex-direction: column">
				<input style="width: 20px; margin:-20px 0 20px 400px" type="submit" value="✖">
				<?foreach ($bookshelves as $bookshelf):
					$bookshelf = $arResult['formattingApi']->prepareText($bookshelf);
					$arResult['Bookshelf']['ADDED']= $arResult['bookApi']->checkBookInBookshelf($arResult['Book']['ID'], $bookshelf['ID']);?>
					<div style="display: flex" class="user-bookshelf">
						<p><?=$bookshelf['TITLE']?></p>
						<?if($arResult['Bookshelf']['ADDED']):?>
							<button id="<?=$bookshelf['ID']?>" style="background-color: #84c25c" type="submit" onclick="addBook(<?=$arResult['Book']['ID']?>, <?=$bookshelf['ID']?>)">Добавлено</button>
						<?else:?>
							<button id="<?=$bookshelf['ID']?>" style="background-color: #ededed" type="submit" onclick="addBook(<?=$arResult['Book']['ID']?>, <?=$bookshelf['ID']?>)">Добавить в полку</button>
						<?endif;?>
					</div>
				<hr>
				<?endforeach;?>
			</main>
			<?endif;?>
		</div>
		<div class="book-detail-card-description">
			<p class="book-detail-card-description-name"> <?= $arResult['Book']['TITLE'] ?> </p>
			<p class="book-detail-card-rating-title"> <?= Loc::getMessage('UP_LITLAB_RATING') ?> </p>
			<div class="book-detail-card-rating">
				<div class="r-boxes">
					<?php
						$divType = isset($_SESSION['NAME']) ? 'button' : 'div';
						for($rating = 5; $rating > $arResult['Book']['RATING_NUMBER']; $rating--):?>
							<<?= $divType ?> <?= isset($_SESSION['NAME']) ? "onclick=\"makeEstimation({$arResult['Book']['ID']}, {$rating})\"" : '' ?> class="r-box<?= isset($_SESSION['NAME']) ? '-lu' : '' ?>-<?=$rating?>"> </<?= $divType ?>>
						<?php endfor;?>
						<?php
						for($rating = $arResult['Book']['RATING_NUMBER']; $rating > 0; $rating--):?>
							<<?= $divType ?> <?= isset($_SESSION['NAME']) ? "onclick=\"makeEstimation({$arResult['Book']['ID']}, {$rating})\"" : '' ?> class="r-box<?= isset($_SESSION['NAME']) ? '-lu' : '' ?>-<?=$rating?>-active"> </<?= $divType ?>>
						<?php endfor; ?>
				</div>
				<div class="rating-num"><?= $arResult['Book']['BOOK_RATING'] ? number_format((float)$arResult['Book']['BOOK_RATING'], 2, '.', '') : Loc::getMessage('UP_LITLAB_NULL_RATING') ?></div>
			</div>
			<p class="book-detail-card-description-author"><?= $arResult['Authors']['NAME'] ?></p>
			<div class="book-detail-card-description-rating"></div>
			<p class="book-detail-card-description-overview">
					<span> <?= $arResult['Book']['DESCRIPTION'] ?> </span>
			</p>
			<div class="book-detail-card-description-genres">
				<p style="margin-right: 20px"><?= Loc::getMessage('UP_LITLAB_GENRES') ?>:</p>
				<div class="book-detail-card-description-genres-links">
					<?php foreach ($arResult['Genre'] as $id => $genre): ?>
					<a href="/books/?genre_id=<?= $id ?>"><?= $genre ?></a>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="book-detail-card-description-ISBN">
				<p style="margin-right: 20px">ISBN: <?= $arResult['Book']['ISBN'] ? : 'отсутствует' ?></p>
			</div>
			<div class="book-detail-card-description-publication-date">
				<p style="margin:20px 0"><?= Loc::getMessage('UP_LITLAB_PUBLICATION_YEAR') ?>: <?= $arResult['Book']['PUBLICATION_YEAR'] ? : 'отсутствует' ?></p>
			</div>
		</div>
	</div>
</main>
<?php
}
