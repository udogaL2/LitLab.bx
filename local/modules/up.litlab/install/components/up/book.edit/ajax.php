<?php

use Bitrix\Main\DI\ServiceLocator;
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


class BookEditAjaxController extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return [
			'removeAuthor' => [
				'prefilters' => [],
				'postfilters' => []
			],
			'removeGenre' => [
				'prefilters' => [],
				'postfilters' => []
			],
			'removeBook' => [
				'prefilters' => [],
				'postfilters' => []
			],
		];
	}

	public function removeAuthorAction($bookId, $authorId, $action)
	{
		if (!$_SESSION['USER_ID'] || !$action || !$authorId || !$bookId)
		{
			return ['result' => false];
		}

		if(ServiceLocator::getInstance()->get('User')->getUserRole($_SESSION['USER_ID']) !== 'admin')
		{
			return ['result' => false];
		}

		$bookApi = ServiceLocator::getInstance()->get('Book');

		if ($action === 'delete')
		{
			$bookApi->deleteAuthor($bookId, $authorId);

			return ['result' => true];
		}

		return ['result' => false];
	}

	public function removeGenreAction($bookId, $genreId, $action)
	{
		if (!$_SESSION['USER_ID'] || !$action || !$genreId || !$bookId)
		{
			return ['result' => false];
		}

		if(ServiceLocator::getInstance()->get('User')->getUserRole($_SESSION['USER_ID']) !== 'admin')
		{
			return ['result' => false];
		}

		$bookApi = ServiceLocator::getInstance()->get('Book');

		if ($action === 'delete')
		{
			$bookApi->deleteGenre($bookId, $genreId);

			return ['result' => true];
		}

		return ['result' => false];
	}

	public function removeBookAction($bookId, $action)
	{
		if (!$_SESSION['USER_ID'] || !$action || !$bookId)
		{
			return ['result' => false];
		}

		if(ServiceLocator::getInstance()->get('User')->getUserRole($_SESSION['USER_ID']) !== 'admin')
		{
			return ['result' => false];
		}

		$bookApi = ServiceLocator::getInstance()->get('Book');

		if ($action === 'delete')
		{
			$bookApi->deleteBook($bookId);

			return ['result' => true];
		}

		return ['result' => false];
	}
}