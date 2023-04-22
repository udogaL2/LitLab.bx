<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");

$APPLICATION->IncludeComponent(
	'up:bookshelf.edit', '',[
	'BOOKSHELF_ID' => (int)$_REQUEST['id'],
	'TITLE' => (string)Context::getCurrent()->getRequest()->getPost('title'),
	'DESCRIPTION' => (string)Context::getCurrent()->getRequest()->getPost('description'),
	'TAGS' => Context::getCurrent()->getRequest()->getPost('tags'),
	'TAGS-CREATED' => Context::getCurrent()->getRequest()->getPost('tags-created'),
	'COMMENT' => Context::getCurrent()->getRequest()->getPost('comment'),
]);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>


