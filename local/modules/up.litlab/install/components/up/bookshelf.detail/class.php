<?php

use Bitrix\Main\DI\ServiceLocator;

class LitlabBookshelDetailfComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->fetchBookshelfDetail();
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	protected function fetchBookshelfDetail()
	{
		$BookshelfAPI = ServiceLocator::getInstance()->get('Bookshelf');

		$bookshelfInfo = $BookshelfAPI->getDetailsById($this->arParams['BOOKSHELF_ID']);

		if (!$bookshelfInfo)
			LocalRedirect('/404');

		$this->arResult['Bookshelf'] = $bookshelfInfo;
	}
}