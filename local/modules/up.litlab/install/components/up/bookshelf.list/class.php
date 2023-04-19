<?php

use Bitrix\Main\Context;
use Bitrix\Main\DI\ServiceLocator;

class LitlabBookshelfListComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->prepareTemplateParams();
		$this->fetchBookshelfList();
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	protected function prepareTemplateParams()
	{
		$this->arResult['SEARCH'] = $this->arParams['SEARCH'];
	}

	protected function fetchBookshelfList()
	{
		$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');
		$bookApi = ServiceLocator::getInstance()->get('Book');
		$userApi = ServiceLocator::getInstance()->get('User');
		$formattingApi = ServiceLocator::getInstance()->get('Formatting');

		$this->arResult['BookshelfApi'] = $bookshelfApi;
		$this->arResult['UserApi'] = $userApi;
		$this->arResult['BookApi'] = $bookApi;
		$this->arResult['FormattingApi'] = $formattingApi;
	}
}