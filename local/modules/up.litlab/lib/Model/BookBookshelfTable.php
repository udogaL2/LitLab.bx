<?php

namespace Up\LitLab\Model;

use Bitrix\Main\ORM\Data\DataManager, Bitrix\Main\ORM\Fields\IntegerField, Bitrix\Main\ORM\Fields\StringField, Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

class BookBookshelfTable extends DataManager
{
	public static function getTableName()
	{
		return 'up_LitLab_bookshelf_book';
	}

	public static function getMap()
	{
		return [
			'BOOK_ID' => new IntegerField(
				'BOOK_ID',
				[
					'primary' => true,
				]
			),

			'BOOK' => (new Reference('BOOK', BookTable::class, Join::on('this.BOOK_ID', 'ref.ID'))),

			'BOOKSHELF_ID' => new IntegerField(
				'BOOKSHELF_ID',
				[
					'primary' => true,
				]
			),

			'BOOKSHELF' => new Reference('BOOKSHELF', BookshelfTable::class, Join::on('this.BOOKSHELF_ID', 'ref.ID')),

			'COMMENT' => new StringField(
				'COMMENT',
				[
					'validation' => [__CLASS__, 'validateComment'],
				]
			),
		];
	}

	public static function validateComment()
	{
		return [
			new LengthValidator(null, 400),
		];
	}
}