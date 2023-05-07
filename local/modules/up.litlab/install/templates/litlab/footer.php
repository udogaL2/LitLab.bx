
<?php

use Bitrix\Main\Localization\Loc;

$userApi = new \Up\Litlab\API\User();
?>
</section>
<footer>
	<div class="footer-wrapper">
		<p class="footer-copyright"><?= Loc::getMessage('UP_LITLAB_COPYRIGHT') ?></p>
		<div class="footer-links">
			<a class="footer-link" href="/"><?= Loc::getMessage('UP_LITLAB_HOME') ?></a>
			<a class="footer-link" href="/books/"><?= Loc::getMessage('UP_LITLAB_BOOKS') ?></a>
			<a class="footer-link" href="/about/"><?= Loc::getMessage('UP_LITLAB_ABOUT') ?></a>
			<a class="footer-link" href="/regulation/"><?= Loc::getMessage('UP_LITLAB_RULES') ?></a>
			<?if (isset($_SESSION['NAME'])):
				$userId = $userApi->getUserId($_SESSION['NAME'])?>
				<a class="footer-link" href="/user/<?=$userId?>/"><?=Loc::getMessage('UP_LITLAB_USER_ACCOUNT')?></a>
			<?else:?>
			<a class="footer-link" href="/auth/"><?=Loc::getMessage('UP_LITLAB_USER_ACCOUNT')?></a>
			<? endif;?>
		</div>
	</div>
</footer>
</body>
</html>