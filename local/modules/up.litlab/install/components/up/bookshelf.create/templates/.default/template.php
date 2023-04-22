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

<main class="bookshelf-create-main">
	<p class="bookshelf-create-main-title"><?= Loc::getMessage('UP_LITLAB_ADD_NEW_BOOKSHELF_TITLE') ?></p>
	<form class="bookshelf-add-form" action="" method="post">
		<div class="bookshelf-create-name">
			<p><?= Loc::getMessage('UP_LITLAB_TITLE') ?></p>
			<input type="text" name="input-bookshelf-name">
		</div>
		<div class="bookshelf-create-description">
			<p><?= Loc::getMessage('UP_LITLAB_DESC') ?></p>
			<input class="bookshelf-edit-descr" type="text" style="" name="input-bookshelf-description">
		</div>
			<input type="submit" value="<?= Loc::getMessage('UP_LITLAB_SAVE') ?>">
	</form>
</main>
<?php
// $APPLICATION->IncludeComponent(
// 	"bitrix:system.auth.registration",
// 	"",
// 	[
// 	],
// 	false
// );
// ?>


