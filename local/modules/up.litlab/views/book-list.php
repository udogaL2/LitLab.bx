<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");

$APPLICATION->IncludeComponent(
	'up:book.list',
	'',
	['SEARCH' => Context::getCurrent()->getRequest()->getValues()['search'],
	 'GENRE_ID' => (int)Context::getCurrent()->getRequest()->getValues()['genre_id']]
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");