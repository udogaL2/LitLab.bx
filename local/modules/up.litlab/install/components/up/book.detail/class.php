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
			$this->arResult['MESSEAGE'] = 'UP_LITLAB_BOOK_MISSING';
		}

		$this->arResult['Book'] = $bookInfo;
		$this->arResult['Book']['IMG_PATH'] = CFile::GetPath($bookInfo['IMAGE_ID']);

		$authorRawInfo = $bookAPI->getAuthors($this->arParams['BOOK_ID']);
		$authorInfo = array_map(function ($array) { return $array['NAME'];}, $authorRawInfo);

		$this->arResult['Authors']['NAME'] = count($authorInfo) > 1 ?
			join(', ', array_values($authorInfo))
			: $authorInfo[0];

		$genreInfo = $bookAPI->getGenres($this->arParams['BOOK_ID']);

		$this->arResult['Genre'] = $genreInfo;
	}
}