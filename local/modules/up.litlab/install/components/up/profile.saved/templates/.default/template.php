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

<main class="profile-main">
	<?
	if (isset($_SESSION['USER_ID']) && (string)$arResult['USER_ID']===$_SESSION['USER_ID']):?>
	<div class="user-header">
		<p><?= Loc::getMessage('UP_LITLAB_SAVED_BOOKSHELVES') ?></p>
		<a href="/create/bookshelf/"><?=Loc::getMessage('UP_LITLAB_CREATE_BOOKSHELF')?></a>
	</div>
	<div class="user-profile">
		<div class="user-profile-card">

			<p class="user-profile-card-nickname"><?=$arResult['userNickName']?></p>
			<hr>
			<a href="/create/book/" style="text-decoration: none"><?=Loc::getMessage('UP_LITLAB_ADD_BOOK')?></a>
			<hr>
			<a href="/user/<?=$arResult['USER_ID']?>/" style="text-decoration: none"><?=Loc::getMessage('UP_LITLAB_YOURS_BOOKSHELVES')?></a>
			<hr>
			<a href="/logout/"><?=Loc::getMessage('UP_LITLAB_LOGOUT')?></a>
		</div>

		<? else:
			if ($arResult['userApi']->getCreatorInfo((int)$arResult['USER_ID'])!==false):?>
				<div class="user-header">
					<p><?=Loc::getMessage('UP_LITLAB_BOOKSHELVES_OF_USER')?> <?=$arResult['userNickName']?></p>
				</div>
			<?endif;
		endif;?>
		<div class="user-bookshelf-list" style="width: 95%">
			<?php
			$nav = new \Bitrix\Main\UI\PageNavigation('page');
			$nav->allowAllRecords(false)->setPageSize(4)->initFromUri();
				$savedBookshelves = $arResult['userBookshelfApi']->getListOfSavedBookshelf(
					$arResult['USER_ID'],
					$nav->getLimit(),
					$nav->getOffset()
				);
			if($savedBookshelves[0]):
				$nav->setRecordCount($arResult['userBookshelfApi']->getCountOfSavedUserBookshelves($arResult['USER_ID']));
			foreach ($savedBookshelves as $savedBookshelf):
				$savedBookshelf = $arResult['FormattingApi']->prepareText($savedBookshelf);
				$image = $arResult['bookApi']->getImage($savedBookshelf['ID']);
				?>
				<div class="user-bookshelf">
					<img src="<?= CFile::GetPath($image) ?>" height="200px" width="150px">
					<div class="user-bookshelf-description">
						<a href="/user/<?=$savedBookshelf['CREATOR_ID']?>/bookshelf/<?=$savedBookshelf['ID']?>/"><?=$savedBookshelf['TITLE']?></a>
						<span><?=$savedBookshelf['DESCRIPTION']?></span>
					</div>
				<div class="user-bookshelf-buttons">
					<input style="margin-left: 90px;" type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-save.png" height="30px" width="25px">
				</div>
				</div>
				<hr width="100%">
				<?php endforeach;?>
			<?else:
				$APPLICATION->IncludeComponent(
					'up:system.messeage',
					'',
					['MESSEAGE' => 'UP_LITLAB_BOOKSHELVES_MISSING'],
				);?>

			<?endif;?>
		</div>
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

