<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");

$APPLICATION->IncludeComponent('up:auth', '',[
	'NAME' => (string)Context::getCurrent()->getRequest()->getPost('login'),
	'PASSWORD' => (string)Context::getCurrent()->getRequest()->getPost('pass'),]
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");