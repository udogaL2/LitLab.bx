<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");


if($_SESSION['NAME'] && $_SERVER['REQUEST_URI'] !== '/logout/')
{
    LocalRedirect("/user/{$_SESSION['USER_ID']}/");
}

$APPLICATION->IncludeComponent('up:auth', '',[
	'NAME' => (string)Context::getCurrent()->getRequest()->getPost('login'),
	'PASSWORD' => (string)Context::getCurrent()->getRequest()->getPost('pass'),
	'TOKEN'=> (string)Context::getCurrent()->getRequest()->getPost('token'),
	]
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");