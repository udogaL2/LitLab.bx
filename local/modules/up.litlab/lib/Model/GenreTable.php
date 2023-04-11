<?php

namespace Up\LitLab\Model;

use Bitrix\Main\ORM\Data\DataManager, Bitrix\Main\ORM\Fields\IntegerField, Bitrix\Main\ORM\Fields\StringField, Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;

class GenreTable extends DataManager
{
	public static function getTableName()
	{
		return 'up_LitLab_genre';
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
			'TITLE' => new StringField(
				'TITLE',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateTitle'],
				]
			),

			'GENRES' => (new ManyToMany('GENRES', BookTable::class))
				->configureTableName('up_LitLab_genre_book'),

		];
	}

	public static function validateTitle()
	{
		return [
			new LengthValidator(null, 150),
		];
	}
}