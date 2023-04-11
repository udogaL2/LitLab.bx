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
}