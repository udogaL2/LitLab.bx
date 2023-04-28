<?php

use Bitrix\Main\Context;
use Bitrix\Main\DI\ServiceLocator;
use Up\Litlab\API\User;

class LitlabRegisterComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		session_start();
		$this->prepareTemplateParams();
		$this->register();
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	protected function prepareTemplateParams()
	{
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		$userApi = new User();
		if ($request === "POST")
		{
			if (
				!is_string($this->arParams['~NAME'])
				&& !is_string($this->arParams['~PASSWORD'])
				&& !is_string($this->arParams['~USERNAME'])
			)
			{
				$this->arResult['ERROR'] = "UP_LITLAB_TYPE_ERROR";

			}
			if (!$this->arParams['~NAME'] || !$this->arParams['~PASSWORD'] || !$this->arParams['~USERNAME'])
			{
				$this->arResult['ERROR'] = "UP_LITLAB_EMPTY_ERROR";
			}
			$validForm = $userApi->validateAuthForm($this->arParams['~NAME'], $this->arParams['~PASSWORD']);
			if ($validForm != '')
			{
				$this->arResult['ERROR'] = $validForm;
			}
			$this->arResult['NAME'] = $this->arParams['~NAME'];
			$this->arResult['USERNAME'] = $this->arParams['~USERNAME'];
			$this->arResult['PASSWORD'] = hash('md5', $this->arParams['~PASSWORD']);
			$this->arResult['ROLE'] = 'user';
		}
	}

	protected function register()
	{
		$userAPI = new User();
		$userBookshelfApi = new \Up\Litlab\API\Bookshelf();
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if (empty($this->arResult['ERROR']))
		{
			if ($userAPI->checkLogin($this->arResult['NAME']) && $request === "POST")
			{
				$response = $userAPI->registerUser($this->arResult);
				$userBookshelfApi->autoAddedUserBookshelfs($userAPI->getUserId($this->arResult['NAME']));
				if (!isset($response))
				{
					$this->arResult['ERROR'] = "UP_LITLAB_SAVING_ERROR";
				}
                LocalRedirect("/auth/");
			}
			elseif ($request === "POST")
			{
				$this->arResult['ERROR'] = "UP_LITLAB_LOGIN_IS_BUSY";
			}
		}
	}
}
