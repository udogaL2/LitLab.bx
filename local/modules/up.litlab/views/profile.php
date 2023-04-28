<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");

$APPLICATION->IncludeComponent('up:profile', '', [
	'USER_ID' => (int)$_REQUEST['user_id'],
	]);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");