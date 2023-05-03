<?php

use Bitrix\Main\Context;
use Bitrix\Main\DI\ServiceLocator;
use Up\Litlab\API\Book;
use Up\Litlab\API\User;
use Up\Litlab\API\Validating;

class LitlabBookCreateComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->prepareTemplateParams();
		$this->createBook();
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	protected function prepareTemplateParams()
	{
		$validApi = new Validating;
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		$tokenApi = new \Up\Litlab\API\Token();
		$this->arResult['TOKEN'] = $tokenApi->createToken();
		if($request === "POST")
		{
			$isValidTitle = $validApi->validate($this->arParams['~TITLE'], 1, 255);
			if ($isValidTitle!==true){
				$this->arResult['ERROR'] = $isValidTitle;
				return;
			}
			$isValidAuthor = $validApi->validate($this->arParams['~AUTHOR'], 1, 255);
			if ($isValidAuthor!==true){
				$this->arResult['ERROR'] = $isValidAuthor;
				return;
			}

			$checkToken = $tokenApi->checkToken($this->arResult['TOKEN'], $_SESSION['TOKEN']);
			if($checkToken!==true){
				$this->arResult['ERROR'] = $checkToken;
				return;
			}

			$this->arResult['TITLE'] = ($this->arParams['~TITLE']);
			$this->arResult['DESCRIPTION'] = ($this->arParams['~AUTHOR']) . ' автор';
			$this->arResult['IMAGE_ID'] = 1;
			$this->arResult['PUBLICATION_YEAR'] = '2023';
			$this->arResult['DATE_CREATED'] = new \Bitrix\Main\Type\DateTime();
			$this->arResult['STATUS'] = 'moderation';
		}
	}

	protected function createBook()
	{
		session_start();
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		$BookAPI = new Book();

		if (empty($this->arResult['ERROR']))
		{
			if (!isset($_SESSION['USER_ID'])){
				LocalRedirect('/auth/');
			}

			if ($request === 'POST')
			{
				$response = $BookAPI->addBook($this->arResult);

				if (!isset($response))
				{
					$this->arParams['ERROR'] = "UP_LITLAB_SAVING_ERROR";
					$this->includeComponentTemplate();
					return;
				}
				LocalRedirect(sprintf("/user/%s/", $_SESSION['USER_ID']));
			}
		}
	}
}
