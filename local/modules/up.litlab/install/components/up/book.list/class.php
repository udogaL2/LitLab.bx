<?php

use Bitrix\Main\DI\ServiceLocator;

class LitlabBookListComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->prepareTemplateParams();
		$this->fetchBookList();
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
			$this->arResult['USER']['ROLE'] = $this->arResult['UserApi']->getUserRole($_SESSION['USER_ID']);
		}
	}

	protected function prepareTemplateParams()
	{
		if ($this->arParams['STATUS'])
		{
			$this->arResult['STATUS'] = $this->arParams['STATUS'];
		}

		if($this->arParams['BOOKSHELF_ID']){
			$this->arResult['BOOKSHELF_ID'] = $this->arParams['BOOKSHELF_ID'];
		}
		$this->arResult['SEARCH'] = $this->arParams['SEARCH'] ? : '';
		$this->arResult['GENRE_ID'] = $this->arParams['GENRE_ID'] ? : null;
	}

	protected function fetchBookList()
	{

		$bookApi = ServiceLocator::getInstance()->get('Book');
		$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');
		$formattingApi = ServiceLocator::getInstance()->get('Formatting');
		$userApi = ServiceLocator::getInstance()->get('User');

		$genres = $formattingApi->prepareText($bookApi->getAllGenres());

		$this->arResult['BookApi'] = $bookApi;
		$this->arResult['BookshelfApi'] = $bookshelfApi;
		$this->arResult['FormattingApi'] = $formattingApi;
		$this->arResult['UserApi'] = $userApi;
		$this->arResult['Genres'] = $genres;
	}
}