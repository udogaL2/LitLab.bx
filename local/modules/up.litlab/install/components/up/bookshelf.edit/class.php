<?php

use Bitrix\Main\Context;
use Bitrix\Main\DI\ServiceLocator;
use Up\Litlab\API\Book;
use Up\Litlab\API\Bookshelf;
use Up\Litlab\API\User;

class LitlabBookshelfEditComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->prepareTemplateParams();
		$this->editBookshelf();
		$this->addTags();
		$this->addComment();
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	protected function prepareTemplateParams()
	{
		$this->arResult['BOOKSHELF_ID'] = htmlspecialcharsbx($this->arParams['BOOKSHELF_ID']);
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		$bookshelfApi = new Bookshelf();
		if($request === "POST")
		{
			foreach ($this->arParams['~TAGS'] as $tag){
				if (!is_string($tag)){
					$this->arParams['ERROR'] = "ERROR1";
				}
				if (!$tag){
					$this->arParams['ERROR'] = "ERROR2";
				}
			}
			foreach ($this->arParams['~TAGS-CREATED'] as $tag){
				if (!is_string($tag)){
					$this->arParams['ERROR'] = "ERROR1";
				}
				if (!$tag){
					$this->arParams['ERROR'] = "ERROR2";
				}
			}
			if (!is_string($this->arParams['~TITLE']) && !is_string($this->arParams['~DESCRIPTION']))
			{
				$this->arParams['ERROR'] = "ERROR1";
			}
			if (!$this->arParams['~TITLE'] && !$this->arParams['~DESCRIPTION'] )
			{
				$this->arParams['ERROR'] = "ERROR2";
			}
			if (!$currentBookshelf = $bookshelfApi->getBookshelfById($this->arResult['BOOKSHELF_ID']))
			{    // такого проекта не существует
				$this->arParams['ERROR'] = "ERROR5";

			}
			if (
				$currentBookshelf['TITLE'] === $this->arParams['~TITLE']
				&& $currentBookshelf['DESCRIPTION'] === $this->arParams['~DESCRIPTION']
				&& $bookshelfApi->getTags((int)$currentBookshelf['ID']) === $this->arParams['~TAGS-CREATED']
				&& !$this->arParams['~TAGS'] && !$this->arParams['COMMENT']
			)
			{    // данные не были как-либо отредактированы
				$this->arParams['ERROR'] = "ERROR10";

			}

			$this->arResult['COMMENT'] = $this->arParams['~COMMENT'];
			$this->arResult['TAGS'] = $this->arParams['~TAGS'];
			$this->arResult['TAGS-CREATED'] = $this->arParams['~TAGS-CREATED'];
			$this->arResult['TITLE'] = htmlspecialcharsbx($this->arParams['~TITLE']);
			$this->arResult['DESCRIPTION'] = htmlspecialcharsbx($this->arParams['~DESCRIPTION']);
			$this->arResult['DATE_UPDATED'] = new \Bitrix\Main\Type\DateTime();
		}
	}



	protected function addTags(){

		$bookshelfApi = new Bookshelf();
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if (empty($this->arParams['ERROR']))
		{
			if ($request === 'POST')
			{
				foreach ($this->arResult['TAGS'] as $tag)
				{
					if ($bookshelfApi->getTagByName($tag) === false) //если тега не существует
					{
						$bookshelfApi->addTag($tag);
						$bookshelfApi->addTagsOfBookshelf((int)$bookshelfApi->getTagByName($tag)['ID'], (int)$this->arResult['BOOKSHELF_ID']);
					}
					else{
						if(in_array($tag, $bookshelfApi->getTags($this->arResult['BOOKSHELF_ID']))){ // если существует и такая связь есть
							continue;
						}
						else
						{
							$bookshelfApi->addTagsOfBookshelf(
								(int)$bookshelfApi->getTagByName($tag)['ID'],
								(int)$this->arResult['BOOKSHELF_ID']
							);
						}
					}
				}
				foreach($this->arResult['TAGS-CREATED'] as $key=>$tag)
				{
					if (in_array($tag, $bookshelfApi->getTags($this->arResult['BOOKSHELF_ID']), true))
					{
						continue;
					}
					$bookshelfApi->addTag($tag);
					$bookshelfApi->addTagsOfBookshelf(
						(int)$bookshelfApi->getTagByName($tag)['ID'],
						(int)$this->arResult['BOOKSHELF_ID']
					);
				}
				$tagsId = [];
				foreach($this->arResult['TAGS-CREATED'] as $key=>$tag)
				{
					if ($tag === $bookshelfApi->getTags($this->arResult['BOOKSHELF_ID'])[$key])
					{
						continue;
					}
					$tagsId[] = $bookshelfApi->getTagByName($bookshelfApi->getTags($this->arResult['BOOKSHELF_ID'])[$key])['ID'];
				}

				$bookshelfApi->deleteTagsOfBookshelf($tagsId, $this->arResult['BOOKSHELF_ID']);

				// LocalRedirect(sprintf("/user/%s/", $bookshelfApi->getBookshelfById($this->arResult['BOOKSHELF_ID'])['CREATOR_ID']));
			}
		}
	}

	protected function addComment()
	{
		$bookshelfApi = new Bookshelf();
		$bookApi = new Book();
		$formattingApi = new \Up\Litlab\API\Formatting();

		$this->arResult['bookshelfApi'] = $bookshelfApi;
		$this->arResult['bookApi'] = $bookApi;
		$this->arResult['formattingApi'] = $formattingApi;
		$books = $bookApi->getListOfBookByBookshelf($this->arResult['BOOKSHELF_ID'], null);
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if (empty($this->arParams['ERROR']))
		{
			if ($request === 'POST')
			{
				foreach ($this->arResult['COMMENT'] as $key=>$comment){
					if ($comment === $bookshelfApi->getComments($this->arResult['BOOKSHELF_ID'])[$key])
					{
						continue;
					}
					$bookshelfApi->addComments($this->arResult['BOOKSHELF_ID'], $books[$key]['ID'], $comment);
				}
			}
		}
	}

	protected function editBookshelf()
	{
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		$bookshelfApi = new Bookshelf();
		$bookApi = new Book();
		$formattingApi = new \Up\Litlab\API\Formatting();

		$this->arResult['bookshelfApi'] = $bookshelfApi;
		$this->arResult['bookApi'] = $bookApi;
		$this->arResult['formattingApi'] = $formattingApi;

		if (empty($this->arParams['ERROR']))
		{
			if (!isset($_SESSION['NAME']))
			{
				LocalRedirect('/auth/');
			}
			if ($request === 'POST')
			{

				$response = $bookshelfApi->updateBookshelf((int)$this->arResult['BOOKSHELF_ID'], [
					$this->arResult['TITLE'],
					$this->arResult['DESCRIPTION'],
					$this->arResult['DATE_UPDATED']]);
				if (!isset($response))
				{
					$this->arParams['ERROR'] = "ERROR3";
					$this->includeComponentTemplate();
				}

			}
		}
	}
}
