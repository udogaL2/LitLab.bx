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
	$projects = $arResult['BookshelfApi']->getListOfBookshelf();
	foreach ($projects as $project):
		var_dump($project['TITLE']); ?>

		<br>
		<br>
		<br>

	<?php
	endforeach; ?>
</div>
