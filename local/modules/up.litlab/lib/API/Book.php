<?php

namespace Up\Litlab\API;
use Bitrix\Main\ORM\Query\Query;
use Up\Litlab\Model\BookTable;
use Up\LitLab\Model\GenreTable;

class Book
{
	public function getListOfBook(?int $limit = 3, int $offset = 0, string $status = 'public', string $search = '', int $genre_id = null): array
	{
		$query = BookTable::query()
			->setSelect(['ID', 'TITLE', 'DESCRIPTION', 'IMAGE_ID', 'PUBLICATION_YEAR', 'ISBN', 'STATUS', 'DATE_CREATED'])
			->where('STATUS', $status)
			->setLimit($limit)
			->setOffset($offset)
			;

		if ($search)
		{
			$query = $query
				->where(Query::filter()
							 ->logic('or')
							 ->where([
								  ['ISBN', $search],
								  ['TITLE', 'like', '%'.$search.'%']
							 ]));
		}

		if ($genre_id)
		{
			$query = $query->where('GENRES.ID', $genre_id);
		}

		return $query->fetchAll();
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

	public function getCount(string $search = '', string $status = 'public', int $genre_id = null): int
	{
		$query = BookTable::query()
							   ->whereIn('STATUS', $status)
		;

		if ($search)
		{
			$query = $query
				->where(Query::filter()
							 ->logic('or')
							 ->where([
										 ['ISBN', $search],
										 ['TITLE', 'like', '%'.$search.'%']
									 ]));
		}

		if ($genre_id)
		{
			$query = $query->where('GENRES.ID', $genre_id);
		}

		return count($query->fetchAll());
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
			->setSelect(['ID', 'TITLE', 'DESCRIPTION', 'IMAGE_ID', 'PUBLICATION_YEAR', 'ISBN', 'STATUS', 'DATE_CREATED'])
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
		$genres = BookTable::getByPrimary($bookId, ['select' => ['G_ID' => 'GENRES.ID', 'G_TITLE' => 'GENRES.TITLE']])
						->fetchAll()
			;

		$result = [];

		foreach ($genres as $genre)
		{
			$result[$genre['G_ID']] = $genre['G_TITLE'];
		}

		return $result;
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

	public function getAllGenres(): array
	{
		$query = GenreTable::query()
			->setSelect(['*'])
			->fetchAll()
		;

		$result = [];

		foreach ($query as $tag)
		{
			$result[$tag['ID']] = $tag['TITLE'];
		}

		return $result;
	}
}