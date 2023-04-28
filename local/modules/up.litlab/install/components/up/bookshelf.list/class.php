<?php

use Bitrix\Main\Context;
use Bitrix\Main\DI\ServiceLocator;

class LitlabBookshelfListComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->prepareTemplateParams();
		$this->prepareApis();
		$this->prepareSession();
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	protected function prepareTemplateParams()
	{
		$this->arResult['SEARCH'] = $this->arParams['SEARCH'];
		if ($this->arParams['STATUS'])
		{
			$this->arResult['STATUS'] = $this->arParams['STATUS'];
		}
	}

	protected function prepareSession()
	{
		if($_SESSION['USER_ID'])
		{
			$this->arResult['USER']['ID'] = $_SESSION['USER_ID'];
			$this->arResult['USER']['ROLE'] = $this->arResult['UserApi']->getUserRole($_SESSION['USER_ID']);
		}
	}

	protected function prepareApis()
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