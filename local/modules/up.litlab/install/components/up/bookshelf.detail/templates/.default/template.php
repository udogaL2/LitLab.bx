<?php

/**
 * @var array $arResult
 * @var array $arParams
 */

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}
$this->addExternalCss(
	"\local\modules\up.litlab\install\components\up\bookshelf.detail\\templates\.default\add-style.css"
);
$this->addExternalCss(
	"\local\modules\up.litlab\install\components\up\bookshelf.detail\\templates\.default\add-two-style.css"
);
?>

<section class="bookshelf-detail-main">
	<div class="bookshelf-detail-overview">
		<p class="bookshelf-detail-name">Книжная полка "<?= $arResult['Bookshelf']['TITLE'] ?>" от
			<a class="bookshelf-detail-author-name" href="/user/<?= $arResult['Bookshelf']['Creator']['ID'] ?>/"><?= $arResult['Bookshelf']['Creator']['NAME'] ?></a>
		</p>
		<p class="bookshelf-detail-description"><?= $arResult['Bookshelf']['DESCRIPTION'] ?></p>
		<?php if($arResult['Bookshelf']['Tags'][0]): ?>
			<div class="book-detail-card-description-genres">
				<p style="margin-right: 20px">Теги:</p>
				<div class="book-detail-card-description-genres-links">
					<?php foreach ($arResult['Bookshelf']['Tags'] as $tag):?>
					<p><?= $tag ?></p>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="bookshelf-detail-info">
			<p>Создано: <?= $arResult['Bookshelf']['DATE_CREATED'] ?></p>
			<p>Последнее обновление: <?= $arResult['Bookshelf']['DATE_UPDATED'] ?></p>
		</div>
	</div>
	<div class="bookshelf-detail-buttons">
		<input src="\local\modules\up.litlab\install\templates\litlab\images\icon-save.png" type="image" height="25px" width="20px">
		<p><?= $arResult['Bookshelf']['SavesCount'] ?></p>
		<input src="\local\modules\up.litlab\install\templates\litlab\images\icon-like.png" type="image" height="25px" width="30px">
		<p><?= $arResult['Bookshelf']['LIKES'] ?></p>
		<img src="\local\modules\up.litlab\install\templates\litlab\images\icon-book.png" height="25px" width="25px">
		<p><?= $arResult['Bookshelf']['BOOK_COUNT'] ?></p>
	</div>
</section>
<section class="bookshelf-detail-card-list">
	<div class="bookshelf-list-cards">
		<?php
		$APPLICATION->IncludeComponent(
			'up:book.list',
			'bookshelf_template',
			['BOOKSHELF_ID' => (int)$_REQUEST['bookshelf_id']]
		);
		?>
	</div>
</section>