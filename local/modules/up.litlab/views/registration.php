<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");

if($_SESSION['NAME'])
{
	LocalRedirect("/user/{$_SESSION['USER_ID']}/");
}

$APPLICATION->IncludeComponent('up:register', '',[
   'NAME' => (string)Context::getCurrent()->getRequest()->getPost('login'),
   'USERNAME' => (string)Context::getCurrent()->getRequest()->getPost('username'),
   'PASSWORD' => (string)Context::getCurrent()->getRequest()->getPost('pass'),]
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
