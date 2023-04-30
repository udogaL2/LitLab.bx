<?php

use Bitrix\Main\Context;
use Bitrix\Main\DI\ServiceLocator;
use Up\Litlab\API\Book;
use Up\Litlab\API\User;
use Up\Litlab\API\Validating;

class LitlabBookDetailfComponent extends CBitrixComponent
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
		if($request === "POST")
		{
			$isValidTitle = $validApi->validate($this->arParams['~TITLE'], 1, 255);
			if ($isValidTitle!==true){
				$this->arResult['ERROR'] = $isValidTitle;
			}
			$isValidAuthor = $validApi->validate($this->arParams['~AUTHOR'], 1, 255);
			if ($isValidAuthor!==true){
				$this->arResult['ERROR'] = $isValidAuthor;
			}

			$this->arResult['TITLE'] = ($this->arParams['~TITLE']);
			$this->arResult['DESCRIPTION'] = ($this->arParams['~AUTHOR']) . ' автор';
			$this->arResult['IMAGE_ID'] = 1;
			$this->arResult['PUBLICATION_YEAR'] = '2023';
			$this->arResult['DATE_CREATED'] = new \Bitrix\Main\Type\DateTime();
			$this->arResult['STATUS'] = 'moderated';
		}
	}

	protected function createBook()
	{
		session_start();
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		$BookAPI = new Book();
		$userApi = new User;
		if (empty($this->arResult['ERROR']))
		{
			if (!isset($_SESSION['NAME'])){
				LocalRedirect('/auth/');
			}

			if ($request === 'POST')
			{
				$response = $BookAPI->addBook($this->arResult);

				if (!isset($response))
				{
					$this->arParams['ERROR'] = "ERROR3";
					$this->includeComponentTemplate();
				}
				LocalRedirect(sprintf("/user/%s/", $userApi->getUserId($_SESSION['NAME'])));
			}
		}
	}
}
