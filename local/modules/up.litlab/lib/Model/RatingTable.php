<?php

namespace Up\LitLab\Model;

use Bitrix\Main\ORM\Data\DataManager, Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

class RatingTable extends DataManager
{
	public static function getTableName()
	{
		return 'up_LitLab_rating';
	}

	public static function getMap()
	{
		return [
			'BOOK_ID' => new IntegerField(
				'BOOK_ID',
				[
					'required' => true,
				]
			),

			'USER_ID' => new IntegerField(
				'USER_ID',
				[
					'required' => true,
				]
			),

			'USER' => new Reference('USER', UserTable::class, Join::on('this.USER_ID', 'ref.ID')),

			'BOOK_RATING' => new Reference('BOOK_RATING', BookTable::class, Join::on('this.BOOK_ID', 'ref.ID')),

			'ESTIMATION' => new IntegerField(
				'ESTIMATION',
				[
					'required' => true,
				]
			),
		];
	}
}