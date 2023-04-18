<?php
/**
 * @var CMain $APPLICATION
 */


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");

$APPLICATION->IncludeComponent(
	'up:bookshelf.detail',
	'',
	[
		'BOOKSHELF_ID' => (int)$_REQUEST['bookshelf_id'],
		'USER_ID' => (int)$_REQUEST['user_id'],
	]
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");