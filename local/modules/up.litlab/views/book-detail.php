<?php
/**
 * @var CMain $APPLICATION
 */


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");

$APPLICATION->IncludeComponent(
	'up:book.detail',
	'',
	[
		'BOOK_ID' => (int)$_REQUEST['id'],
	]
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");