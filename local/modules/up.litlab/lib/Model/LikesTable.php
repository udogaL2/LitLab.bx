<?php

namespace Up\LitLab\Model;

use Bitrix\Main\ORM\Data\DataManager, Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Up\Litlab\API\User;

class LikesTable extends DataManager
{
	public static function getTableName()
	{
		return 'up_LitLab_likes';
	}

	public static function getMap()
	{
		return [
			'USER_ID' => new IntegerField(
				'USER_ID',
				[
					'primary' => true,
				]
			),

			'BOOKSHELF_ID' => new IntegerField(
				'BOOKSHELF_ID',
				[
					'primary' => true,
				]
			),

			'BOOKSHELF' => new Reference(
				'BOOKSHELF', BookshelfTable::class, Join::on('this.BOOKSHELF_ID', 'ref.ID')
			),

			'USER' => new Reference(
				'USER', UserTable::class, Join::on('this.USER_ID', 'ref.ID')
			),
		];
	}
}