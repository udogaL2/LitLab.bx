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

$this->addExternalCss("\local\modules\up.litlab\install\components\up\bookshelf.edit\\templates\.default\add-style.css");
$this->addExternalCss("\local\modules\up.litlab\install\components\up\bookshelf.edit\\templates\.default\add-two-style.css");
?>
<?php
if (!empty($arParams['ERROR'])):?>
	<p><?= Loc::getMessage('UP_LITLAB_' . $arParams['ERROR']) ?></p>
<?php endif;?>

<?php

	$bookshelfId = $arResult['BOOKSHELF_ID'];
	$bookshelf = $arResult['bookshelfApi']->getBookshelfById((int)$bookshelfId);
	$bookshelfTags = $arResult['bookshelfApi']->getTags($bookshelfId);
	$booksOfBookshelf = $arResult['bookApi']->getListOfBookByBookshelf($bookshelfId, 3, 0, $bookshelf['STATUS']);
	$booksComments = $arResult['bookshelfApi']->getComments($bookshelfId, $bookshelf['STATUS']);

?>

<section class="bookshelf-create-main">
	<p class="bookshelf-create-main-title">Редактирование полки</p>
	<form class="bookshelf-add-form" action="" method="post">
		<div class="bookshelf-create-name">
			<p>Название полки</p>
			<input type="text" value="<?=$bookshelf['TITLE']?>" name="title">
		</div>
		<div class="bookshelf-create-description">
			<p>Описание полки</p>
			<input class="bookshelf-edit-descr" type="text" value="<?=$bookshelf['DESCRIPTION']?>" name="description">
		</div>
		<div class="bookshelf-create-description two" style="align-items: flex-start;">
		<?
		if (!$bookshelfTags[0]):?>
			<p>Теги
				<a class="button-add-tag" onclick="return createTag()">+</a>
			</p>

			<section class="shelf-card-tags-list">

			</section>
		<? else:?>
			<p>Теги
				<a class="button-add-tag" onclick="return createTag()">+</a></p>
			<section class="shelf-card-tags-list">
			<?php
			foreach ($arResult['formattingApi']->prepareText($bookshelfTags) as $tag): ?>
				<input class="bookshelf-edit-tag" type="text" value="<?= $tag ?>" name="tags-created[]" style="
							width: 130px; text-align: center; word-wrap: break-word; margin: 0 5px 10px 0">
			<?php
			endforeach; ?>
		<?endif;?>
			</section>
		</div>
		<hr width="100%">

<?php
if ($booksOfBookshelf !== []):?>
<section class="user-bookshelf-list bookshelf-edit-books">
	<? foreach ($booksOfBookshelf as $book):
?>
	<section class="user-bookshelf">
		<img height="200px" width="150px" src="<?= CFile::GetPath($book['IMAGE_ID']) ?>">
		<section class="user-bookshelf-description">
			<p><?=$book['TITLE']?></p>
			<p style="font-size: 18px"><?=$arResult['bookApi']->getAuthors($book['ID'])[0]['NAME']?></p>
					<?
					if ($booksComments[$book['ID']][0]):?>
					<input class="bookshelf-edit-comment" type="text" value="<?=($booksComments[$book['ID']])?>" name="comment[]">
					<?else:?>
						<input class="bookshelf-edit-comment" type="text" value="" placeholder="Комментарий..." name="comment[]">
					<?endif;?>
				</section>
				<section class="user-bookshelf-buttons">
					<input type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-trash.png" height="30px" width="25px">
				</section>
			</section>
			<hr>
			<?endforeach;?>
			<? endif;?>
	<div style="width: 100%; text-align: center"><button type="submit" class="bookshelf-edit-save">Сохранить</button></div>
	</section>
</div>
</form>
</section>
