<?php

/**
 * @var array $arResult
 * @var array $arParams
 */

use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}
$this->addExternalCss("\local\modules\up.litlab\install\components\up\book.edit\\templates\.default\add-style.css");

if (!empty($arResult['ERROR']))
{
	$APPLICATION->IncludeComponent(
		'up:system.messeage',
		'',
		['MESSEAGE' => $arResult['ERROR']],
	);
}

$arResult['BOOK'] = ServiceLocator::getInstance()->get('Formatting')->prepareText($arResult['BOOK']);
?>

<main class="book-edit-main">
	<p class="book-edit-main-title"><?=Loc::getMessage('UP_LITLAB_BOOK_EDIT')?></p>
	<?php
	if ($arResult['BOOK']['ID']):
		if ($arResult['BOOK']['STATUS'] !== 'moderation'):
		?>
			<a class="book-edit-detail-link" href="/book/<?= $arResult['BOOK']['ID'] ?>/"><?=Loc::getMessage('UP_LITLAB_TO_BOOK_PAGE')?></a>
		<?php
		endif;
		?>
		<a class="book-edit-delete" onclick="removeBook(<?=$arResult['BOOK']['ID']?>)"><?=Loc::getMessage('UP_LITLAB_REMOVE')?></a>
	<?php
	endif;
	?>
	<form class="book-add-form" action="" method="post">
		<div class="book-edit-title">
			<p><?=Loc::getMessage('UP_LITLAB_BOOK_TITLE')?></p>
			<input class="book-edit-title" type="text" name="input-book-title" value="<?= $arResult['BOOK']['TITLE'] ?>">
		</div>
		<div class="book-edit-description">
			<p><?=Loc::getMessage('UP_LITLAB_BOOK_DESCRIPTION')?></p>
			<input class="book-edit-description" type="text" name="input-book-description" value="<?= $arResult['BOOK']['DESCRIPTION'] ?>">
		</div>
		<div class="book-edit-year">
			<p><?=Loc::getMessage('UP_LITLAB_BOOK_YEAR_PUBLICATION')?></p>
			<input class="book-edit-year" type="text" name="input-book-year" value="<?= $arResult['BOOK']['PUBLICATION_YEAR'] ?>">
		</div>
		<div class="book-edit-isbn">
			<p><?=Loc::getMessage('UP_LITLAB_ISBN')?></p>
			<input class="book-edit-isbn" type="text" name="input-book-isbn" value="<?= $arResult['BOOK']['ISBN'] ?>">
		</div>
		<div class="book-edit-image">
			<p><?=Loc::getMessage('UP_LITLAB_IMAGE')?></p>

			<?php
			if ($arResult['BOOK']['IMG_PATH']): ?>
				<a href="<?= $arResult['BOOK']['IMG_PATH'] ?>" target="_blank"><img style="height: 80px" src="<?= $arResult['BOOK']['IMG_PATH'] ?>" alt="изображение отсутствует"></a>
			<?php
			endif;
			$APPLICATION->IncludeComponent(
				"bitrix:main.file.input",
				"",
				[
					"INPUT_NAME" => "IMAGE_NAME_INPUT",
					"MULTIPLE" => "N",
					"MODULE_ID" => "LitLab",
					"MAX_FILE_SIZE" => "2097152",
					"ALLOW_UPLOAD" => "I",
				],
				false
			);
			?>
		</div>
		<div class="book-edit-genre-div" style="align-items: flex-start;">
			<div class="book-edit-genre-title-section">
				<p><?=Loc::getMessage('UP_LITLAB_GENRE')?></p>
				<a class="button-add-genre" onclick="return createField()">+</a>
				<a class="button-remove-genre" onclick="return deleteField()">-</a>
			</div>

			<section class="book-genre-list">

				<?php
				if ($arResult['BOOK']['GENRES']):
					foreach ($arResult['BOOK']['GENRES'] as $id => $GENRE):
						?>
						<div id="genre_<?= $id ?>">
							<input class="book-edit-genre-created" name="genre[]" style="width: 130px;" value="<?= $GENRE ?>">
							<a class="book-edit-genre-created-delete" style="padding: 5px 10px; margin-right: 10px" onclick="removeGenre(<?= $arResult['BOOK']['ID'] ?>, <?= $id ?>)">-</a>
						</div>
					<?php
					endforeach;
				endif;
				?>

			</section>
		</div>
		<div class="book-edit-author-div" style="align-items: flex-start;">
			<div class="book-edit-author-title-section">
				<p><?=Loc::getMessage('UP_LITLAB_AUTHORS')?></p>
				<a class="button-add-author" onclick="return createField()">+</a>
				<a class="button-remove-author" onclick="return deleteField()">-</a>
			</div>

			<section class="book-author-list">
				<?php
				if ($arResult['BOOK']['AUTHORS']):
					foreach ($arResult['BOOK']['AUTHORS'] as $id => $AUTHOR):
						?>
						<div id="author_<?= $id ?>">
							<input class="book-edit-author-created" name="author[]" style="width: 130px;" value="<?= $AUTHOR ?>">
							<a class="book-edit-author-created-delete" style="padding: 5px 10px; margin-right: 10px" onclick="removeAuthor(<?= $arResult['BOOK']['ID'] ?>, <?= $id ?>)">-</a>
						</div>
					<?php
					endforeach;
				endif;
				?>
			</section>
		</div>
		<input class="book-edit-save" type="submit" value="Сохранить">
	</form>
</main>