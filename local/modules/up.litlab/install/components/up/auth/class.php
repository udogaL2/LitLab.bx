<?php

use Bitrix\Main\Context;
use Bitrix\Main\DI\ServiceLocator;
use Up\Litlab\API\User;

class LitlabAuthComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		session_start();
		$this->prepareTemplateParams();
		if($_SERVER['REQUEST_URI'] === '/logout/'){
			$this->logout();
		}
		$this->checkAuth();
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
		if($request === "POST"){
			if (!is_string($this->arParams['~NAME']) && !is_string($this->arParams['~PASSWORD']))
			{
				$this->arResult['ERROR'] = "UP_LITLAB_TYPE_ERROR";
			}
			if (!$this->arParams['~NAME'] && !$this->arParams['~PASSWORD'])
			{
				$this->arResult['ERROR'] = "UP_LITLAB_EMPTY_ERROR";
			}
			$isValidForm = $validApi->validateAuthForm($this->arParams['~NAME'], $this->arParams['~PASSWORD']);
			if (!$isValidForm){
				$this->arResult['ERROR'] = $isValidForm;
			}
			$_SESSION['NAME'] = $this->arParams['~NAME'];
			$_SESSION['PASSWORD'] = $this->arParams['~PASSWORD'];

			$this->arResult['NAME'] = $_SESSION['NAME'];
			$this->arResult['PASSWORD'] = $_SESSION['PASSWORD'];
		}

	}
	protected function checkAuth(){
		$userApi = new User;
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if (empty($this->arResult['ERROR']))
		{
			if (
				!$userApi->checkLogin($this->arResult['NAME'])
				&& !$userApi->checkPass(hash('md5', $this->arResult['PASSWORD'])
				) && $request === "POST"
			)
			{
				$userId = $userApi->getUserId($this->arResult['NAME']);
				$_SESSION['USER'] = $this->arResult['NAME'];
				$_SESSION['USER_ID'] = $userId;
				LocalRedirect(sprintf("/user/%s/", $userId));
			}
			elseif ($request === "POST")
			{
				$this->arResult['ERROR'] = "UP_LITLAB_INPUT_INCORRECT_DATA";
				$_SESSION = [];
			}
		}
	}
	protected function logout(){
		$_SESSION = array();
		session_destroy();
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
			}
		}
		LocalRedirect('/');
	}
}
