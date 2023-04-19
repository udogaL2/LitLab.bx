<?php

use Bitrix\Main\DI\ServiceLocator;

class LitlabBookListComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->prepareTemplateParams();
		$this->fetchBookList();
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	protected function prepareTemplateParams()
	{
		if ($this->arParams['BOOKSHELF_ID'])
		{
			$this->arResult['BOOKSHELF_ID'] = $this->arParams['BOOKSHELF_ID'];
		}
	}

	protected function fetchBookList()
	{
		$bookApi = ServiceLocator::getInstance()->get('Book');
		$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');
		$formattingApi = ServiceLocator::getInstance()->get('Formatting');

		$this->arResult['BookApi'] = $bookApi;
		$this->arResult['BookshelfApi'] = $bookshelfApi;
		$this->arResult['FormattingApi'] = $formattingApi;
	}
}