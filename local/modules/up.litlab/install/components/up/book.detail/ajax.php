<?php

use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Localization\Loc;
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


class BookDetailAjaxController extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return[
			'addBookToUserBookshelf'=>
			[
				'prefilters' =>[],
				'postfilters'=>[]
			],
			'addRating' =>
			[
				'prefilters' => [],
				'postfilters' => []
			],
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
			$bookshelfApi->updateBookshelf($bookshelfId, ['DATE_UPDATED'=>new \Bitrix\Main\Type\DateTime()]);
			return ['result'=>true, 'addedFlag'=>!$addFlag];
		}

		return ['result' => false];
	}


	public function addRatingAction(string $action, int $bookId, int $estimation)
	{
		if (!$_SESSION['NAME'] || !$action || !$bookId || !$estimation || $estimation > 5 || $estimation < 1)
		{
			return ['result' => false];
		}

		if ($action === 'estimation')
		{
			$userId = (int)$_SESSION['USER_ID'];

			if (!$userId)
			{
				return ['result' => false];
			}

			$bookApi = ServiceLocator::getInstance()->get('Book');

			if (!$bookApi->getDetailsById($bookId))
			{
				return ['result' => false];
			}

			$estimationFlag = $bookApi->isMadeEstimation($bookId, $userId);
			if ($estimationFlag === false)
			{
				$bookApi->addEstimation($userId, $bookId, $estimation);
			}
			else if ($estimationFlag === true)
			{
				$bookApi->deleteEstimation($userId, $bookId);
			}
			else
			{
				return ['result' => false];
			}

			$estimation = $bookApi->getEstimation($bookId);
			$averageEstimation = number_format((float)$estimation['BOOK_RATING'], 2, '.', '');

			return ['result' => true, 'estimationFlag' => !$estimationFlag, 'averageEstimation' => $averageEstimation, 'estimationCount' => $estimation['ESTIMATION_COUNT']];
		}

		return ['result' => false];
	}
}
