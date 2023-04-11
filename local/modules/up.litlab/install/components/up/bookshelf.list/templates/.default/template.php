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

<div class="columns">
	<?php
	$bookshelves = $arResult['BookshelfApi']->getListOfBookshelf();
	foreach ($bookshelves as $bookshelf):
		var_dump($bookshelf['TITLE']); ?>

		<br>
		<br>
		<br>

	<?php
	endforeach; ?>
</div>
