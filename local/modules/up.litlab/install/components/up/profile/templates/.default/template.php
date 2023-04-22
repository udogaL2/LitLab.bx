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
		$uri = $APPLICATION->GetCurUri();
		$userId = trim($uri, "/user/");
	if (isset($_SESSION['NAME']) && $userId===$arResult['userApi']->getUserId($_SESSION['NAME'])):?>
	<div class="user-header">
		<p><?= Loc::getMessage('UP_LITLAB_YOURS_BOOKSHELVES') ?></p>
		<a href="/create/bookshelf/"><?=Loc::getMessage('UP_LITLAB_CREATE_BOOKSHELF')?></a>
	</div>
	<div class="user-profile">
		<div class="user-profile-card">
			<p class="user-profile-card-nickname"><?=$arResult['userApi']->getUserName((int)$userId)?></p>
			<hr>
			<a href="/create/book/" style="text-decoration: none"><?=Loc::getMessage('UP_LITLAB_ADD_BOOK')?></a>
			<hr>
			<a href="/logout/"><?=Loc::getMessage('UP_LITLAB_LOGOUT')?></a>
		</div>

	<? else:
		if ($arResult['userApi']->getCreatorInfo($userId)!==false):?>
		<div class="user-header">
			<p><?=Loc::getMessage('UP_LITLAB_BOOKSHELVES_OF_USER')?> <?=$arResult['userApi']->getUserName((int)$userId)?></p>
		</div>

		<?// else:
		// LocalRedirect('/404');
		endif;
		endif;?>
		<div class="user-bookshelf-list" style="width: 95%">
			<?php
			session_start();
			$nav = new \Bitrix\Main\UI\PageNavigation('page');
			$nav->allowAllRecords(false)->setPageSize(4)->initFromUri();

			if (isset($_SESSION['NAME']) && $userId===$arResult['userApi']->getUserId($_SESSION['NAME']))
			{
				$publicPage = 0;
				$userBookshelves = $arResult['userBookshelfApi']->getListOfUserBookshelf(
					$userId,
					$nav->getLimit(),
					$nav->getOffset()
				);
			}
			else{
				$publicPage = 1;
				$userBookshelves = $arResult['userBookshelfApi']->getListOfUserBookshelf(
					$userId,
					$nav->getLimit(),
					$nav->getOffset(),
					['public']
				);
			}

			if(!$userBookshelves[0])
			{
				$APPLICATION->IncludeComponent(
					'up:system.messeage',
					'',
					['MESSEAGE' => 'UP_LITLAB_USER_BOOKSHELVES_MISSING'],
				);
			}
			else{
			$nav->setRecordCount($arResult['userBookshelfApi']->getUserBookshelfCount($userId));
			foreach ($userBookshelves as $userBookshelf):
				$userBookshelf = $arResult['FormattingApi']->prepareText($userBookshelf);
				$image = $arResult['bookApi']->getImage($userBookshelf['ID']);
			?>
			<? if ($publicPage === 1):?>
			<div class="user-bookshelf" style="width: 45%">
			<?else:?>
			<div class="user-bookshelf">
			<? endif;?>
				<img src="<?= CFile::GetPath($image) ?>" height="200px" width="150px">
				<div class="user-bookshelf-description">
					<a href="/user/<?=$userBookshelf['CREATOR_ID']?>/bookshelf/<?=$userBookshelf['ID']?>/"><?=$userBookshelf['TITLE']?></a>
					<span><?=$userBookshelf['DESCRIPTION']?></span>
				</div>
				<? if ($publicPage === 0): ?>
				<div class="user-bookshelf-buttons">
					<input type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-lock.png" height="30px" width="25px">
					<a href="/edit/bookshelf/<?=$userBookshelf['ID']?>/"><?=Loc::getMessage('UP_LITLAB_EDIT')?></a>
				</div>
				<? endif;?>
			</div>
			<? if ($publicPage === 0): ?>
				<hr width="100%">
			<? endif;?>
			<?php endforeach;?>
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
	}
	?>
</main>
