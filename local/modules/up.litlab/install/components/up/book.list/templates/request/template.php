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

if ((!$arResult['USER']['ID']) || ($arResult['USER']['ROLE'] !== 'admin'))
{
	$APPLICATION->IncludeComponent(
		'up:system.messeage',
		'',
		['MESSEAGE' => 'UP_LITLAB_ACCESS_IS_RESTRICTED'],
	);
}
else
{
	?>
	<main class="book-list-main">
		<div class="main-header">
			<p class="main-title"><?= Loc::getMessage('UP_LITLAB_REQUEST_BOOKS') ?></p>
		</div>

		<div class="book-list-cards">
			<?php
			$nav = new \Bitrix\Main\UI\PageNavigation('page');
			$nav->allowAllRecords(false)->setPageSize(12)->initFromUri();

			$books = $arResult['BookApi']->getListOfRequest(
				$nav->getLimit(),
				$nav->getOffset(),
				status: $arResult['STATUS']
			);

			if (!$books[0])
			{
				$APPLICATION->IncludeComponent(
					'up:system.messeage',
					'',
					['MESSEAGE' => 'UP_LITLAB_BOOKS_MISSING'],
				);
			}
			else
			{
			$bookIds = [];

			foreach ($books as $book)
			{
				$bookIds[] = $book['ID'];
			}

			$nav->setRecordCount($arResult['BookApi']->getCount(status: $arResult['STATUS']));
			foreach ($books as $book):
				$book = $arResult['FormattingApi']->prepareText($book);
				?>
				<a class="book-list-card-name" href="/edit/book/<?=$book['ID']?>/">
					<div class="book-list-card">
						<p class="book-list-card-name">
							<strong><?= $book['TITLE'] ?></strong>
						</p><br>
						<p class="book-list-card-description"><?= $book['DESCRIPTION'] ?></p>
					</div>
				</a>
			<?php
			endforeach; ?>
		</div>
		<?php
		$APPLICATION->IncludeComponent(
			"bitrix:main.pagenavigation",
			"",
			[
				"NAV_OBJECT" => $nav,
				"SEF_MODE" => "Y",
			],
			false
		);
		}
		?>
	</main>
	<?php
}
?>