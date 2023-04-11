<?php

namespace Up\LitLab\Model;

use Bitrix\Main\ORM\Data\DataManager, Bitrix\Main\ORM\Fields\IntegerField, Bitrix\Main\ORM\Fields\StringField, Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;

class AuthorTable extends DataManager
{
	public static function getTableName()
	{
		return 'up_LitLab_author';
	}

	public static function getMap()
	{
		return [
			'ID' => new IntegerField(
				'ID',
				[
					'primary' => true,
					'autocomplete' => true,
				]
			),
			'NAME' => new StringField(
				'NAME',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateName'],
				]
			),

			'AUTHORS' => (new ManyToMany('AUTHORS', BookTable::class))
				->configureTableName('up_LitLab_book_author'),
		];
	}

	public static function validateName(): array
	{
		return [
			new LengthValidator(null, 255),
		];
	}
}