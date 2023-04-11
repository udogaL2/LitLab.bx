<?php

namespace Up\Litlab\API;
use Up\Litlab\Model\BookTable;

class Book
{
	public function getListOfBook(): array
	{
		return BookTable::query()
							 ->setSelect(['*'])
							 ->fetchAll()
			;
	}

	public function getDetailsById(int $id): array|false
	{
		return BookTable::getByPrimary($id)
							 ->fetch()
			;
	}
}