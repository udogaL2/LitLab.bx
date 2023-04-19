<?php

use Up\Litlab\API\User;
use Up\Litlab\API\UserBookshelf;
use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Context;


class LitlabBookshelfCreateComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->prepareTemplateParams();
		$this->createBookshelf();
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	protected function prepareTemplateParams()
	{
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		$userApi = new User;
		if($request === "POST")
		{
			if (!is_string($this->arParams['~TITLE']) && !is_string($this->arParams['~DESCRIPTION']))
			{
				$this->arParams['ERROR'] = "ERROR1";
			}
			if (!$this->arParams['~TITLE'] && !$this->arParams['~DESCRIPTION'])
			{
				$this->arParams['ERROR'] = "ERROR2";
			}

			$this->arResult['TITLE'] = htmlspecialcharsbx($this->arParams['~TITLE']);
			$this->arResult['DESCRIPTION'] = htmlspecialcharsbx($this->arParams['~DESCRIPTION']);
			$this->arResult['LIKES'] = 0;
			$this->arResult['DATE_CREATED'] = new \Bitrix\Main\Type\DateTime();
			$this->arResult['DATE_UPDATED'] = new \Bitrix\Main\Type\DateTime();
			$this->arResult['STATUS'] = 'private';
		}
	}

	protected function createBookshelf()
	{
		session_start();
		$BookshelfAPI = new \Up\Litlab\API\Bookshelf();
		$userApi = new User;
		if (empty($this->arParams['ERROR'])){
			$this->arResult['CREATOR_ID'] = $userApi->getUserId($_SESSION['NAME']);
			$response = $BookshelfAPI->addBookshelf($this->arResult);

			if (!isset($response))
			{
				$this->arParams['ERROR'] = "ERROR3";
				$this->includeComponentTemplate();
			}
		}

		// LocalRedirect(sprintf("/user/%s/", $userApi->getUserId($_SESSION['NAME'])));
	}
}