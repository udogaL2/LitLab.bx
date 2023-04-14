<?php

namespace Up\Litlab\API;
use Up\Litlab\Model\BookTable;

class Book
{
	public function getListOfBook(?int $limit = 3, int $offset = 0): array
	{
		return BookTable::query()
			->setSelect(['*'])
			->setLimit($limit)
			->setOffset($offset)
			->fetchAll()
			;
	}

	public function getCount(): int
	{
		return BookTable::getCount();
	}

	public function getDetailsById(int $id): array|false
	{
		return BookTable::getByPrimary($id)
			 ->fetch()
			;
	}

	public function getAuthors(int $bookId): array
	{
		return BookTable::getByPrimary($bookId, ['select' => ['NAME' => 'AUTHORS.NAME']])
			->fetchAll()
			;
	}

	public function getGenres(int $bookId): array
	{
		return BookTable::getByPrimary($bookId, ['select' => ['G_TITLE' => 'GENRES.TITLE']])
						->fetchAll()
			;
	}
}