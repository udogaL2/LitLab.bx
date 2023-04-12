<?php

namespace Up\Litlab\API;
use Up\Litlab\Model\BookshelfTable;

class Bookshelf
{
	public function getListOfBookshelf(): array
	{
		return BookshelfTable::query()
						   ->setSelect(['*'])
						   ->fetchAll()
			;
	}

	public function getDetailsById(int $id): array|false
	{
		return BookshelfTable::getByPrimary($id)
							 ->fetch()
			;
	}
}