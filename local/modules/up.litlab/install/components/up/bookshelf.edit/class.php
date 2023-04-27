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
					$this->arResult['ERROR'] = "UP_LITLAB_TYPE_ERROR";
				}
				if (!$tag){
					$this->arResult['ERROR'] = "UP_LITLAB_EMPTY_ERROR";
				}
			}
			foreach ($this->arParams['~TAGS-CREATED'] as $tag){
				if (!is_string($tag)){
					$this->arResult['ERROR'] = "UP_LITLAB_TYPE_ERROR";
				}
				if (!$tag){
					$this->arResult['ERROR'] = "UP_LITLAB_EMPTY_ERROR";
				}
			}
			if (!is_string($this->arParams['~TITLE']) && !is_string($this->arParams['~DESCRIPTION']))
			{
				$this->arResult['ERROR'] = "UP_LITLAB_TYPE_ERROR";
			}
			if (!$this->arParams['~TITLE'] && !$this->arParams['~DESCRIPTION'] )
			{
				$this->arResult['ERROR'] = "UP_LITLAB_EMPTY_ERROR";
			}
			if (!$currentBookshelf = $bookshelfApi->getBookshelfById($this->arResult['BOOKSHELF_ID']))
			{
				$this->arResult['ERROR'] = "UP_LITLAB_BOOKSHELF_NOT_FOUND";
			}
			if (
				$currentBookshelf['TITLE'] === $this->arParams['~TITLE']
				&& $currentBookshelf['DESCRIPTION'] === $this->arParams['~DESCRIPTION']
				&& $bookshelfApi->getTags((int)$currentBookshelf['ID']) === $this->arParams['~TAGS-CREATED']
				&& !$this->arParams['~TAGS'] && !$this->arParams['COMMENT']
			)
			{
				$this->arResult['ERROR'] = "UP_LITLAB_DATA_NOT_BEEN_EDITED";
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
		if (empty($this->arResult['ERROR']))
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
		if (empty($this->arResult['ERROR']))
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

		if (empty($this->arResult['ERROR']))
		{
			if (!isset($_SESSION['NAME']))
			{
				LocalRedirect('/auth/');
			}
			if ($request === 'POST')
			{

				$response = $bookshelfApi->updateBookshelf((int)$this->arResult['BOOKSHELF_ID'], [
					'TITLE'=>$this->arResult['TITLE'],
					'DESCRIPTION'=>$this->arResult['DESCRIPTION'],
					'DATE_UPDATED'=>$this->arResult['DATE_UPDATED']]);
				if (!isset($response))
				{
					$this->arResult['ERROR'] = "UP_LITLAB_SAVING_ERROR";
					$this->includeComponentTemplate();
				}

			}
		}
	}
}
