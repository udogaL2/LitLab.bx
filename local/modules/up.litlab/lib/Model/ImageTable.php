<?php

namespace Up\LitLab\Model;

use Bitrix\Main\ORM\Data\DataManager, Bitrix\Main\ORM\Fields\IntegerField, Bitrix\Main\ORM\Fields\StringField, Bitrix\Main\ORM\Fields\Validators\LengthValidator;

class ImageTable extends DataManager
{
	public static function getTableName()
	{
		return 'up_LitLab_image';
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
			'PATH' => new StringField(
				'PATH',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validatePath'],
				]
			),
		];
	}

	public static function validatePath()
	{
		return [
			new LengthValidator(null, 255),
		];
	}
}