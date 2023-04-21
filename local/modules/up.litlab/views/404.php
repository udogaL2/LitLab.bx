<?php
/**
 * @var CMain $APPLICATION
 */


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");
?>

<?php
$APPLICATION->IncludeComponent(
	'up:system.messeage',
	'',
	['MESSEAGE' => 'UP_LITLAB_PAGE_NOT_FOUND'],
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");