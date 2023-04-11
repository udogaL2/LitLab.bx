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

book list
<br>
<?php
$books = $arResult['BookshelfApi']->getListOfBook();
foreach ($books as $book):
	var_dump($book['TITLE']); ?>

	<br>
	<br>
	<br>

<?php
endforeach; ?>