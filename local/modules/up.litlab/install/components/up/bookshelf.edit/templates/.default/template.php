<?php

/**@global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

$this->addExternalCss("\local\modules\up.litlab\install\components\up\bookshelf.edit\\templates\.default\add-style.css");
$this->addExternalCss("\local\modules\up.litlab\install\components\up\bookshelf.edit\\templates\.default\add-two-style.css");
CJSCore::Init(array('ajax'));
?>
<?php
	if (!empty($arResult['ERROR']))
	{
		$APPLICATION->IncludeComponent(
			'up:system.messeage',
			'',
			['MESSEAGE' => $arResult['ERROR']],
		);
	}
?>

<?php

	$bookshelfId = $arResult['BOOKSHELF_ID'];
	$bookshelf = $arResult['bookshelfApi']->getBookshelfById((int)$bookshelfId);
	$bookshelfTags = $arResult['bookshelfApi']->getTags($bookshelfId);

	$booksComments = $arResult['bookshelfApi']->getComments($bookshelfId, $bookshelf['STATUS']);
	$bookshelf = $arResult['formattingApi']->prepareText($bookshelf);

	if(!(isset($_SESSION['USER_ID']) && $_SESSION['USER_ID']==$bookshelf['CREATOR_ID'])):
		$APPLICATION->IncludeComponent(
			'up:system.messeage',
			'',
			['MESSEAGE' => 'UP_LITLAB_BOOKSHELF_MISSING'],
		);
	else:
?>

<section class="bookshelf-create-main">
	<p class="bookshelf-create-main-title"><?=Loc::getMessage('UP_LITLAB_EDIT_BOOKSHELF')?></p>
	<form class="bookshelf-add-form" action="" method="post">

		<?if($bookshelf['TITLE']==='Буду читать' || $bookshelf['TITLE']==='Прочитано'):?>
		<div class="bookshelf-create-name" style="justify-content: flex-start">
			<p style="margin-right:20px"><?=Loc::getMessage('UP_LITLAB_BOOKSHELF_TITLE')?></p>
			<p><?=$bookshelf['TITLE']?></p>
			<?else:?>
			<div class="bookshelf-create-name" style="justify-content: flex-start">
				<p><?=Loc::getMessage('UP_LITLAB_BOOKSHELF_TITLE')?></p>
				<input name="title" required type="text" value="<?=$bookshelf['TITLE']?>">
			<?endif;?>
		</div>
		<div class="bookshelf-create-description">
			<p><?=Loc::getMessage('UP_LITLAB_BOOKSHELF_DESC')?></p>
			<input required class="bookshelf-edit-descr" type="text" value="<?=$bookshelf['DESCRIPTION']?>" name="description">
		</div>
		<div style="justify-content: space-evenly;">
			<?if ($bookshelf['STATUS']==='private' || $bookshelf['STATUS']==='moderated'):?>
				<label>
					<input type="radio" name="status" value="private" checked> Приватная
				</label>
				<label>
					<input type="radio" name="status" value="public"> Публичная
				</label>
			<?else:?>
				<label>
					<input type="radio" name="status" value="private" > Приватная
				</label>
				<label>
					<input type="radio" name="status" value="public" checked> Публичная
				</label>
			<?endif;?>
		</div>


		<div class="bookshelf-create-description two" style="">
		<?
		if (!$bookshelfTags[0]):?>
			<p><?=Loc::getMessage('UP_LITLAB_BOOKSHELF_TAGS')?>
				<a class="button-add-tag" onclick="return createTag()">+</a>
			</p>

			<section class="shelf-card-tags-list">

			</section>
		<? else:?>
			<p><?=Loc::getMessage('UP_LITLAB_BOOKSHELF_TAGS')?>
			<a class="button-add-tag" onclick="return createTag()">+</a></p>
			<section class="shelf-card-tags-list">
				<?php
				foreach ($arResult['formattingApi']->prepareText($bookshelfTags) as $tag):
					$tagId = $arResult['bookshelfApi']->getTagByName($tag)['ID']?>
				<section id="<?=$tagId?>">
					<input required class="bookshelf-edit-tag" type="text" value="<?= $tag ?>" name="tags-created[]" style="
								width: 130px; text-align: center; word-wrap: break-word; margin: 10px 5px 10px 0">
					<button style="padding: 5px 10px; margin-right: 10px" type="submit" value="<?=$tagId?>" name="delete" onclick="removeTag(<?=$tagId?>, <?=$arResult['BOOKSHELF_ID']?>)">-</button>
				</section>
				<?php
				endforeach; ?>
			</section>
		<?endif;?>
	</div>
		<div style="width: 100%; text-align: center"><button type="submit" class="bookshelf-edit-save">Сохранить</button></div>
	</form>

<?php
$booksOfBookshelf = $arResult['bookApi']->getListOfBookByBookshelf($bookshelfId, null, 0, 'public');
if ($booksOfBookshelf !== []):?>
	<hr width="100%">
	<section class="user-bookshelf-list bookshelf-edit-books">
	<? foreach ($booksOfBookshelf as $book):?>
	<section class="user-bookshelf" ">
		<img height="200px" width="150px" src="<?= CFile::GetPath($book['IMAGE_ID']) ?>">
		<form method="post" style="width: 100%;display: flex;align-items: baseline;margin-left: 50px;">
		<section class="user-bookshelf-description">
			<p><?=$book['TITLE']?></p>
			<p style="font-size: 18px"><?=$arResult['bookApi']->getAuthors($book['ID'])[0]['NAME']?></p>
			<section style="display: flex; align-items: flex-end;">
					<?
					if ($booksComments[$book['ID']][0]):?>
					<input class="bookshelf-edit-comment" type="text" value="<?=($booksComments[$book['ID']])?>" name="comment">
					<?else:?>
						<input class="bookshelf-edit-comment" type="text" value="" placeholder="Комментарий..." name="comment">
					<?endif;?>
					<input type="hidden" name="item" value="<?=$book['ID']?>">
			<section style=" text-align: center"><button class="button-add-comment" style="margin-left: 40px;" type="submit">Добавить комментарий</button></section>
			</section>
		</section>
		</form>
				<section class="user-bookshelf-buttons">
					<form method="post">
						<input type="image" onclick="removeBook(<?=$book['ID']?>, <?=$arResult['BOOKSHELF_ID']?>)" src="\local\modules\up.litlab\install\templates\litlab\images\icon-trash.png" height="30px" width="25px">
					</form>
				</section>
	</section>
			<hr>
			<?endforeach;?>
			<? endif;?>
</section>

</section>

</div>
<?if($bookshelf['TITLE']!=='Буду читать' && $bookshelf['TITLE']!=='Прочитано'):?>
	<div style="width: 100%; text-align: center; display: block;">
		<form method="post">
			<button type="submit" onclick="removeBookshelf(<?=$arResult['BOOKSHELF_ID']?>, <?=$bookshelf['CREATOR_ID']?>)" class="bookshelf-edit-save" style="background-color: rgba(216,0,0,0.83)">Удалить полку</button>
		</form>
	</div>
<?endif;?>
</section>
<?php endif;?>