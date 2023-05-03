<?php

use Bitrix\Main\DI\ServiceLocator;

class LitlabBookDetailfComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->prepareTemplateParams();
		$this->fetchBookDetail();
		$this->prepareSession();
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	protected function prepareSession()
	{
		if($_SESSION['USER_ID'])
		{
			$this->arResult['USER']['ID'] = $_SESSION['USER_ID'];
			$this->arResult['USER']['ROLE'] = $this->arResult['userApi']->getUserRole($_SESSION['USER_ID']);
		}
	}

	protected function prepareTemplateParams()
	{
		$formattingAPI = ServiceLocator::getInstance()->get('Formatting');
		$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');
		$userApi = ServiceLocator::getInstance()->get('User');
		$bookApi = ServiceLocator::getInstance()->get('Book');
		$this->arResult['bookApi'] = $bookApi;
		$this->arResult['bookshelfApi'] = $bookshelfApi;
		$this->arResult['userApi'] = $userApi;
		$this->arResult['formattingApi'] = $formattingAPI;
		$this->arResult['Book'] = $formattingAPI->prepareText($this->arResult['Book']);
		$this->arResult['Authors'] = $formattingAPI->prepareText($this->arResult['Authors']);
		$this->arResult['Genre'] = $formattingAPI->prepareText($this->arResult['Genre']);
	}

	protected function fetchBookDetail()
	{
		$bookAPI = ServiceLocator::getInstance()->get('Book');
		$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');

		$bookInfo = $bookAPI->getDetailsById($this->arParams['BOOK_ID']);

		if (!$bookInfo)
		{
			$this->arResult['MESSEAGE'] = 'UP_LITLAB_BOOK_MISSING';
			return;
		}

		$this->arResult['Book'] = $bookInfo;
		$this->arResult['Book'] = $this->arResult['formattingApi']->prepareText($this->arResult['Book']);
		$this->arResult['Book']['IMG_PATH'] = CFile::GetPath($bookInfo['IMAGE_ID']);

		$authorInfo = $bookAPI->getAuthors($this->arParams['BOOK_ID']);

		$this->arResult['Authors']['NAME'] = count($authorInfo) > 1 ?
			join(', ', array_values($authorInfo))
			: array_values($authorInfo)[0];

		$genreInfo = $bookAPI->getGenres($this->arParams['BOOK_ID']);

		$this->arResult['Genre'] = $this->arResult['formattingApi']->prepareText($genreInfo);

		if ($_SESSION['USER_ID'])
		{
			$userId = $_SESSION['USER_ID'];
			$bookshelfIdWillRead = $bookshelfApi->getBookshelfIdByTitle($userId, 'Буду читать');
			$bookshelfIdRead = $bookshelfApi->getBookshelfIdByTitle($userId, 'Прочитано');

			$this->arResult['Bookshelf']['ADDED']= $bookAPI->checkBookInBookshelf($this->arResult['Book']['ID'], $bookshelfIdWillRead);
			$this->arResult['USER_ID'] = $userId;
			$this->arResult['WILL_READ_ID'] = $bookshelfIdWillRead;
			$this->arResult['READ_ID'] = $bookshelfIdRead;
			$this->arResult['Bookshelf']['ADDED_WILL_READ_ID']= $bookAPI->checkBookInBookshelf($this->arResult['Book']['ID'], $this->arResult['WILL_READ_ID']);
			$this->arResult['Bookshelf']['ADDED_READ_ID']= $bookAPI->checkBookInBookshelf($this->arResult['Book']['ID'], $this->arResult['READ_ID']);
			$this->arResult['Book']['RATING_NUMBER'] = (int)$bookAPI->getUserEstimation($userId, $bookInfo['ID']);

			$this->arResult['Bookshelves'] = array_slice($bookshelfApi->getListOfUserBookshelf($this->arResult['USER_ID'], ['public', 'private', 'moderation'], null, 0), 2);
		}
		else
		{
			$this->arResult['Book']['RATING_NUMBER'] = (int)$this->arResult['Book']['BOOK_RATING'];
		}

	}
}