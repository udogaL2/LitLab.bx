<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;
$request = Context::getCurrent()->getRequest()->getRequestMethod();
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
	$APPLICATION->SetTitle("LitLab");
CJSCore::Init(array('ajax'));

$APPLICATION->IncludeComponent(
	'up:bookshelf.edit', '',[
	'BOOKSHELF_ID' => (int)$_REQUEST['id'],
	'TITLE' => (string)Context::getCurrent()->getRequest()->getPost('title'),
	'DESCRIPTION' => (string)Context::getCurrent()->getRequest()->getPost('description'),
	'TAGS' => Context::getCurrent()->getRequest()->getPost('tags'),
	'COMMENT' => Context::getCurrent()->getRequest()->getPost('comment'),
	'STATUS' => Context::getCurrent()->getRequest()->getPost('status'),
	'ITEM_ID' => Context::getCurrent()->getRequest()->getPost('item'),
	'DELETED' => Context::getCurrent()->getRequest()->getPost('delete'),
	'BOOK-DELETED' => Context::getCurrent()->getRequest()->getPost('deleteBook'),
	'TOKEN'=> (string)Context::getCurrent()->getRequest()->getPost('token'),
]);

	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
?>


