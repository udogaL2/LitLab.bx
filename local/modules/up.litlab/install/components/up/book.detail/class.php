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
	}

	protected function fetchBookDetail()
	{
	}
}