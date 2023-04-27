<?php

use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Localization\Loc;

class BookDetailAjaxController extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return [
			'addRating' => [
				'prefilters' => [],
				'postfilters' => []
			],
		];
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

			$averageEstimation = number_format((float)$bookApi->getEstimation($bookId), 2, '.', '');

			return ['result' => true, 'estimationFlag' => !$estimationFlag, 'averageEstimation' => $averageEstimation];
		}

		return ['result' => false];
	}
}
