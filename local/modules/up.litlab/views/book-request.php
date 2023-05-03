<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");

$APPLICATION->IncludeComponent(
	'up:book.list',
	'request',
	['STATUS' => ['moderation']]
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");