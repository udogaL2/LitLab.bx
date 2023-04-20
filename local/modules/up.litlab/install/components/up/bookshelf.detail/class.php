<?php

use Bitrix\Main\DI\ServiceLocator;

class LitlabBookshelDetailfComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->fetchBookshelfDetail();
		$this->prepareTemplateParams();
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	protected function prepareTemplateParams()
	{
		$formattingAPI = ServiceLocator::getInstance()->get('Formatting');
		$this->arResult['Bookshelf'] = $formattingAPI->prepareText($this->arResult['Bookshelf']);
	}

	protected function fetchBookshelfDetail()
	{
		$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');

		$bookshelfInfo = $bookshelfApi->getDetailsById($this->arParams['BOOKSHELF_ID'], $this->arParams['USER_ID']);

		if (!$bookshelfInfo)
		{
			LocalRedirect('/404');
		}

		$userApi = ServiceLocator::getInstance()->get('User');

		$this->arResult['Bookshelf'] = $bookshelfInfo;
		$this->arResult['Bookshelf']['Tags'] = $bookshelfApi->getTags($this->arParams['BOOKSHELF_ID']);
		$this->arResult['Bookshelf']['SavesCount'] = $bookshelfApi->getCountOfSavedBookshelves($this->arParams['BOOKSHELF_ID']);
		$this->arResult['Bookshelf']['Creator'] = $userApi->getCreatorInfo($this->arParams['USER_ID']);
	}
}