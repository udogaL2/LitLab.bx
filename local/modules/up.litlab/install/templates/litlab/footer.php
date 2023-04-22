
<?php
	$userApi = new \Up\Litlab\API\User();
?>
</section>
<footer>
	<div class="footer-wrapper">
		<p class="footer-copyright"><?= \Bitrix\Main\Localization\Loc::getMessage('UP_LITLAB_COPYRIGHT') ?></p>
		<div class="footer-links">
			<a class="footer-link" href="/"><?= \Bitrix\Main\Localization\Loc::getMessage('UP_LITLAB_HOME') ?></a>
			<a class="footer-link" href="/books/"><?= \Bitrix\Main\Localization\Loc::getMessage('UP_LITLAB_BOOKS') ?></a>
			<?if (isset($_SESSION['NAME'])):
				$userId = $userApi->getUserId($_SESSION['NAME'])?>
				<a class="footer-link" href="/user/<?=$userId?>/">Личный кабинет</a>
			<?else:?>
			<a class="footer-link" href="/auth/">Личный кабинет</a>
			<? endif;?>
		</div>
	</div>
</footer>
</body>
</html>