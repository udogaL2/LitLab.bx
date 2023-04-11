<?php

namespace Up\Tasks\Model;

use Bitrix\Main\Localization\Loc, Bitrix\Main\ORM\Data\DataManager, Bitrix\Main\ORM\Fields\DatetimeField, Bitrix\Main\ORM\Fields\IntegerField, Bitrix\Main\ORM\Fields\StringField, Bitrix\Main\ORM\Fields\TextField, Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\Type\DateTime;

class ProjectTable extends DataManager
{
	public static function getTableName()
	{
		return 'up_tasks_project';
	}

	public static function getMap(): array
	{
		return [
			new IntegerField(
				'ID', [
						'primary' => true,
						'autocomplete' => true,
					]
			),
			new StringField(
				'NAME', [
						  'required' => true,
						  'validation' => [__CLASS__, 'validateName'],
					  ]
			),
			new TextField(
				'DESCRIPTION', [
								 'required' => true,
							 ]
			),
			new DatetimeField(
				'LAST_ACTIVITY', [
								   'default_value' => function() {
									   return new DateTime();
								   },
							   ]
			),
			'TASKS_COUNT' => new ExpressionField(
				'TASKS_COUNT', '(select count(*) from up_tasks_task where PROJECT_ID = %s)', ['ID']
			),
		];
	}

	public static function validateName()
	{
		return [
			new LengthValidator(null, 255),
		];
	}
}