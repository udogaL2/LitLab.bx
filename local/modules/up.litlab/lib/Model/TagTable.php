<?php

namespace Up\LitLab\Model;

use Bitrix\Main\ORM\Data\DataManager, Bitrix\Main\ORM\Fields\IntegerField, Bitrix\Main\ORM\Fields\StringField, Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;

class TagTable extends DataManager
{
	public static function getTableName()
	{
		return 'up_LitLab_tag';
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

			'TAGS' => (new ManyToMany('TAGS', BookshelfTable::class))
				->configureTableName('up_LitLab_tag_bookshelf'),
		];
	}

	public static function validateTitle()
	{
		return [
			new LengthValidator(null, 150),
		];
	}
}