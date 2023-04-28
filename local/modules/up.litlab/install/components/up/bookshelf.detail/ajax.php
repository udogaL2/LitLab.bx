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
			],
			'publish' =>
				[
					'prefilters' => [],
					'postfilters' => []
				],
			'modification' =>
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

			if ($bookshelfApi->getStatus($bookshelfId) !== 'public')
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
			$userId = (int)$_SESSION['USER_ID'];
			$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');

			$savedFlag = $bookshelfApi->isSaved($bookshelfId, $userId);

			if ($bookshelfApi->getStatus($bookshelfId) !== 'public')
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

	public function publishAction(string $action, int $bookshelfId)
	{
		if (!$_SESSION['USER_ID'] || !$action || !$bookshelfId)
		{
			return ['result' => false];
		}

		if ($action === 'publish')
		{
			$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');
			$userApi = ServiceLocator::getInstance()->get('User');

			if ($userApi->getUserRole($_SESSION['USER_ID']) !== 'admin')
			{
				return ['result' => false];
			}

			if ($bookshelfApi->getStatus($bookshelfId) !== 'moderation')
			{
				return ['result' => false];
			}

			$bookshelfApi->updateBookshelf($bookshelfId, ['STATUS' => 'public']);

			return ['result' => true, 'status' => 'public'];
		}

		return ['result' => false];
	}

	public function modificationAction(string $action, int $bookshelfId)
	{
		if (!$_SESSION['USER_ID'] || !$action || !$bookshelfId)
		{
			return ['result' => false];
		}

		if ($action === 'modification')
		{
			$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');
			$userApi = ServiceLocator::getInstance()->get('User');

			if ($userApi->getUserRole($_SESSION['USER_ID']) !== 'admin')
			{
				return ['result' => false];
			}

			if (!$bookshelfApi->getBookshelfById($bookshelfId))
			{
				return ['result' => false];
			}

			if (!in_array($bookshelfApi->getStatus($bookshelfId), ['public', 'moderation']))
			{
				return ['result' => false];
			}

			$bookshelfApi->updateBookshelf($bookshelfId, ['STATUS' => 'modification']);

			return ['result' => true, 'status' => 'modification'];
		}

		return ['result' => false];
	}
}