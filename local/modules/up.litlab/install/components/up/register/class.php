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
		$validApi = new \Up\Litlab\API\Validating();
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
			$isValidForm = $validApi->validateRegisterForm($this->arParams['~NAME'], $this->arParams['~USERNAME'], $this->arParams['~PASSWORD']);
			if (!$isValidForm)
			{
				$this->arResult['ERROR'] = $isValidForm;
			}
			$_SESSION['NAME'] = $this->arParams['~NAME'];
			$_SESSION['USERNAME'] = $this->arParams['~USERNAME'];
			$_SESSION['PASSWORD'] = $this->arParams['~PASSWORD'];

			$this->arResult['NAME'] = $_SESSION['NAME'];
			$this->arResult['USERNAME'] = $_SESSION['USERNAME'];
			$this->arResult['PASSWORD'] = $_SESSION['PASSWORD'];
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
				$this->arResult['PASSWORD'] = hash('md5', $this->arResult['PASSWORD']);
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
				$_SESSION = [];
			}
		}
	}
}
