<?php

use Bitrix\Main\DI\ServiceLocator;

class LitlabBookshelDetailfComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		// $this->prepareTemplateParams();
		// $this->fetchBookshelfDetail();
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	// protected function prepareTemplateParams()
	// {
	// }
	//
	// protected function fetchBookshelfDetail()
	// {
	//
	// }
}