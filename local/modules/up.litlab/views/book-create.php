<?php

/**
 * @var CMain $APPLICATION
 */


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");

$APPLICATION->IncludeComponent('up:book.create', '');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
