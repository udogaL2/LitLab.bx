<?php

use Bitrix\Main\DI\ServiceLocator;

class LitLabBookshelfComponent extends CBitrixComponent
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
	}

	protected function fetchBookshelfList()
	{
		$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');

		$this->arResult['BookshelfApi'] = $bookshelfApi;
	}
}