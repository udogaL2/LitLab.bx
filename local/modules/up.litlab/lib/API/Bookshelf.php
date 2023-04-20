<?php

namespace Up\Litlab\API;

use Bitrix\Main\Filter\Filter;
use Up\Litlab\Model\BookshelfTable;
use Up\LitLab\Model\UserTable;

class Bookshelf
{
	public function getListOfBookshelf(?int $limit = 3, int $offset = 0, array $status = ['public'], string $search = null, bool $notEmpty = false): array
	{
		$query = BookshelfTable::query()
		 	->setSelect(['ID', 'CREATOR_ID', 'TITLE', 'DESCRIPTION', 'LIKES', 'DATE_CREATED', 'DATE_UPDATED', 'STATUS', 'BOOK_COUNT'])
			->whereIn('STATUS', $status)
			->setLimit($limit)
			->setOffset($offset)
			;

		if ($search)
		{
			$query = $query->whereLike('TITLE', '%' . $search . '%');
		}

		if ($notEmpty)
		{
			$query = $query->where('BOOK_COUNT', '>', 0);
		}

		return $query->fetchAll();
	}
	public function getListOfUserBookshelf($userId, ?int $limit = 3, int $offset = 0, array $status = ['public', 'private']): array
	{
		return BookshelfTable::query()
							 ->setSelect(['*'])
							 ->setFilter(array(['CREATOR_ID'=>$userId]))
							 ->wherein('STATUS', $status)
							 ->setLimit($limit)
							 ->setOffset($offset)
							 ->fetchAll()
			;
	}

	public function getCount(string $search = null, array $status = ['public'], bool $notEmpty = false): int
	{
		$query = BookshelfTable::query()
			->whereIn('STATUS', $status)
			;

		if ($search)
		{
			$query = $query->whereLike('TITLE', '%' . $search . '%');
		}

		if ($notEmpty)
		{
			$query = $query->where('BOOK_COUNT', '>', 0);
		}

		return count($query->fetchAll());
	}

	public function getUserBookshelfCount($userId): int
	{
		return BookshelfTable::getCount(['CREATOR_ID'=> $userId]);
	}

	public function getDetailsById(int $id, int $userId, array $status = ['public', 'private']): array|false
	{
		return BookshelfTable::query()
							 ->setSelect(['ID', 'CREATOR_ID', 'TITLE', 'DESCRIPTION', 'LIKES', 'DATE_CREATED', 'DATE_UPDATED', 'STATUS', 'BOOK_COUNT'])
							 ->where('ID', $id)
							 ->where('CREATOR_ID', $userId)
							 ->where('STATUS', 'in', $status)
							 ->fetch()
			;
	}

	public function getTagsInEachBookshelf(array $bookshelfIds): bool|array
	{
		$bookshelvesTag = BookshelfTable::query()
			->setSelect(['BS_ID' => 'ID', 'T_TITLE' => 'TAGS.TITLE'])
			->where('ID', 'in', $bookshelfIds)
			->setLimit(4)
			->fetchAll()
		;

		$result = [];

		foreach ($bookshelvesTag as $value)
		{
			$result[$value['BS_ID']][] = $value['T_TITLE'];
		}

		return $result;
	}

	public function getTags(int $bookshelfId): array
	{
		$tags = BookshelfTable::getByPrimary($bookshelfId, ['select' => ['T_TITLE' => 'TAGS.TITLE']])
						->fetchAll()
			;

		$result = [];

		foreach ($tags as $tag){
			$result[] = $tag['T_TITLE'];
		}

		return $result;
	}

	public function getComments(int $bookshelfId, string $status = 'public'): array|false
	{
		$comments = BookshelfTable::query()
			->setSelect(['B_COMMENT' => 'BOOKSHELF.COMMENT',
						 'B_BOOK_ID' => 'BOOKSHELF.BOOK_ID'])
			->where('ID', $bookshelfId)
			->where('STATUS', $status)
			->fetchAll()
		;

		$result = [];

		foreach ($comments as $comment)
		{
			$result[$comment['B_BOOK_ID']] = $comment['B_COMMENT'];
		}

		return $result;
	}

	public function getCountOfSavedBookshelves(int $bookshelfId)
	{
		return count(BookshelfTable::query()
			->setSelect(['USER_BOOKSHELVES'])
			->where('ID', $bookshelfId)
			->fetchAll())
			;
	}

	public function getCountOfSavedBookshelvesForEach(array $bookshelfId)
	{
		$counts = BookshelfTable::query()
		   ->setSelect(['ID', 'U_ID' => 'USER_BOOKSHELVES.ID'])
		   ->where('ID', 'in', $bookshelfId)
		   ->fetchAll()
			;

		$result = [];

		foreach ($counts as $count)
		{
			$result[$count['ID']][] = $count['U_ID'];
		}

		return $result;
	}

	public function addBookshelf(array $params){
		return BookshelfTable::add($params);
	}

	public function autoAddedUserBookshelfs(int $userId){
		BookshelfTable::add([
								'CREATOR_ID'=>$userId,
								'TITLE' => 'Буду читать',
								'DESCRIPTION' => 'Полка, в которую вы можете добавить понравившиеся вам книги.',
								'LIKES' => 0,
								'DATE_CREATED' => new \Bitrix\Main\Type\DateTime(),
								'DATE_UPDATED' => new \Bitrix\Main\Type\DateTime(),
								'STATUS' => 'private'
							]);
		BookshelfTable::add([
								'CREATOR_ID'=>$userId,
								'TITLE' => 'Прочитано',
								'DESCRIPTION' => 'Полка, в которую вы можете добавить книги, которые уже прочитали.',
								'LIKES' => 0,
								'DATE_CREATED' => new \Bitrix\Main\Type\DateTime(),
								'DATE_UPDATED' => new \Bitrix\Main\Type\DateTime(),
								'STATUS' => 'private'
							]);
	}
}