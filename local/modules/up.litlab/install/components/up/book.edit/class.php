<?php

use Bitrix\Main\Context;
use Bitrix\Main\DI\ServiceLocator;
use Up\Litlab\API\Book;
use Up\Litlab\API\User;

class LitlabBookDetailfComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->prepareTemplateParams();
		$this->editBook();
		$this->addGenre();
		$this->addAuthor();
		$this->prepareRedirect();
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams($arParams)
	{
		$this->arParams['~GENRE'] = $this->arParams['~GENRE'] ? : [];
		$this->arParams['~AUTHOR'] = $this->arParams['~AUTHOR'] ? : [];
		return $arParams;
	}

	protected function prepareTemplateParams()
	{
		if ($_SESSION['USER_ID'] && ServiceLocator::getInstance()->get('User')->getUserRole($_SESSION['USER_ID']) !== 'admin')
		{
			LocalRedirect('/auth/');
		}

		$request = Context::getCurrent()->getRequest()->getRequestMethod();

		if($this->arParams['BOOK_ID'])
		{
			$bookApi = ServiceLocator::getInstance()->get('Book');

			$bookInfo = $bookApi->getDetailsByIdForEdit($this->arParams['BOOK_ID'], ['public', 'moderation']);
			$this->arParams['BOOK'] = $bookInfo;

			if ($this->arParams['BOOK']['STATUS'] === 'public')
			{
				$this->arResult['BOOK'] = $this->arParams['BOOK'];
				$authors = $bookApi->getAuthors($this->arParams['BOOK_ID']);
				$this->arResult['BOOK']['AUTHORS'] = array_values($authors)[0] ? $authors : [];
				$genres = $bookApi->getGenres($this->arParams['BOOK_ID']);
				$this->arResult['BOOK']['GENRES'] = array_values($genres)[0] ? $genres : [];
				$this->arResult['BOOK']['IMG_PATH'] = CFile::GetPath($this->arResult['BOOK']['IMAGE_ID']);

			}
			elseif ($this->arParams['BOOK']['STATUS'] === 'moderation')
			{
				$this->arResult['BOOK']['ID'] = $this->arParams['BOOK']['ID'];
				$this->arResult['BOOK']['STATUS'] = $this->arParams['BOOK']['STATUS'];
				$this->arResult['BOOK']['TITLE'] = $this->arParams['BOOK']['TITLE'];
				$this->arResult['BOOK']['DESCRIPTION'] = $this->arParams['BOOK']['DESCRIPTION'];
			}
		}

		if($request === "POST")
		{
			if (!$this->arParams['~TITLE'] ||
				!$this->arParams['~DESCRIPTION'] || !$this->arParams['YEAR'] ||
				!$this->arParams['~ISBN'] || !$this->arParams['IMG'] && !$this->arParams['BOOK']['IMAGE_ID']
				|| !$this->arParams['~GENRE'] || !$this->arParams['~AUTHOR'])
			{
				$this->arResult['ERROR'] = "UP_LITLAB_EMPTY_ERROR";
				CFile::Delete($this->arParams['IMG']);
				return;
			}

			if (!is_string($this->arParams['~TITLE']) ||
				!is_string($this->arParams['~DESCRIPTION']) || !is_numeric($this->arParams['~YEAR']) ||
				!is_string($this->arParams['~ISBN']) || !is_numeric($this->arParams['IMG']) && !$this->arParams['BOOK']['IMAGE_ID'])
			{
				$this->arResult['ERROR'] = "UP_LITLAB_TYPE_ERROR";
				CFile::Delete($this->arParams['IMG']);
				return;
			}
			if ($this->arParams['IMG'])
			{
				$tempImagePath = CFile::GetPath($this->arParams['IMG']);
				if (!$tempImagePath)
				{
					$this->arResult['ERROR'] = "UP_LITLAB_TEMP_IMAGE_NOT_FOUND";
					CFile::Delete($this->arParams['IMG']);
					return;
				}

				$tempImageInfo = array_merge(CFile::MakeFileArray($tempImagePath), ["MODULE_ID" => "LitLab"]);

				$res = CFile::CheckImageFile($tempImageInfo, 2097152, 1200, 1500);

				if (strlen($res) > 0)
				{
					$this->arResult['ERROR'] = "UP_LITLAB_ERROR_FILE_TYPE";
					CFile::Delete($this->arParams['IMG']);
					return;
				}
			}

			if(strlen($this->arParams['~TITLE']) > 255 || strlen($this->arParams['~DESCRIPTION']) > 1200
				|| strlen($this->arParams['~YEAR']) !== 4 || strlen($this->arParams['~ISBN']) > 20)
			{
				$this->arResult['ERROR'] = "UP_LITLAB_MAX_LEN_EXCEEDED";
				CFile::Delete($this->arParams['IMG']);
				return;
			}

			$validApi = new \Up\Litlab\API\Validating();

			if (!empty($this->arParams['~GENRE']))
			{
				foreach ($this->arParams['~GENRE'] as $genre)
				{
					$isValidGenre = $validApi->validate($genre, 1, 150);
					if (!$isValidGenre)
					{
						$this->arResult['ERROR'] = $isValidGenre;
						CFile::Delete($this->arParams['IMG']);
						break;
					}
				}
			}

			if (!empty($this->arParams['~AUTHOR']))
			{
				foreach ($this->arParams['~AUTHOR'] as $author)
				{
					$isValidAuthor = $validApi->validate($author, 1, 150);
					if (!$isValidAuthor)
					{
						$this->arResult['ERROR'] = $isValidAuthor;
						CFile::Delete($this->arParams['IMG']);
						break;
					}
				}
			}

			if($this->arParams['BOOK_ID'])
			{
				if ($this->arParams['~TITLE'] === $this->arResult['BOOK']['TITLE'] &&
					$this->arParams['~DESCRIPTION'] === str_replace(array("\n","\r"), '', $this->arParams['BOOK']['DESCRIPTION']) &&
					$this->arParams['YEAR'] === $this->arParams['BOOK']['PUBLICATION_YEAR'] &&
					$this->arParams['ISBN'] === $this->arParams['BOOK']['ISBN'] &&
					!$this->arParams['IMG'] && $this->arParams['BOOK']['IMAGE_ID'] &&
					!array_diff($this->arParams['~GENRE'], $this->arResult['BOOK']['GENRES']) &&
					!array_diff($this->arParams['~AUTHOR'], $this->arResult['BOOK']['AUTHORS'])
				)
				{
					$this->arResult['ERROR'] = "UP_LITLAB_DATA_NOT_BEEN_EDITED";
					return;
				}
			}

			$this->arResult['BOOK']['ID'] = $this->arParams['BOOK_ID'] ? : null;
			$this->arResult['BOOK']['TITLE'] = $this->arParams['~TITLE'];
			$this->arResult['BOOK']['DESCRIPTION'] = $this->arParams['~DESCRIPTION'];
			$this->arResult['BOOK']['PUBLICATION_YEAR'] = $this->arParams['~YEAR'];
			$this->arResult['BOOK']['ISBN'] = $this->arParams['~ISBN'];
			$this->arResult['BOOK']['STATUS'] = 'public';

			$this->arResult['GENRE'] = $this->arParams['~GENRE'];
			$this->arResult['AUTHOR'] = $this->arParams['~AUTHOR'];
			$this->arResult['TEMP_IMAGE_ID'] = $this->arParams['IMG'];
			$this->arResult['TEMP_IMAGE_INFO'] = $tempImageInfo;
		}
	}

	protected function editBook()
	{
		session_start();
		$request = Context::getCurrent()->getRequest()->getRequestMethod();

		if (empty($this->arResult['ERROR']))
		{
			if (!isset($_SESSION['USER_ID'])){
				LocalRedirect('/auth/');
			}

			if ($request === 'POST')
			{
				if ($this->arParams['IMG'])
				{
					$imageId = CFile::SaveFile($this->arResult['TEMP_IMAGE_INFO'], 'img', 'img');
					$this->arResult['BOOK']['IMAGE_ID'] = $imageId;
					$this->arResult['BOOK']['IMG_PATH'] = CFile::GetPath($this->arResult['BOOK']['IMAGE_ID']);
				}

				$bookApi = ServiceLocator::getInstance()->get('Book');

				if (!$this->arParams['BOOK_ID'])
				{
					$this->arResult['BOOK']['DATE_CREATED'] = new \Bitrix\Main\Type\DateTime();

					$response = $bookApi->addBook($this->arResult['BOOK']);
					if (!isset($response))
					{
						$this->arResult['ERROR'] = "UP_LITLAB_SAVING_ERROR";
						return;
					}

					$this->arResult['BOOK']['ID'] = $response->getId();
				}
				else
				{
					$updateData = [];
					$updateData['TITLE'] = $this->arResult['BOOK']['TITLE'];
					$updateData['DESCRIPTION'] = $this->arResult['BOOK']['DESCRIPTION'];
					$updateData['IMAGE_ID'] = $this->arResult['BOOK']['IMAGE_ID'];
					$updateData['PUBLICATION_YEAR'] = $this->arResult['BOOK']['PUBLICATION_YEAR'];
					$updateData['ISBN'] = $this->arResult['BOOK']['ISBN'];
					$updateData['STATUS'] = 'public';


					$bookApi->updateBook($this->arResult['BOOK']['ID'], $updateData);
				}
			}
		}
	}

	protected function addGenre(){
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if (empty($this->arResult['ERROR']))
		{
			if (!isset($_SESSION['USER_ID'])){
				LocalRedirect('/auth/');
			}

			if ($request === 'POST')
			{
				$bookApi = ServiceLocator::getInstance()->get('Book');
				$allGenre = $bookApi->getAllGenres();
				$bookGenre = $this->arResult['BOOK']['GENRES'];
				foreach ($this->arResult['GENRE'] as $genre)
				{
					if (!in_array($genre, $allGenre)) //если жанра не существует
					{
						$genreId = $bookApi->addGenre($genre)->getId();
						$bookApi->addGenreOfBook(
							(int)$genreId,
							(int)$this->arResult['BOOK']['ID']
						);
						$bookGenre[$genreId] = $genre;
					}
					else
					{
						if ($bookGenre && in_array($genre, $bookGenre))
						{ // если существует и такая связь есть
							continue;
						}
						else
						{
							$genreId = (int)array_search($genre, $allGenre);
							$bookApi->addGenreOfBook(
								$genreId,
								(int)$this->arResult['BOOK']['ID']
							);
							$bookGenre[$genreId] = $genre;
						}
					}
				}
				$this->arResult['BOOK']['GENRES'] = $bookGenre;
			}
		}
	}

	protected function addAuthor(){
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if (empty($this->arResult['ERROR']))
		{
			if ($request === 'POST')
			{
				$bookApi = ServiceLocator::getInstance()->get('Book');
				$allAuthors = $bookApi->getAllAuthors();
				$bookAuthors = $this->arResult['BOOK']['AUTHORS'];
				foreach ($this->arResult['AUTHOR'] as $author)
				{
					if (!in_array($author, $allAuthors)) //если автора не существует
					{
						$authorId = $bookApi->addAuthor($author)->getId();
						$bookApi->addAuthorOfBook(
							(int)$authorId,
							(int)$this->arResult['BOOK']['ID']
						);
						$bookAuthors[$authorId] = $author;
					}
					else
					{
						if ($bookAuthors && in_array($author, $bookAuthors))
						{ // если существует и такая связь есть
							continue;
						}
						else
						{
							$authorId = (int)array_search($author, $allAuthors);
							$bookApi->addAuthorOfBook(
								$authorId,
								(int)$this->arResult['BOOK']['ID']
							);
							$bookAuthors[$authorId] = $author;
						}
					}
				}
				$this->arResult['BOOK']['AUTHORS'] = $bookAuthors;

			}
		}
	}

	protected function prepareRedirect()
	{
		$request = Context::getCurrent()->getRequest()->getRequestMethod();

		if (empty($this->arResult['ERROR']))
		{
			if ($request === 'POST' && !$this->arParams['BOOK_ID'])
			{
				LocalRedirect("/book/{$this->arResult["BOOK"]["ID"]}/");
			}
		}
	}
}
