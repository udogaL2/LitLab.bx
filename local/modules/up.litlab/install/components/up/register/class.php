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
		$userApi = new User;
		if($request === "POST")
		{
			if (
				!is_string($this->arParams['~NAME']) && !is_string($this->arParams['~PASSWORD'])
				&& !is_string(
					$this->arParams['~USERNAME']
				)
			)
			{
				$this->arParams['ERROR'] = "ERROR1";

			}
			if (!$this->arParams['~NAME'] && !$this->arParams['~PASSWORD'] && !$this->arParams['~USERNAME'])
			{
				$this->arParams['ERROR'] = "ERROR2";

			}
			$validForm = $userApi->validateAuthForm($this->arParams['~NAME'], $this->arParams['~PASSWORD']);
			if ($validForm != ''){
				$this->arParams['ERROR'] = $validForm;
			}
			$_SESSION['NAME'] = htmlspecialcharsbx($this->arParams['~NAME']);
			$_SESSION['USERNAME'] = htmlspecialcharsbx($this->arParams['~USERNAME']);
			$_SESSION['PASSWORD'] = htmlspecialcharsbx($this->arParams['~PASSWORD']);


			$this->arResult['NAME'] = $_SESSION['NAME'];
			$this->arResult['USERNAME'] = $_SESSION['USERNAME'];
			$this->arResult['PASSWORD'] = hash('md5', $_SESSION['PASSWORD']);
			$this->arResult['ROLE'] = 'user';
		}
	}
	protected function register(){
		$userAPI = new User;
		$userBookshelfApi = new \Up\Litlab\API\Bookshelf();
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if (empty($this->arParams['ERROR'])){
			if ($userAPI->checkLogin($this->arResult['NAME']) && $request === "POST")
			{

				$response = $userAPI->registerUser($this->arResult);
				$userBookshelfApi->autoAddedUserBookshelfs($userAPI->getUserId($this->arResult['NAME']));
				if (!isset($response))
					{
						$this->arParams['ERROR'] = "ERROR3";
						$this->includeComponentTemplate();
					}
				LocalRedirect("/auth/");
			}
			elseif($request==="POST"){
					$this->arParams['ERROR'] = "ERROR8";
					$_SESSION = [];
				}
			}
	}
}
