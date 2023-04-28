<?php

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
		if (!$_SESSION['NAME'] || !$bookshelfId || !$bookId || !$action)
		{
			return ['result' => false];
		}
		if($action === 'delete')
		{
			$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');
			$bookshelfApi->deleteBookOfBookshelf([$bookId], $bookshelfId);
			return ['result' => true];
		}
	}

	public function deleteBookshelfAction($bookshelfId, $action)
	{
		if (!$_SESSION['NAME'] || !$bookshelfId || !$action)
		{
			return ['result' => false];
		}
		if($action === 'delete')
		{
			$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');
			$bookshelfApi->updateStatus($bookshelfId, 'deleted');
			return ['result' => true];
		}
		return ['result'=>false];
	}

	public function deleteTagAction($bookshelfId, $tagId, $action)
	{
		if (!$_SESSION['NAME'] || !$bookshelfId || !$tagId || !$action)
		{
			return ['result' => false];
		}
		if($action === 'delete')
		{
			$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');
			$bookshelfApi->deleteTagOfBookshelf((int)$tagId, (int)$bookshelfId);
			return ['result' => true, 'action'=>'delete'];

		}
		return ['result'=>false];
	}

	public function addTagAction($bookshelfId, $tag, $action)
	{
		$bookshelfApi = ServiceLocator::getInstance()->get('Bookshelf');
		var_dump($tag);
		if($action==='addTag')
		{
			if ($bookshelfApi->getTagByName($tag) === false) //если тега не существует
			{
				$bookshelfApi->addTag($tag);
				$bookshelfApi->addTagsOfBookshelf((int)$bookshelfApi->getTagByName($tag)['ID'], (int)$bookshelfId);
			}
			else
			{
				if (!in_array($tag, $bookshelfApi->getTags($bookshelfId)))
				{ // если существует и такой связи нет
					$bookshelfApi->addTagsOfBookshelf(
						(int)$bookshelfApi->getTagByName($tag)['ID'],
						(int)$bookshelfId
					);
				}
			}

			return ['result' => true, 'action' => 'addTag'];
		}
		return ['result'=>false];
	}

	public function editTagValueAction()
	{

	}
}