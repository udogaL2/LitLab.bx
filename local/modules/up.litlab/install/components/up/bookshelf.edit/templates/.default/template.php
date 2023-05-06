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
$arResult['bookshelf'] = $arResult['bookshelfApi']->getBookshelfById((int)$arResult['BOOKSHELF_ID']);
$arResult['bookshelfTags'] = $arResult['bookshelfApi']->getTags($arResult['BOOKSHELF_ID']);
$arResult['booksComments'] = $arResult['bookshelfApi']->getComments($arResult['BOOKSHELF_ID']);
$arResult['booksOfBookshelf'] = $arResult['bookApi']->getListOfBookByBookshelf($arResult['BOOKSHELF_ID'], null, 0, 'public');
	$tokenApi = new \Up\Litlab\API\Token();
	$arResult['bookshelf'] = $arResult['formattingApi']->prepareText($arResult['bookshelf']);

	if(!(isset($_SESSION['USER_ID']) && (int)$_SESSION['USER_ID']==$arResult['bookshelf']['CREATOR_ID']) || $arResult['bookshelf']['STATUS']==='deleted'):
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
		<input type="hidden" name="token" value="<?=$tokenApi->createToken();?>">
		<?if($arResult['bookshelf']['TITLE']==='Буду читать' || $arResult['bookshelf']['TITLE']==='Прочитано'):?>
		<div class="bookshelf-create-name" style="justify-content: flex-start">
			<p style="margin-right:20px"><?=Loc::getMessage('UP_LITLAB_BOOKSHELF_TITLE')?></p>
			<p><?=$arResult['bookshelf']['TITLE']?></p>
			<?else:?>
			<div class="bookshelf-create-name" style="justify-content: flex-start">
				<p><?=Loc::getMessage('UP_LITLAB_BOOKSHELF_TITLE')?></p>
				<input name="title" required type="text" value="<?=$arResult['bookshelf']['TITLE']?>">
				<?endif;?>
		</div>
		<div class="bookshelf-create-description">
			<p><?=Loc::getMessage('UP_LITLAB_BOOKSHELF_DESC')?></p>
			<input required class="bookshelf-edit-descr" type="text" value="<?=$arResult['bookshelf']['DESCRIPTION']?>" name="description">
		</div>
		<div class="bookshelf-create-status" style="justify-content: space-evenly;">
			<?if ($arResult['bookshelf']['STATUS']==='private' || $arResult['bookshelf']['STATUS']==='moderated'):?>
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


		<div class="bookshelf-edit-tag-div">
			<div class="bookshelf-edit-tag-title-section">
				<p><?=Loc::getMessage('UP_LITLAB_BOOKSHELF_TAGS')?></p>
				<a class="button-add-tag" onclick="return createField()">+</a>
				<a class="button-remove-tag" onclick="return deleteField()">-</a>
			</div>

			<section class="shelf-card-tags-list">
				<?php
				if ($arResult['BOOKSHELF']['TAGS']):
				foreach ($arResult['formattingApi']->prepareText($arResult['BOOKSHELF']['TAGS']) as $id => $tag):?>
				<section id="tag_<?=$id?>">
					<input required class="bookshelf-edit-tag-created" type="text" value="<?= $tag ?>" name="tags[]" style="
								width: 130px; text-align: center; word-wrap: break-word; margin: 10px 5px 10px 0">
					<a class="button-delete-tag" style="padding: 5px 10px; margin-right: 10px" onclick="removeTag(<?=$id?>, <?=$arResult['BOOKSHELF_ID']?>)">-</a>
				</section>
				<?php
				endforeach;
				endif;?>
			</section>
		</div>
		<div style="width: 100%; text-align: center"><button type="submit" class="bookshelf-edit-save">Сохранить</button></div>
	</form>

<?php

if ($arResult['booksOfBookshelf'] !== []):?>
	<hr width="100%">
	<section class="user-bookshelf-list bookshelf-edit-books">
	<? foreach ($arResult['booksOfBookshelf'] as $book):
		$book = $arResult['formattingApi']->prepareText($book)?>
	<section class="user-bookshelf" ">
		<img height="200px" width="150px" src="<?= CFile::GetPath($book['IMAGE_ID']) ?>">
		<form method="post" style="width: 100%;display: flex;align-items: baseline;margin-left: 50px;">
			<input type="hidden" name="token" value="<?=$tokenApi->createToken();?>">
		<section class="user-bookshelf-description">
			<p><?=$book['TITLE']?></p>
			<p style="font-size: 18px"><?=$arResult['bookApi']->getAuthors($book['ID'])[0]['NAME']?></p>
			<section style="display: flex; align-items: flex-end;">
					<?
					if ($arResult['booksComments'][$book['ID']][0]):?>
					<input class="bookshelf-edit-comment" type="text" value="<?=htmlspecialcharsbx($arResult['booksComments'][$book['ID']])?>" name="comment">
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
						<input type="hidden" name="token" value="<?=$tokenApi->createToken();?>">
						<input type="hidden" name="deleteBook" value="<?=$book['ID']?>">
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
<?if($arResult['bookshelf']['TITLE']!=='Буду читать' && $arResult['bookshelf']['TITLE']!=='Прочитано'):?>
	<div style="width: 100%; text-align: center; display: block;">
		<form method="post">
			<input type="hidden" name="token" value="<?=$tokenApi->createToken();?>">
			<a onclick="removeBookshelf(<?=$arResult['BOOKSHELF_ID']?>, <?=$arResult['bookshelf']['CREATOR_ID']?>)" class="bookshelf-edit-delete" style="background-color: rgba(216,0,0,0.83); display: block">Удалить полку</a>
		</form>
	</div>
<?endif;?>
</section>
<?php endif;?>