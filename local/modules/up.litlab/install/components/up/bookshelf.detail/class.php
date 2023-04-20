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
		$userApi = ServiceLocator::getInstance()->get('User');

		if (isset($_SESSION['NAME']) && $this->arParams['USER_ID'] === (int)$userApi->getUserId($_SESSION['NAME']))
		{
			$bookshelfInfo = $bookshelfApi->getDetailsById($this->arParams['BOOKSHELF_ID'], $this->arParams['USER_ID']);
		}
		else
		{
			$bookshelfInfo = $bookshelfApi->getDetailsById(
				$this->arParams['BOOKSHELF_ID'],
				$this->arParams['USER_ID'],
				['public']
			);
		}
		if (!$bookshelfInfo)
		{
			LocalRedirect('/404');
		}

		$userApi = ServiceLocator::getInstance()->get('User');

		$this->arResult['Bookshelf'] = $bookshelfInfo;
		$this->arResult['Bookshelf']['BookCount'] = $bookshelfApi->getCountInBookshelf($this->arParams['BOOKSHELF_ID']);
		$this->arResult['Bookshelf']['Tags'] = $bookshelfApi->getTags($this->arParams['BOOKSHELF_ID']);
		$this->arResult['Bookshelf']['SavesCount'] = $bookshelfApi->getCountOfSavedBookshelves($this->arParams['BOOKSHELF_ID']);
		$this->arResult['Bookshelf']['Creator'] = $userApi->getCreatorInfo($this->arParams['USER_ID']);
	}
}