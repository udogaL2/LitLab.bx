<?php

namespace Up\Litlab\API;
use Up\Litlab\Model\BookshelfTable;

class Bookshelf
{
	public function getListOfBookshelf(?int $limit = 3, int $offset = 0): array
	{
		return BookshelfTable::query()
						   ->setSelect(['*'])
							->setLimit($limit)
							->setOffset($offset)
						   ->fetchAll()
			;
	}

	public function getListOfUserBookshelf($userId, ?int $limit = 3, int $offset = 0): array
	{
		$result = BookshelfTable::query()
							 ->setSelect(['*'])
							 ->setFilter(array('CREATOR_ID' => $userId))
							 ->setLimit($limit)
							 ->setOffset($offset)
							 ->fetchAll()
			;
		return $result;
	}
	public function getCount(): int
	{
		return BookshelfTable::getCount();
	}

	public function getDetailsById(int $id): array|false
	{
		return BookshelfTable::getByPrimary($id)
							 ->fetch()
			;
	}
	public function addBookshelf($params){
		return BookshelfTable::add($params);
	}
}
