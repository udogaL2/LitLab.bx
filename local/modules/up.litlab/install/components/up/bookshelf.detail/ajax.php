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

		$userId = ServiceLocator::getInstance()->get('User')->getUserId($_SESSION['NAME']);

		if ($action === 'like')
		{
			$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');
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

		$userId = ServiceLocator::getInstance()->get('User')->getUserId($_SESSION['NAME']);

		if ($action === 'save')
		{
			$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');

			$savedFlag = $bookshelfApi->isSaved($bookshelfId, $userId);

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