<?php

use Bitrix\Main\DI\ServiceLocator;

class BookshelfDetailAjaxController extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return [
			'addLike' => [
				'prefilters' => [],
				'postfilters' => []
			],
			'save' =>
			[
				'prefilters' => [],
				'postfilters' => []
			]
		];
	}

	public function addLikeAction(string $action, int $bookshelfId)
	{
		if (!$_SESSION['NAME'] || !$action || !$bookshelfId)
		{
			return ['result' => false];
		}

		if ($action === 'like')
		{
			$userId = (int)$_SESSION['USER_ID'];

			if (!$userId)
			{
				return ['result' => false];
			}

			$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');

			if (!$bookshelfApi->getBookshelfById($bookshelfId))
			{
				return ['result' => false];
			}

			$likedFlag = $bookshelfApi->isLiked($bookshelfId, $userId);
			if ($likedFlag === false)
			{
				$bookshelfApi->addLike($bookshelfId, $userId);
			}
			else if ($likedFlag === true)
			{
				$bookshelfApi->deleteLike($bookshelfId, $userId);
			}
			else
			{
				return ['result' => false];
			}
			$likesCount = $bookshelfApi->getLikesCount($bookshelfId);

			return ['result' => true, 'likedFlag' => !$likedFlag, 'likesCount' => (int)$likesCount];
		}

		return ['result' => false];
	}

	public function saveAction(string $action, int $bookshelfId)
	{
		if (!$_SESSION['NAME'] || !$action || !$bookshelfId)
		{
			return ['result' => false];
		}


		if ($action === 'save')
		{
			$userId = ServiceLocator::getInstance()->get('User')->getUserId($_SESSION['NAME']);
			$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');

			$savedFlag = $bookshelfApi->isSaved($bookshelfId, $userId);

			if (!$bookshelfApi->getBookshelfById($bookshelfId))
			{
				return ['result' => false];
			}

			if ($savedFlag === false)
			{
				$bookshelfApi->saveBookshelfToUserCollection($bookshelfId, $userId);
			}
			else if ($savedFlag === true)
			{
				$bookshelfApi->deleteBookshelfToUserCollection($bookshelfId, $userId);
			}
			else
			{
				return ['result' => false];
			}

			$savesCount = $bookshelfApi->getSavesCount($bookshelfId);


			return ['result' => true, 'savedFlag' => !$savedFlag, 'savesCount' => (int)$savesCount];
		}

		return ['result' => false];
	}
}