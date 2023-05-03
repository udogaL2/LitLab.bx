<?php

/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");

$APPLICATION->IncludeComponent('up:book.create', '', [
	'TITLE' => (string)Context::getCurrent()->getRequest()->getPost('input-book-title'),
	'AUTHOR' => (string)Context::getCurrent()->getRequest()->getPost('input-book-author'),
	'TOKEN'=> (string)Context::getCurrent()->getRequest()->getPost('token'),
]);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
