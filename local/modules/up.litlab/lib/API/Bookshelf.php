<?php

namespace Up\Litlab\API;

use Up\Litlab\Model\BookshelfTable;
use Up\LitLab\Model\UserTable;

class Bookshelf
{
	public function getListOfBookshelf(?int $limit = 3, int $offset = 0, array $status = ['public']): array
	{
		return BookshelfTable::query()
		 	->setSelect(['*'])
			->whereIn('STATUS', $status)
			->setLimit($limit)
			->setOffset($offset)
		 	->fetchAll()
			;
	}

	public function getCount(): int
	{
		return BookshelfTable::getCount();
	}

	public function getDetailsById(int $id, int $userId, string $status = 'public'): array|false
	{
		return BookshelfTable::query()
			->setSelect(['*'])
			->where('ID', $id)
			->where('CREATOR_ID', $userId)
			->where('STATUS', $status)
			->fetch()
			;
	}

	public function getCountInBookshelf(int $bookshelfId, string $status = 'public'): int
	{
		return BookshelfTable::getCount(
			[
				'BOOKSHELF.BOOKSHELF_ID' => $bookshelfId,
				'STATUS' => $status
			]
		);
	}

	public function getCountInEachBookshelf(array $bookshelfIds, array $status = ['public']): bool|array
	{
		$booksCount = BookshelfTable::query()
			->setSelect(['BS_ID' => 'BOOKSHELF.BOOKSHELF_ID', 'B_ID' => 'BOOKSHELF.BOOK_ID'])
		->where('STATUS', 'in', $status)
		->where('BOOKSHELF.BOOKSHELF_ID', 'in', $bookshelfIds)
		->fetchAll()
		;

		$result = [];

		foreach ($booksCount as $value)
		{
			$result[$value['BS_ID']][] = $value['B_ID'];
		}

		return $result;
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
			->fetchAll());
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
}