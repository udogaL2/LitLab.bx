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
				$this->arResult['ERROR'] = "UP_LITLAB_TYPE_ERROR";
			}
			if (!$this->arParams['~TITLE'] && !$this->arParams['~DESCRIPTION'])
			{
				$this->arResult['ERROR'] = "UP_LITLAB_EMPTY_ERROR";
			}

			$this->arResult['TITLE'] = $this->arParams['~TITLE'];
			$this->arResult['DESCRIPTION'] = $this->arParams['~DESCRIPTION'];
			$this->arResult['LIKES'] = 0;
			$this->arResult['DATE_CREATED'] = new \Bitrix\Main\Type\DateTime();
			$this->arResult['DATE_UPDATED'] = new \Bitrix\Main\Type\DateTime();
			$this->arResult['STATUS'] = 'private';
		}
	}

	protected function createBookshelf()
	{
		session_start();
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		$BookshelfAPI = new \Up\Litlab\API\Bookshelf();
		$userApi = new User;
		if (empty($this->arResult['ERROR']))
		{
			if (!isset($_SESSION['NAME'])){
				LocalRedirect('/auth/');
			}
			$this->arResult['CREATOR_ID'] = $userApi->getUserId($_SESSION['NAME']);
			if ($request === 'POST')
			{
				$response = $BookshelfAPI->addBookshelf($this->arResult);
				LocalRedirect(sprintf("/user/%s/", $this->arResult['CREATOR_ID']));
				if (!isset($response))
				{
					$this->arResult['ERROR'] = "UP_LITLAB_SAVING_ERROR";
				}
			}
		}
	}
}