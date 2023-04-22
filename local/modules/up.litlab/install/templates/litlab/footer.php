
<?php
	$userApi = new \Up\Litlab\API\User();
?>
</section>
<footer>
	<div class="footer-wrapper">
		<p class="footer-copyright">Copyright 2023. Все права защищены.</p>
		<div class="footer-links">
			<a class="footer-link" href="/">Главная</a>
			<a class="footer-link" href="/books/">Книги</a>
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