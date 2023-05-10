<?php

/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;
use Bitrix\Main\DI\ServiceLocator;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");

if ($_SESSION['USER_ID'] && ServiceLocator::getInstance()->get('User')->getUserRole($_SESSION['USER_ID']) === 'admin')
{
	$APPLICATION->IncludeComponent(
		'up:book.edit',
		'',
		[
			'BOOK_ID' => (int)$_REQUEST['id'],
			'IMG' => (int)Context::getCurrent()->getRequest()->getPost('IMAGE_NAME_INPUT'),
			'TITLE' => (string)Context::getCurrent()->getRequest()->getPost('input-book-title'),
			'DESCRIPTION' => (string)Context::getCurrent()->getRequest()->getPost('input-book-description'),
			'YEAR' => (string)Context::getCurrent()->getRequest()->getPost('input-book-year'),
			'ISBN' => (string)Context::getCurrent()->getRequest()->getPost('input-book-isbn'),
			'GENRE' => Context::getCurrent()->getRequest()->getPost('genre'),
			'AUTHOR' => Context::getCurrent()->getRequest()->getPost('author'),
			'TITLE_FLAG' => true,
		]
	);
}
else{
	$APPLICATION->IncludeComponent('up:book.create', '', [
		'TITLE' => (string)Context::getCurrent()->getRequest()->getPost('input-book-title'),
		'AUTHOR' => (string)Context::getCurrent()->getRequest()->getPost('input-book-author'),
	]);
}


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
