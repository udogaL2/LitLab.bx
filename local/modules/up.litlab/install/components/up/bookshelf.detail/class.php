<?php

use Bitrix\Main\Context;
use Bitrix\Main\DI\ServiceLocator;

class LitlabBookshelfDetailComponent extends CBitrixComponent
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
				$this->arParams['BOOKSHELF_ID'], $this->arParams['USER_ID'], ['public']
			);
		}
		if (!$bookshelfInfo)
		{
			$this->arResult['MESSEAGE'] = 'UP_LITLAB_BOOKSHELF_MISSING';
		}

		$userApi = ServiceLocator::getInstance()->get('User');

		$this->arResult['Bookshelf'] = $bookshelfInfo;
		$this->arResult['Bookshelf']['Tags'] = $bookshelfApi->getTags($this->arParams['BOOKSHELF_ID']);
		$this->arResult['Bookshelf']['SavesCount'] = $bookshelfApi->getSavesCount(
			$this->arParams['BOOKSHELF_ID']
		);
		$this->arResult['Bookshelf']['Creator'] = $userApi->getCreatorInfo($this->arParams['USER_ID']);

		if ($_SESSION['NAME'])
		{
			$bookshelfId = $this->arParams['BOOKSHELF_ID'];
			$userId = ServiceLocator::getInstance()->get('User')->getUserId($_SESSION['NAME']);
			$likedFlag = $bookshelfApi->isLiked($bookshelfId, $userId);
			$savedFlag = $bookshelfApi->isSaved($bookshelfId, $userId);
			$this->arResult['Bookshelf']['LIKED'] = $likedFlag;
			$this->arResult['Bookshelf']['SAVED'] = $savedFlag;
		}
	}
}