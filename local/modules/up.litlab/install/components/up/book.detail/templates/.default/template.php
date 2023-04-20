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
?>

<main class="book-detail-main">
	<div class="book-detail-card">
		<div class="book-detail-card-image">
			<img height="500px" width="400px" alt="" src="<?= $arResult['Book']['IMG_PATH'] ?>">
			<div class="book-detail-card-buttons">
				<button>буду читать</button>
				<button>прочитано</button>
			</div>
			<button style="padding: 10px 50px; background-color: #7fb255; border: 1px solid #65B95E">+ добавить</button>
		</div>
		<div class="book-detail-card-description">
			<p class="book-detail-card-description-name"> <?= $arResult['Book']['TITLE'] ?> </p>
			<p class="book-detail-card-description-author"><?= $arResult['Authors']['NAME'] ?></p>
			<div class="book-detail-card-description-rating"></div>
			<p class="book-detail-card-description-overview">
					<span> <?= $arResult['Book']['DESCRIPTION'] ?> </span>
			</p>
			<div class="book-detail-card-description-genres">
				<p style="margin-right: 20px">Жанры:</p>
				<div class="book-detail-card-description-genres-links">
					<?php foreach ($arResult['Genre'] as $id => $genre): ?>
					<a href="/books/?genre_id=<?= $id ?>"><?= $genre ?></a>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="book-detail-card-description-ISBN">
				<p style="margin-right: 20px">ISBN: <?= $arResult['Book']['ISBN'] ? : 'отсутствует' ?></p>
			</div>
			<div class="book-detail-card-description-publication-date">
				<p style="margin:20px 0">Год публикации: <?= $arResult['Book']['PUBLICATION_YEAR'] ? : 'отсутствует' ?></p>
			</div>
		</div>
	</div>
</main>
