<?php
/**
 * @var CMain $APPLICATION
 */


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");

$APPLICATION->IncludeComponent(
	'up:bookshelf.edit', '',);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>


