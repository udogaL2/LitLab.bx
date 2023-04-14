<?php

use Bitrix\Main\DI\ServiceLocator;

class LitlabBookDetailfComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->fetchBookDetail();
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
			LocalRedirect('/404');
		}

		$this->arResult['Book'] = $bookInfo;

		$authorRawInfo = $bookAPI->getAuthors($this->arParams['BOOK_ID']);
		$authorInfo = array_map(function ($array) { return $array['NAME'];}, $authorRawInfo);

		$this->arResult['Authors']['NAME'] = count($authorInfo) > 1 ?
			join(', ', array_values($authorInfo))
			: $authorInfo[0];

		$genreRawInfo = $bookAPI->getGenres($this->arParams['BOOK_ID']);
		$genreInfo = array_map(function ($array) { return $array['G_TITLE'];}, $genreRawInfo);

		$this->arResult['GENRE'] = $genreInfo;
	}
}