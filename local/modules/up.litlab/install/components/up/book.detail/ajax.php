<?php

use Bitrix\Main\DI\ServiceLocator;
use Up\Litlab\API\Bookshelf;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

class BookDetailAjaxController extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return[
			'addBookToUserBookshelf'=>[
				'prefilters' =>[],
				'postfilters'=>[]
			]
		];
	}


	public function addBookToUserBookshelfAction($bookId, $bookshelfId, $action)
	{
		if (!$_SESSION['NAME'] || !$action || !$bookshelfId || !$bookId)
		{
			return ['result' => false];
		}


		$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');
		$bookApi = ServiceLocator::getInstance()->get('Book');

			if ($action === 'add')
			{
				$addFlag = $bookApi->checkBookInBookshelf((int)$bookId, (int)$bookshelfId);
				if(!$addFlag)
				{
					$bookshelfApi->addBookToBookshelf($bookId, $bookshelfId);
				}
				else
				{
					$bookshelfApi->deleteBookOfBookshelf([$bookId], $bookshelfId);
				}
				return ['result'=>true, 'addedFlag'=>!$addFlag];
			}

		return ['result' => false];

	}

}