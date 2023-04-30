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
		$userId = (string)$arResult['USER_ID'];
	if (isset($_SESSION['NAME']) && $userId===$arResult['userApi']->getUserId($_SESSION['NAME'])):?>
	<div class="user-header">
		<p><?= Loc::getMessage('UP_LITLAB_YOURS_BOOKSHELVES') ?></p>
		<a href="/create/bookshelf/"><?=Loc::getMessage('UP_LITLAB_CREATE_BOOKSHELF')?></a>
	</div>
	<div class="user-profile">
		<div class="user-profile-card">

			<p class="user-profile-card-nickname"><?=$arResult['userApi']->getUserNickname($userId)?></p>
			<hr>
			<a href="/create/book/" style="text-decoration: none"><?=Loc::getMessage('UP_LITLAB_ADD_BOOK')?></a>
			<hr>
			<a href="/logout/"><?=Loc::getMessage('UP_LITLAB_LOGOUT')?></a>
		</div>

	<? else:
		if ($arResult['userApi']->getCreatorInfo((int)$userId)!==false):?>
		<div class="user-header">
			<p><?=Loc::getMessage('UP_LITLAB_BOOKSHELVES_OF_USER')?> <?=$arResult['userApi']->getUserNickname($userId)?></p>
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
					['public', 'private', 'moderated'],
					$nav->getLimit(),
					$nav->getOffset(),
				);
			}
			else{
				$publicPage = 1;
				$userBookshelves = $arResult['userBookshelfApi']->getListOfUserBookshelf(
					$userId,
					['public'],
					$nav->getLimit(),
					$nav->getOffset(),

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
					<div class="user-bookshelf-buttons-status">
					<?if ($userBookshelf['STATUS'] === 'private'):?>
					<input class="status" type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-lock.png" height="30px" width="25px">
					<p class="status-descr">Доступна только вам</p>
					<?elseif ($userBookshelf['STATUS'] === 'public'):?>
					<input class="status" type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-unlock.png" height="30px" width="25px">
					<p class="status-descr">Видна всем</p>
					<?else:?>
						<input class="status" type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-moderated.png" height="30px" width="30px">
						<p class="status-descr">На модерации</p>
					<?endif;?>
					</div>
					<a href="/edit/bookshelf/<?=$userBookshelf['ID']?>/"><?=Loc::getMessage('UP_LITLAB_EDIT')?></a>
				</div>
				<? endif;?>
			</div>
			<? if ($publicPage === 0): ?>
				<hr width="100%">
			<? endif;?>
		<?php endforeach;?>
		<?}?>

		<?php
		//
		// 	$savedBookshelves = $arResult['userBookshelfApi']->getListOfSavedBookshelf(
		// 		$userId,
		// 		$nav->getRecordCount()%$nav->getLimit(),
		// 		$nav->getOffset()
		// 	);
		// if($savedBookshelves[0]):
		// foreach ($savedBookshelves as $savedBookshelf):
		// 	$savedBookshelf = $arResult['FormattingApi']->prepareText($savedBookshelf);
		// 	$image = $arResult['bookApi']->getImage($savedBookshelf['ID']);
		// 	?>
		<!--	<div class="user-bookshelf">-->
		<!--		<img src="--><?//= CFile::GetPath($image) ?><!--" height="200px" width="150px">-->
		<!--		<div class="user-bookshelf-description">-->
		<!--			<a href="/user/--><?//=$savedBookshelf['CREATOR_ID']?><!--/bookshelf/--><?//=$savedBookshelf['ID']?><!--/">--><?//=$savedBookshelf['TITLE']?><!--</a>-->
		<!--			<span>--><?//=$savedBookshelf['DESCRIPTION']?><!--</span>-->
		<!--		</div>-->
		<!--	<div class="user-bookshelf-buttons">-->
		<!--		<input style="margin-left: 90px;" type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-save.png" height="30px" width="25px">-->
		<!--	</div>-->
		<!--	</div>-->
		<!--	<hr width="100%">-->
		<!--	--><?php //endforeach;?>
		<?//endif;?>
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
