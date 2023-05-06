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
		$this->changeStatus();
		$this->addComment();
		// $this->prepareRedirect();
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams($arParams)
	{
		$this->arParams['~TAGS'] = $this->arParams['~TAGS'] ? : [];
		return $arParams;
	}

	protected function prepareTemplateParams()
	{
		$bookshelfApi = new Bookshelf();
		$tokenApi = new \Up\Litlab\API\Token();
		$validApi = new \Up\Litlab\API\Validating();
		$formattingApi = new \Up\Litlab\API\Formatting();
		$this->arResult['formattingApi'] = $formattingApi;
		$this->arResult['bookshelfApi'] = $bookshelfApi;
		$this->arResult['bookApi'] = new Book();

		$this->arResult['BOOKSHELF_ID'] = $this->arParams['BOOKSHELF_ID'];
		if(isset($_SESSION['USER_ID']))
		{
			$bookInfo = $bookshelfApi->getDetailsById($this->arParams['BOOKSHELF_ID'], $_SESSION['USER_ID']);
			$this->arParams['BOOKSHELF'] = $bookInfo;
			$tags = $bookshelfApi->getTags($this->arParams['BOOKSHELF_ID']);
			$this->arResult['BOOKSHELF']['TAGS'] = array_values($tags)[0] ? $tags : [];
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

				$isValidTitle = $validApi->validate($this->arParams['~TITLE'], 1, 255);
				$isValidDescription = $validApi->validate($this->arParams['~DESCRIPTION'], 1, 2000);
				$isValidComment = $validApi->validate($this->arParams['~COMMENT'], 1, 400);
				if (
					($bookshelfApi->getBookshelfById($this->arResult['BOOKSHELF_ID'])['TITLE'] !== 'Буду читать'
						&& $bookshelfApi->getBookshelfById($this->arResult['BOOKSHELF_ID'])['TITLE'] !== 'Прочитано')
				)
				{
					if (
						!((($isValidTitle !== true || $isValidDescription !== true)) xor ($isValidComment !== true
								&& !$this->arParams['BOOK-DELETED']))
					)
					{

						if ($isValidTitle !== true)
						{
							$this->arResult['ERROR'] = $isValidTitle;
						}
						elseif ($isValidDescription !== true)
						{
							$this->arResult['ERROR'] = $isValidDescription;
						}
						elseif ($isValidComment !== true)
						{
							$this->arResult['ERROR'] = $isValidComment;
						}
					}
				}
				else
				{
					if (
						!($isValidDescription !== true xor ($isValidComment !== true
								&& !$this->arParams['BOOK-DELETED']))
					)
					{
						if ($isValidDescription !== true)
						{
							$this->arResult['ERROR'] = $isValidDescription;
						}
						elseif ($isValidComment !== true)
						{
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
						// && $bookshelfApi->getTags((int)$currentBookshelf['ID']) === $this->arParams['~TAGS-CREATED']
						&& !$this->arParams['~TAGS']
						&& $currentBookshelf['STATUS'] === $this->arParams['~STATUS'])
					&& !$this->arParams['COMMENT']
					&& !$this->arParams['DELETED']
				)
				{    // данные не были как-либо отредактированы
					$this->arResult['ERROR'] = "UP_LITLAB_DATA_NOT_BEEN_EDITED";
				}
				$checkToken = $tokenApi->checkToken($this->arParams['TOKEN'], $_SESSION['TOKEN']);
				if ($checkToken !== true)
				{
					$this->arResult['ERROR'] = $checkToken;
				}
				$this->arResult['DELETED'] = $this->arParams['DELETED'];
				$this->arResult['STATUS'] = $this->arParams['~STATUS'];
				$this->arResult['COMMENT'] = $this->arParams['~COMMENT'];
				$this->arResult['TAGS'] = $this->arParams['~TAGS'];
				// $this->arResult['TAGS-CREATED'] = $this->arParams['~TAGS-CREATED'];
				$this->arResult['TITLE'] = $this->arParams['~TITLE'];
				$this->arResult['DESCRIPTION'] = $this->arParams['~DESCRIPTION'];
				$this->arResult['DATE_UPDATED'] = new \Bitrix\Main\Type\DateTime();
				$this->arResult['ITEM_ID'] = $this->arParams['ITEM_ID'];

			}
		}
	}

	protected function changeStatus()
	{
		$bookshelfApi = new Bookshelf();
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if ($request === 'POST')
		{
			if (empty($this->arResult['ERROR']))
			{
				if (!isset($_SESSION['USER_ID']))
				{
					LocalRedirect('/auth/');
				}
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
						$bookshelfApi->updateStatus($this->arResult['BOOKSHELF_ID'], 'moderation');
					}
					else
					{
						$bookshelfApi->updateStatus($this->arResult['BOOKSHELF_ID'], 'moderation');
					}
				}

				if (
					$this->arResult['STATUS'] === null
					&& !empty($this->arResult['COMMENT'])
				)//если происходит изменение комментария
				{
					if ($bookshelfApi->getBookshelfById($this->arResult['BOOKSHELF_ID'])['STATUS'] === 'public')
					{
						$bookshelfApi->updateStatus($this->arResult['BOOKSHELF_ID'], 'moderation');
					}
				}
			}
		}
	}

	protected function addTags(){

		$bookshelfApi = new Bookshelf();
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if (empty($this->arResult['ERROR']))
		{
			if (!isset($_SESSION['USER_ID']))
			{
				LocalRedirect('/auth/');
			}
			if ($request === 'POST')
			{
				$bookshelfTags = $this->arResult['BOOKSHELF']['TAGS'];
				$tags = $bookshelfApi->getAllTags();
				foreach ($this->arResult['TAGS'] as $tag)
				{
					if (!in_array($tag, $tags)) //если тега не существует
					{
						$tagId = (int)$bookshelfApi->getTagByName($tag)['ID'];
						$bookshelfApi->addTag($tag);
						$bookshelfApi->addTagsOfBookshelf(
							$tagId,
							(int)$this->arResult['BOOKSHELF_ID']
						);
						$bookshelfTags[$tagId] = $tag;
					}
					else
					{
						if ($bookshelfTags && in_array($tag, $bookshelfTags, true))
						{ // если существует и такая связь есть
							continue;
						}
						else
						{
							$tagId = (int)array_search($tag, $tags);
							if ($tagId)
							{
								$bookshelfApi->addTagsOfBookshelf(
									$tagId,
									(int)$this->arResult['BOOKSHELF_ID']
								);
							}
							$bookshelfTags[$tagId] = $tag;
						}
					}
				}
				$this->arResult['BOOKSHELF']['TAGS'] = $bookshelfTags;
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

		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if (empty($this->arResult['ERROR']))
		{
			if (!isset($_SESSION['USER_ID']))
			{
				LocalRedirect('/auth/');
			}
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
			if (!isset($_SESSION['USER_ID']))
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
	protected function prepareRedirect()
	{
		$request = Context::getCurrent()->getRequest()->getRequestMethod();

		if ($request === 'POST')
		{
			LocalRedirect("/edit/bookshelf/{$this->arResult["BOOKSHELF_ID"]}/");
		}
	}
}
