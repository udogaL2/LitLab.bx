<?php

namespace Up\Litlab\API;
use Up\Litlab\Model\BookTable;

class Book
{
	public function getListOfBook(?int $limit = 3, int $offset = 0, string $status = 'public'): array
	{
		return BookTable::query()
			->setSelect(['*'])
			->where('STATUS', $status)
			->setLimit($limit)
			->setOffset($offset)
			->fetchAll()
			;
	}

	public function getListOfBookByBookshelf(int $bookshelfId, ?int $limit = 3, int $offset = 0, string $status = 'public'): array
	{
		return BookTable::query()
			->setSelect(['*'])
			->where('BOOK.BOOKSHELF_ID', $bookshelfId)
			->where('STATUS', $status)
			->setLimit($limit)
			->setOffset($offset)
			->fetchAll()
		;
	}

	public function getCount(string $status = 'public'): int
	{
		return BookTable::getCount(['STATUS' => $status]);
	}

	public function getCountInBookshelf(int $bookshelfId, string $status = 'public'): int
	{
		return BookTable::getCount(
			[
				'BOOK.BOOKSHELF_ID' => $bookshelfId,
				'STATUS' => $status
			]
		);
	}

	public function getDetailsById(int $id, string $status = 'public'): array|false
	{
		return BookTable::query()
			->setSelect(['*'])
			->where('ID', $id)
			->where('STATUS', $status)
			->fetch()
			;
	}

	public function getAuthors(int $bookId): array
	{
		return BookTable::getByPrimary($bookId, ['select' => ['NAME' => 'AUTHORS.NAME']])
			->fetchAll()
			;
	}

	public function getAuthorForEachBook(array $bookIds): array
	{
		$query = BookTable::query()
			->setSelect(['ID', 'NAME' => 'AUTHORS.NAME'])
			->whereIn('ID', $bookIds)
			->fetchAll()
			;

		$result = [];

		foreach ($query as $item)
		{
			if (!$result[$item['ID']])
			{
				$result[$item['ID']] = $item['NAME'];
			}
		}

		return $result;
	}

	public function getGenres(int $bookId): array
	{
		return BookTable::getByPrimary($bookId, ['select' => ['G_TITLE' => 'GENRES.TITLE']])
						->fetchAll()
			;
	}
	public function getImage($bookshelfId, $limit = 1){
		$result = BookTable::query()
			->setSelect(['BS_ID' => 'BOOK.BOOKSHELF_ID', 'IMAGE_ID'])
			->setFilter(['BOOK.BOOKSHELF_ID' => $bookshelfId])
			->setLimit($limit)
			->fetchAll();

		return $result[0]['IMAGE_ID'];

	}

	public function getImages(array $bookshelfId, int $limit = 3)
	{
		$images = BookTable::query()
			->setSelect(['BS_ID' => 'BOOK.BOOKSHELF_ID', 'IMAGE_ID'])
			->where('BOOK.BOOKSHELF_ID', 'in', $bookshelfId)
			->setLimit($limit)
			->fetchAll()
			;

		$result = [];

		foreach ($images as $image)
		{
			$result[$image['BS_ID']][] = $image['IMAGE_ID'];
		}

		return $result;
	}
}