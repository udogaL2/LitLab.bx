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
		$validApi = new \Up\Litlab\API\Validating();
		$tokenApi = new \Up\Litlab\API\Token();
		$this->arResult['TOKEN'] = $tokenApi->createToken();
		if($request === "POST")
		{
			$isValidTitle = $validApi->validate($this->arParams['~TITLE'], 1, 255);
			if ($isValidTitle!==true){
				$this->arResult['ERROR'] = $isValidTitle;
			}
			$isValidDescription = $validApi->validate($this->arParams['~DESCRIPTION'], 1, 2000);
			if ($isValidDescription!==true){
				$this->arResult['ERROR'] = $isValidDescription;
			}
			$checkToken = $tokenApi->checkToken($this->arParams['TOKEN'], $_SESSION['TOKEN']);
			if($checkToken!==true){
				$this->arResult['ERROR'] = $checkToken;
			}

			$this->arResult['TITLE'] = $this->arParams['~TITLE'];
			$this->arResult['DESCRIPTION'] = $this->arParams['~DESCRIPTION'];
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
			if (!isset($_SESSION['USER_ID'])){
				LocalRedirect('/auth/');
			}
			$this->arResult['CREATOR_ID'] = $userApi->getUserId($_SESSION['NAME']);
			if ($request === 'POST')
			{
				$response = $BookshelfAPI->addBookshelf($this->arResult);

				if (!isset($response))
				{
					$this->arResult['ERROR'] = "UP_LITLAB_SAVING_ERROR";
				}
				LocalRedirect(sprintf("/user/%s/", $this->arResult['CREATOR_ID']));
			}
		}
	}
}