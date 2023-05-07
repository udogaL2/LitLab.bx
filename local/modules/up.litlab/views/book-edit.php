<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");
CJSCore::Init(array('ajax'));

$APPLICATION->IncludeComponent(
	'up:book.edit',
	'',
	[
		'BOOK_ID' => (int)$_REQUEST['book_id'],
		'IMG' => (int)Context::getCurrent()->getRequest()->getPost('IMAGE_NAME_INPUT'),
		'TITLE' => (string)Context::getCurrent()->getRequest()->getPost('input-book-title'),
		'DESCRIPTION' => (string)Context::getCurrent()->getRequest()->getPost('input-book-description'),
		'YEAR' => (string)Context::getCurrent()->getRequest()->getPost('input-book-year'),
		'ISBN' => (string)Context::getCurrent()->getRequest()->getPost('input-book-isbn'),
		'GENRE' => Context::getCurrent()->getRequest()->getPost('genre'),
		'AUTHOR' => Context::getCurrent()->getRequest()->getPost('author'),
		'TOKEN' => Context::getCurrent()->getRequest()->getPost('token')
	]
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");