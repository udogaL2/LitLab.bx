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
		<p>Ваши книжные полки</p>
		<a href="/create/bookshelf/">Добавить новую полку</a>
	</div>
	<div class="user-profile">
		<div class="user-profile-card">
			<p class="user-profile-card-nickname"><?=$arResult['userApi']->getUserName((int)$userId)?></p>
			<hr>
			<a href="/create/book/" style="text-decoration: none">Добавить свою книгу</a>
			<hr>
			<a href="/logout/">Выйти</a>
		</div>

	<? else:
		if ($arResult['userApi']->getCreatorInfo($userId)!==false):?>
		<div class="user-header">
			<p>Книжные полки автора <?=$arResult['userApi']->getUserName((int)$userId)?></p>
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
				$publicpage = 0;
				$userBookshelfs = $arResult['userBookshelfApi']->getListOfUserBookshelf(
					$userId,
					$nav->getLimit(),
					$nav->getOffset()
				);
			}
			else{
				$publicpage = 1;
				$userBookshelfs = $arResult['userBookshelfApi']->getListOfUserBookshelf(
					$userId,
					$nav->getLimit(),
					$nav->getOffset(),
					['public']
				);
			}
			$nav->setRecordCount($arResult['userBookshelfApi']->getUserBookshelfCount($userId));
			foreach ($userBookshelfs as $userBookshelf):
				$userBookshelf = $arResult['FormattingApi']->prepareText($userBookshelf);
				$image = $arResult['bookApi']->getImage($userBookshelf['ID']);
			?>
			<? if ($publicpage === 1):?>
			<div class="user-bookshelf" style="width: 45%">
			<?else:?>
			<div class="user-bookshelf">
			<? endif;?>
				<img src="<?= CFile::GetPath($image) ?>" height="200px" width="150px">
				<div class="user-bookshelf-description">
					<a href="/user/<?=$userBookshelf['CREATOR_ID']?>/bookshelf/<?=$userBookshelf['ID']?>/"><?=$userBookshelf['TITLE']?></a>
					<span><?=$userBookshelf['DESCRIPTION']?></span>
				</div>
				<? if ($publicpage === 0): ?>
				<div class="user-bookshelf-buttons">
					<input type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-lock.png" height="30px" width="25px">
					<a href="/edit/bookshelf/<?=$userBookshelf['ID']?>/">Изменить</a>
				</div>
				<? endif;?>
			</div>
			<? if ($publicpage === 0): ?>
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
	?>
</main>
