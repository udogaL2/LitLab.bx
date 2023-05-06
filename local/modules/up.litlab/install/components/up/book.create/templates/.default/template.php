<?php

/**
 * @var array $arResult
 * @var array $arParams
 * @global CMain $APPLICATION
 */

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}
$this->addExternalCss("\local\modules\up.litlab\install\components\up\book.create\\templates\.default\add-style.css");

if (!empty($arResult['ERROR']))
{
	$APPLICATION->IncludeComponent(
		'up:system.messeage',
		'',
		['MESSEAGE' => $arResult['ERROR']],
	);
}
?>

<main class="bookshelf-create-main">
	<p class="bookshelf-create-main-title"><?= Loc::getMessage('UP_LITLAB_BOOK_ADD_REQUEST') ?></p>
	<form class="bookshelf-add-form" action="" method="post">
		<input type="hidden" name="token" value="<?=$arResult['TOKEN']?>">
		<div class="bookshelf-create-name">
			<p><?= Loc::getMessage('UP_LITLAB_TITLE') ?></p>
			<input required type="text" name="input-book-title">
		</div>
		<div class="bookshelf-create-description">
			<p><?= Loc::getMessage('UP_LITLAB_AUTHOR') ?></p>
			<input required class="bookshelf-create-author" type="text"  name="input-book-author">
		</div>
		<input class="book-create-save" type="submit" value="Сохранить">
	</form>
</main>