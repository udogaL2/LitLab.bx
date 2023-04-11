<?php

namespace Up\LitLab\Model;

use Bitrix\Main\ORM\Data\DataManager, Bitrix\Main\ORM\Fields\IntegerField, Bitrix\Main\ORM\Fields\StringField, Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;

class UserTable extends DataManager
{
	public static function getTableName()
	{
		return 'up_LitLab_user';
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
			'USERNAME' => new StringField(
				'USERNAME',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateUsername'],
				]
			),
			'PASSWORD' => new StringField(
				'PASSWORD',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validatePassword'],
				]
			),
			'ROLE' => new StringField(
				'ROLE',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateRole'],
				]
			),

			'BOOKSHELVES' => (new ManyToMany('BOOKSHELVES', BookshelfTable::class))
				->configureTableName('up_LitLab_user_bookshelf'),
		];
	}
	public static function validateName()
	{
		return [
			new LengthValidator(null, 255),
		];
	}
	public static function validateUsername()
	{
		return [
			new LengthValidator(null, 30),
		];
	}
	public static function validatePassword()
	{
		return [
			new LengthValidator(null, 255),
		];
	}
	public static function validateRole()
	{
		return [
			new LengthValidator(null, 50),
		];
	}
}