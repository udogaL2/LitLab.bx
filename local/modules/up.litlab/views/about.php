<?php

use Bitrix\Main\Localization\Loc;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("LitLab");
?>
<div class="about">
	<h1><?=Loc::getMessage('UP_LITLAB_ABOUT')?></h1>
	<p class="about-text">
		<?=Loc::getMessage('UP_LITLAB_ABOUT_TEXT')?>
		<br>
		<a href="/regulation/"><?=Loc::getMessage('UP_LITLAB_RULES')?></a>
	</p>
</div>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");