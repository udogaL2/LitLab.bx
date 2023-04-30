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
		$this->deleteTag();
		$this->changeStatus();
		$this->addComment();
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	protected function prepareTemplateParams()
	{
		$bookshelfApi = new Bookshelf();

		$validApi = new \Up\Litlab\API\Validating();
		$formattingApi = new \Up\Litlab\API\Formatting();
		$this->arResult['formattingApi'] = $formattingApi;
		$this->arResult['bookshelfApi'] = $bookshelfApi;
		$this->arResult['BOOKSHELF_ID'] = $this->arParams['BOOKSHELF_ID'];
		$this->arResult['STATUS'] = $bookshelfApi->getBookshelfById($this->arResult['BOOKSHELF_ID'])['STATUS'];

		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if ($request === "POST")
		{
			if (!empty($this->arParams['~TAGS']))
			{
				foreach ($this->arParams['~TAGS'] as $tag)
				{
					$isValidTag = $validApi->validate($tag, 1, 150);
					if (!$isValidTag)
					{
						$this->arResult['ERROR'] = $isValidTag;
						break;
					}
				}
			}
			if (!empty($this->arParams['~TAGS-CREATED']))
			{
				foreach ($this->arParams['~TAGS-CREATED'] as $tag)
				{
					$isValidTag = $validApi->validate($tag, 1, 150);
					if ($isValidTag !== true)
					{

						$this->arResult['ERROR'] = $isValidTag;
						break;
					}
				}
			}
			$isValidTitle = $validApi->validate($this->arParams['~TITLE'], 1, 255);
			$isValidDescription = $validApi->validate($this->arParams['~DESCRIPTION'], 1, 2000);
			$isValidComment = $validApi->validate($this->arParams['~COMMENT'], 1, 400);

			if(($bookshelfApi->getBookshelfById($this->arResult['BOOKSHELF_ID'])['TITLE'] !== 'Буду читать'
				&& $bookshelfApi->getBookshelfById($this->arResult['BOOKSHELF_ID'])['TITLE'] !== 'Прочитано')){
					if (!((($isValidTitle!==true || $isValidDescription!==true))
						xor $isValidComment!==true))
					{

						if ($isValidTitle!==true){
							$this->arResult['ERROR'] = $isValidTitle;
						}
						elseif ($isValidDescription!==true){
							$this->arResult['ERROR'] = $isValidDescription;
						}
						elseif($isValidComment!==true){
							$this->arResult['ERROR'] = $isValidComment;
						}
					}
			}
			else{
				if (!($isValidDescription!==true
					xor $isValidComment!==true))
				{
					if ($isValidDescription!==true){
						$this->arResult['ERROR'] = $isValidDescription;
					}
					elseif($isValidComment!==true){
						$this->arResult['ERROR'] = $isValidComment;
					}
				}
			}

			// if (!((!$this->arParams['~TITLE']
			// 			|| !$this->arParams['~DESCRIPTION']
			// 			|| !$this->arParams['~STATUS']
			// 			|| !$this->arParams['~TAGS']
			// 			|| !$this->arParams['~TAGS-CREATED']
			// 			|| !$this->arParams['~DELETED']) xor (!$this->arParams['COMMENT']))
			// )
			// {
			// 	$this->arResult['ERROR'] = "UP_LITLAB_SAVING_ERROR";
			// }

			if (!$currentBookshelf = $bookshelfApi->getBookshelfById($this->arResult['BOOKSHELF_ID']))
			{
				$this->arResult['ERROR'] = "UP_LITLAB_BOOKSHELF_NOT_FOUND";
			}
			if (
				($currentBookshelf['TITLE'] === $this->arParams['~TITLE']
					&& $currentBookshelf['DESCRIPTION'] === $this->arParams['~DESCRIPTION']
					&& $bookshelfApi->getTags((int)$currentBookshelf['ID']) === $this->arParams['~TAGS-CREATED']
					&& !$this->arParams['~TAGS'] && $currentBookshelf['STATUS'] === $this->arParams['~STATUS'])
				&& !$this->arParams['COMMENT']
				&& !$this->arParams['DELETED']
			)
			{    // данные не были как-либо отредактированы
				$this->arResult['ERROR'] = "UP_LITLAB_DATA_NOT_BEEN_EDITED";
			}
				$this->arResult['DELETED'] = $this->arParams['DELETED'];
				$this->arResult['STATUS'] = $this->arParams['~STATUS'];
				$this->arResult['COMMENT'] = $this->arParams['~COMMENT'];
				$this->arResult['TAGS'] = $this->arParams['~TAGS'];
				$this->arResult['TAGS-CREATED'] = $this->arParams['~TAGS-CREATED'];
				$this->arResult['TITLE'] = $this->arParams['~TITLE'];
				$this->arResult['DESCRIPTION'] = $this->arParams['~DESCRIPTION'];
				$this->arResult['DATE_UPDATED'] = new \Bitrix\Main\Type\DateTime();
				$this->arResult['ITEM_ID'] = $this->arParams['ITEM_ID'];

		}
	}

	protected function changeStatus()
	{
		$bookshelfApi = new Bookshelf();
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if ($request === 'POST')
		{
			if ($this->arResult['STATUS'] === 'private')
			{
				if ($bookshelfApi->getBookshelfById($this->arResult['BOOKSHELF_ID'])['STATUS'] !== 'private')
				{
					$bookshelfApi->updateStatus($this->arResult['BOOKSHELF_ID'], 'private');
				}
			}
			if ($this->arResult['STATUS'] === 'public')
			{
				if ($bookshelfApi->getBookshelfById($this->arResult['BOOKSHELF_ID'])['STATUS'] !== 'public')
				{
					$bookshelfApi->updateStatus($this->arResult['BOOKSHELF_ID'], 'moderated');
				}
				else
				{
					$bookshelfApi->updateStatus($this->arResult['BOOKSHELF_ID'], 'moderated');
				}
			}

			if ($this->arResult['STATUS'] === null)
			{
				if ($bookshelfApi->getBookshelfById($this->arResult['BOOKSHELF_ID'])['STATUS'] === 'public')
				{
					$bookshelfApi->updateStatus($this->arResult['BOOKSHELF_ID'], 'moderated');
				}
			}
		}
	}

	protected function addTags(){

		$bookshelfApi = new Bookshelf();
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if (empty($this->arResult['ERROR']))
		{
			if ($request === 'POST')
			{
				if($this->arResult['TAGS-CREATED'])
				{
					foreach ($this->arResult['TAGS-CREATED'] as $key => $tag) //добавление измененного значения
					{
						if (in_array($tag, $bookshelfApi->getTags($this->arResult['BOOKSHELF_ID']), true))
						{
							continue;
						}
						if ($bookshelfApi->getTagByName($tag) === false)
						{
							$bookshelfApi->addTag($tag);
							$bookshelfApi->addTagsOfBookshelf(
								(int)$bookshelfApi->getTagByName($tag)['ID'],
								(int)$this->arResult['BOOKSHELF_ID']
							);
						}
						else
						{
							$bookshelfApi->addTagsOfBookshelf(
								(int)$bookshelfApi->getTagByName($tag)['ID'],
								(int)$this->arResult['BOOKSHELF_ID']
							);
						}
					}

					$tagsId = [];
					foreach ($this->arResult['TAGS-CREATED'] as $key => $tag)// удаление старого значения
					{

						if ($tag === $bookshelfApi->getTags($this->arResult['BOOKSHELF_ID'])[$key])
						{
							continue;
						}
						$tagsId[] = $bookshelfApi->getTagByName(
							$bookshelfApi->getTags($this->arResult['BOOKSHELF_ID'])[$key]
						)['ID'];
					}

					$bookshelfApi->deleteTagsOfBookshelf($tagsId, $this->arResult['BOOKSHELF_ID']);
				}

				foreach ($this->arResult['TAGS'] as $tag)
				{
					if ($bookshelfApi->getTagByName($tag) === false) //если тега не существует
					{
						$bookshelfApi->addTag($tag);
						$bookshelfApi->addTagsOfBookshelf(
							(int)$bookshelfApi->getTagByName($tag)['ID'],
							(int)$this->arResult['BOOKSHELF_ID']
						);
					}
					else
					{
						if (in_array($tag, $bookshelfApi->getTags($this->arResult['BOOKSHELF_ID'])))
						{ // если существует и такая связь есть
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
				// LocalRedirect(sprintf("/user/%s/", $bookshelfApi->getBookshelfById($this->arResult['BOOKSHELF_ID'])['CREATOR_ID']));
			}
		}
	}

	protected function deleteTag(){
		$bookshelfApi = new Bookshelf();
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if (empty($this->arResult['ERROR']))
		{
			if ($request === 'POST' && $this->arResult['DELETED'])
			{
				$bookshelfApi->deleteTagsOfBookshelf([$this->arResult['DELETED']], $this->arResult['BOOKSHELF_ID']);
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

		$books = $bookApi->getListOfBookByBookshelf(
			$this->arResult['BOOKSHELF_ID'], null);
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if (empty($this->arResult['ERROR']))
		{
			if ($request === 'POST' && $this->arResult['COMMENT'])
			{
				$bookshelfApi->addComments($this->arResult['BOOKSHELF_ID'], $this->arResult['ITEM_ID'], $this->arResult['COMMENT']);
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
			$bookshelf = $this->arResult['bookshelfApi']->getBookshelfById((int)$this->arResult['BOOKSHELF_ID']);
			if ($request === 'POST')
			{
				if ($bookshelf['TITLE'] === 'Буду читать' || $bookshelf['TITLE'] === 'Прочитано')
				{
					$response = $bookshelfApi->updateBookshelf((int)$this->arResult['BOOKSHELF_ID'], [
						'TITLE' => $bookshelf['TITLE'],
						'DESCRIPTION' => $this->arResult['DESCRIPTION'],
						'DATE_UPDATED' => $this->arResult['DATE_UPDATED']
					]);
				}
				else
				{
					$response = $bookshelfApi->updateBookshelf((int)$this->arResult['BOOKSHELF_ID'], [
						'TITLE' => $this->arResult['TITLE'],
						'DESCRIPTION' => $this->arResult['DESCRIPTION'],
						'DATE_UPDATED' => $this->arResult['DATE_UPDATED']
					]);

				}
				if (!isset($response))
				{
					$this->arResult['ERROR'] = "UP_LITLAB_SAVING_ERROR";
				}
			}
		}
	}
}
