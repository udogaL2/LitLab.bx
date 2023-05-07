<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");
?>
	<div class="about">
		<h1><?= \Bitrix\Main\Localization\Loc::getMessage('UP_LITLAB_RULES') ?></h1>
		<p class="about-text">
			<?=\Bitrix\Main\Localization\Loc::getMessage('UP_LITLAB_RULES_TEXT')?>
		</p>
	</div>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");