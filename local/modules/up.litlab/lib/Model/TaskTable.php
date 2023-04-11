<?php

namespace Up\Tasks\Model;

use Bitrix\Main\Localization\Loc, Bitrix\Main\ORM\Data\DataManager, Bitrix\Main\ORM\Fields\IntegerField, Bitrix\Main\ORM\Fields\StringField, Bitrix\Main\ORM\Fields\TextField, Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Type\DateTime;

class TaskTable extends DataManager
{
	public static function getTableName()
	{
		return 'up_tasks_task';
	}

	public static function getMap()
	{
		return [
			'ID' => new IntegerField(
				'ID', [
				'primary' => true,
				'autocomplete' => true,
			]
			),
			'PROJECT_ID' => new IntegerField(
				'PROJECT_ID', [
				'required' => true,
			]
			),

			'PROJECT' => new Reference('PROJECT', ProjectTable::class, Join::on('this.PROJECT_ID', 'ref.ID')),

			'NAME' => new StringField(
				'NAME', [
			  	'required' => true,
			  	'validation' => [__CLASS__, 'validateName'],
		  	]
			),
			'DESCRIPTION' => new TextField(
				'DESCRIPTION', [
								 'required' => true,
							 ]
			),
			'TODO' => new TextField(
				'TODO', [
						  'required' => true,
					  ]
			),
			'RESPONSIBLE' => new StringField(
				'RESPONSIBLE', [
								 'required' => true,
								 'validation' => [__CLASS__, 'validateResponsible'],
							 ]
			),
			'PRIORITY' => new StringField(
				'PRIORITY', [
							  'required' => true,
							  'validation' => [__CLASS__, 'validatePriority'],
						  ]
			),
			'STATUS' => new StringField(
				'STATUS', [
							'required' => true,
							'validation' => [__CLASS__, 'validateStatus'],
						]
			),
			new DatetimeField(
				'DEADLINE', [
							  'default_value' => function() {
								  $date = new DateTime();
								  $date->add("1 day");

								  return $date;
							  },
						  ]
			),
			new DatetimeField(
				'UPDATED', [
							 'default_value' => function() {
								 return new DateTime();
							 },
						 ]
			),
			new DatetimeField(
				'CREATED', [
							 'default_value' => function() {
								 return new DateTime();
							 },
						 ]
			),
		];
	}

	public static function validateName()
	{
		return [
			new LengthValidator(null, 255),
		];
	}

	public static function validateResponsible()
	{
		return [
			new LengthValidator(null, 255),
		];
	}

	public static function validatePriority()
	{
		return [
			new LengthValidator(null, 100),
		];
	}

	public static function validateStatus()
	{
		return [
			new LengthValidator(null, 100),
		];
	}
}