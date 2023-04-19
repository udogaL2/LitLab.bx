<?php

/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");

$APPLICATION->IncludeComponent('up:bookshelf.create', '', [
	'TITLE' => (string)Context::getCurrent()->getRequest()->getPost('input-bookshelf-name'),
	'DESCRIPTION' => (string)Context::getCurrent()->getRequest()->getPost('input-bookshelf-description'),
]);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");