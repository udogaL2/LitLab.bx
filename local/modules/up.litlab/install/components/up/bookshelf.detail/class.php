<?php

use Bitrix\Main\Context;
use Bitrix\Main\DI\ServiceLocator;

class LitlabBookshelfDetailComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->prepareSession();
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

	protected function prepareSession()
	{
		if($_SESSION['USER_ID'])
		{
			$userApi = ServiceLocator::getInstance()->get('User');
			$this->arResult['USER']['ID'] = $_SESSION['USER_ID'];
			$this->arResult['USER']['ROLE'] = $userApi->getUserRole($_SESSION['USER_ID']);
		}
	}

	protected function fetchBookshelfDetail()
	{
		$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');

		if (isset($this->arResult['USER']) && $this->arParams['USER_ID'] === (int)$this->arResult['USER']['ID'])
		{
			$bookshelfInfo = $bookshelfApi->getDetailsById($this->arParams['BOOKSHELF_ID'], $this->arParams['USER_ID']);
		}
		elseif (isset($this->arResult['USER']['ID']) && $this->arResult['USER']['ROLE'] === 'admin')
		{
			$bookshelfInfo = $bookshelfApi->getDetailsById($this->arParams['BOOKSHELF_ID'], $this->arParams['USER_ID'], ['public', 'moderation', 'modification']);
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
			return;
		}

		$userApi = ServiceLocator::getInstance()->get('User');

		$this->arResult['Bookshelf'] = $bookshelfInfo;
		$this->arResult['Bookshelf']['Tags'] = $bookshelfApi->getTags($this->arParams['BOOKSHELF_ID']);
		$this->arResult['Bookshelf']['SavesCount'] = $bookshelfApi->getSavesCount(
			$this->arParams['BOOKSHELF_ID']
		);
		$this->arResult['Bookshelf']['Creator'] = $userApi->getCreatorInfo($this->arParams['USER_ID']);

		if ($_SESSION['USER_ID'])
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