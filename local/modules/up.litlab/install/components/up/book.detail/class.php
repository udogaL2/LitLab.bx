<?php

use Bitrix\Main\DI\ServiceLocator;

class LitlabBookDetailfComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->prepareTemplateParams();
		$this->fetchBookDetail();
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
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
		$this->arResult['GENRE'] = $formattingAPI->prepareText($this->arResult['GENRE']);
	}

	protected function fetchBookDetail()
	{
		$bookAPI = ServiceLocator::getInstance()->get('Book');

		$bookInfo = $bookAPI->getDetailsById($this->arParams['BOOK_ID']);

		if (!$bookInfo)
		{
			$this->arResult['MESSEAGE'] = 'UP_LITLAB_BOOK_MISSING';
		}

		$this->arResult['Book'] = $bookInfo;
		$this->arResult['Book'] = $this->arResult['formattingApi']->prepareText($this->arResult['Book']);
		$this->arResult['Book']['IMG_PATH'] = CFile::GetPath($bookInfo['IMAGE_ID']);

		$authorRawInfo = $bookAPI->getAuthors($this->arParams['BOOK_ID']);
		$authorInfo = array_map(function ($array) { return $array['NAME'];}, $authorRawInfo);

		$this->arResult['Authors']['NAME'] = count($authorInfo) > 1 ?
			join(', ', array_values($authorInfo))
			: $authorInfo[0];

		$genreInfo = $bookAPI->getGenres($this->arParams['BOOK_ID']);

		$this->arResult['Genre'] = $this->arResult['formattingApi']->prepareText($genreInfo);

		if ($_SESSION['NAME'])
		{
			$userId = ServiceLocator::getInstance()->get('User')->getUserId($_SESSION['NAME']);
			$bookshelfIdWillRead = $this->arResult['bookshelfApi']->getBookshelfIdByTitle($userId, 'Буду читать');
			$bookshelfIdRead = $this->arResult['bookshelfApi']->getBookshelfIdByTitle($userId, 'Прочитано');

			$this->arResult['Bookshelf']['ADDED']= $this->arResult['bookApi']->checkBookInBookshelf($this->arResult['Book']['ID'], $bookshelfIdWillRead);
			$this->arResult['USER_ID'] = $userId;
			$this->arResult['WILL_READ_ID'] = $bookshelfIdWillRead;
			$this->arResult['READ_ID'] = $bookshelfIdRead;
			$this->arResult['Book']['RATING_NUMBER'] = (int)$bookAPI->getUserEstimation($userId, $bookInfo['ID']);
			$this->arResult['Bookshelves'] = array_slice($this->arResult['bookshelfApi']->getListOfUserBookshelf(
				$this->arResult['USER_ID'], ['public', 'private', 'moderated'], null, 0), 2);
		}
		else
		{
			$this->arResult['Book']['RATING_NUMBER'] = (int)$this->arResult['Book']['BOOK_RATING'];
		}

	}
}