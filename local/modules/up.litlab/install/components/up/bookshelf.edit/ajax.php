<?php

use Bitrix\Main\Context;
use Bitrix\Main\DI\ServiceLocator;
use Up\Litlab\API\Bookshelf;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

class BookshelfEditAjaxController extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return[
			'deleteBookshelf'=>[
				'prefilters' =>[],
				'postfilters'=>[]
			],
			'deleteBook'=>[
				'prefilters' =>[],
				'postfilters'=>[]
			],
			'deleteTag'=>[
				'prefilters' =>[],
				'postfilters'=>[]
			],
			'addTag'=>[
				'prefilters' =>[],
				'postfilters'=>[]
			],
		];
	}

	public function deleteBookAction($bookId, $bookshelfId, $action)
	{
		$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');
		if (!$_SESSION['USER_ID'] || !$bookshelfId || !$bookId || !$action)
		{
			return ['result' => false];
		}

		if($_SESSION['USER_ID'] !== $bookshelfApi->getBookshelfCreator($bookshelfId)){
			return ['result' => false];
		}

		if(($action === 'delete') && $_SESSION['USER_ID'] === $bookshelfApi->getBookshelfCreator($bookshelfId))
		{
			$bookshelfApi->deleteBookOfBookshelf([$bookId], $bookshelfId);

			return ['result' => true];
		}
	}

	public function deleteBookshelfAction($bookshelfId, $action)
	{
		if (!$_SESSION['USER_ID'] || !$bookshelfId || !$action)
		{
			return ['result' => false];
		}

		$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');

		if($_SESSION['USER_ID'] !== $bookshelfApi->getBookshelfCreator($bookshelfId)){
			return ['result' => false];
		}

		if($action === 'delete' && $_SESSION['USER_ID'] === $bookshelfApi->getBookshelfCreator($bookshelfId))
		{
			$bookshelfApi->updateStatus($bookshelfId, 'deleted');
			return ['result' => true];
		}
		return ['result'=>false];
	}

	public function deleteTagAction($bookshelfId, $tagId, $action)
	{
		if (!$_SESSION['USER_ID'] || !$bookshelfId || !$tagId || !$action)
		{
			return ['result' => false];
		}
		$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');

		if($_SESSION['USER_ID'] !== $bookshelfApi->getBookshelfCreator($bookshelfId)){
			return ['result' => false];
		}

		if($action === 'delete')
		{
			$bookshelfApi->deleteTagOfBookshelf((int)$tagId, (int)$bookshelfId);
			return ['result' => true];

		}
		return ['result'=>false];
	}

}