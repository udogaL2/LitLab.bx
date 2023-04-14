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
	}

	protected function fetchBookList()
	{
		$bookApi = ServiceLocator::getInstance()->get('Book');
		$formattingApi = ServiceLocator::getInstance()->get('Formatting');

		$this->arResult['BookApi'] = $bookApi;
		$this->arResult['FormattingApi'] = $formattingApi;
	}
}