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

if ($arResult['MESSEAGE'])
{
	$APPLICATION->IncludeComponent(
		'up:system.messeage',
		'',
		['MESSEAGE' => $arResult['MESSEAGE']],
	);
}
else
{
	?>
	<section class="bookshelf-detail-main">
		<div class="bookshelf-detail-overview">
			<p class="bookshelf-detail-name"><?= Loc::getMessage(
					'UP_LITLAB_BOOKSHELF'
				) ?> "<?= $arResult['Bookshelf']['TITLE'] ?>" <?= Loc::getMessage('UP_LITLAB_FROM') ?>
				<a class="bookshelf-detail-author-name" href="/user/<?= $arResult['Bookshelf']['Creator']['ID'] ?>/"><?= $arResult['Bookshelf']['Creator']['USERNAME'] ?></a>
			</p>
			<p class="bookshelf-detail-description"><?= $arResult['Bookshelf']['DESCRIPTION'] ?></p>
			<?php
			if (array_values($arResult['Bookshelf']['Tags'])[0]): ?>
				<div class="book-detail-card-description-genres">
					<p style="margin-right: 20px"><?= Loc::getMessage('UP_LITLAB_TAGS') ?>:</p>
					<div class="book-detail-card-description-genres-links">
						<?php
						foreach ($arResult['Bookshelf']['Tags'] as $tag): ?>
							<p><?= $tag ?></p>
						<?php
						endforeach; ?>
					</div>
				</div>
			<?php
			endif; ?>
			<div class="bookshelf-detail-info">
				<p><?= Loc::getMessage('UP_LITLAB_CREATED') ?>: <?= $arResult['Bookshelf']['DATE_CREATED'] ?></p>
				<p><?= Loc::getMessage('UP_LITLAB_LAST_UPDATE') ?>: <?= $arResult['Bookshelf']['DATE_UPDATED'] ?></p>
			</div>

			<?php
			if ($arResult['USER']['ID'] && $arResult['USER']['ROLE'] === 'admin'):
				?>
				<div class="bookshelf-detail-admin-section">
					<?php
					if ($arResult['Bookshelf']['STATUS'] !== 'modification'):
						if ($arResult['Bookshelf']['STATUS'] === 'moderation'): ?>
							<button class="publication-button" onclick="publishBookshelf(<?=$arResult['Bookshelf']['ID']?>)"><?=Loc::getMessage('UP_LITLAB_PUBLICATION')?></button>
						<?php endif;?>
						<button class="modification-button" onclick="modificationBookshelf(<?=$arResult['Bookshelf']['ID']?>)"><?=Loc::getMessage('UP_LITLAB_MODIFICATION')?></button>
					<?php endif;?>
					<p><?= Loc::getMessage('UP_LITLAB_STATUS') ?>:<p class="status-info"><?= Loc::getMessage($arParams['STATUS_LIST'][$arResult['Bookshelf']['STATUS']]) ?></p></p>
				</div>
			<?php
			endif;
			?>
		</div>
		<div class="bookshelf-detail-buttons">
			<?php
			if (isset($_SESSION['NAME'])):?>
				<div class="shelf-saves">
					<?php
					if ($arResult['Bookshelf']['SAVED']):
						?>
						<input name="unsave" class="saved" onclick="saveBookshelf(<?= $arResult['Bookshelf']['ID'] ?>)" type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-save-saved.png" height="25px" width="20px">
					<?php
					else:
						?>
						<input name="save" onclick="saveBookshelf(<?= $arResult['Bookshelf']['ID'] ?>)" class="shelf-save-input" type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-save.png" height="25px" width="20px">
					<?php
					endif;
					?>
					<p class="save-amount"><?= $arResult['Bookshelf']['SavesCount'] ?></p>
				</div>
				<div class="shelf-likes">
					<?php
					if ($arResult['Bookshelf']['LIKED']):
						?>
						<input class="liked" onclick="likeBookshelf(<?= $arResult['Bookshelf']['ID'] ?>)" type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-like-liked.png" height="25px" width="30px">
					<?php
					else:
						?>
						<input class="shelf-likes-input" onclick="likeBookshelf(<?= $arResult['Bookshelf']['ID'] ?>)" type="image" src="\local\modules\up.litlab\install\templates\litlab\images\icon-like.png" height="25px" width="30px">
					<?php
					endif;
					?>
					<p class="likes-amount"><?= $arResult['Bookshelf']['LIKES'] ?></p>
				</div>
			<?php
			else:
				?>
				<img src="\local\modules\up.litlab\install\templates\litlab\images\icon-save.png" height="25px" width="20px">
				<p><?= $arResult['Bookshelf']['SavesCount'] ?></p>
				<img src="\local\modules\up.litlab\install\templates\litlab\images\icon-like.png" height="25px" width="30px">
				<p><?= $arResult['Bookshelf']['LIKES'] ?></p>
			<?php
			endif;
			?>
			<img src="\local\modules\up.litlab\install\templates\litlab\images\icon-book.png" height="25px" width="25px">
			<p><?= $arResult['Bookshelf']['BOOK_COUNT'] ?></p>
		</div>
	</section>
	<section class="bookshelf-detail-card-list">
		<div class="bookshelf-list-cards">
			<?php
			$APPLICATION->IncludeComponent(
				'up:book.list', 'bookshelf_template', ['BOOKSHELF_ID' => (int)$_REQUEST['bookshelf_id']]
			);
			?>
		</div>
	</section>
	<?php
}
