<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");

$APPLICATION->IncludeComponent(
	'up:bookshelf.list',
	'',
	['SEARCH' => Context::getCurrent()->getRequest()->getValues()['search']]
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");