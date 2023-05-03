<?php

namespace Up\LitLab\Model;

use Bitrix\Main\ORM\Data\DataManager, Bitrix\Main\ORM\Fields\DatetimeField, Bitrix\Main\ORM\Fields\IntegerField, Bitrix\Main\ORM\Fields\StringField, Bitrix\Main\ORM\Fields\TextField, Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Up\Tasks\Model\ImageTable;

class BookTable extends DataManager
{
	public static function getTableName()
	{
		return 'up_LitLab_book';
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

			'DESCRIPTION' => new TextField(
				'DESCRIPTION',
				[
					'required' => true,
				]
			),

			'IMAGE_ID' => new IntegerField(
				'IMAGE_ID',
				[
					'required' => true,
				]
			),

			'PUBLICATION_YEAR' => new StringField(
				'PUBLICATION_YEAR',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validatePublicationYear'],
				]
			),

			'ISBN' => new StringField(
				'ISBN',
				[
					'validation' => [__CLASS__, 'validateIsbn'],
				]
			),

			'STATUS' => new StringField(
				'STATUS',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateStatus'],
				]
			),

			'DATE_CREATED' => new DatetimeField(
				'DATE_CREATED',
				[
					'required' => true,
				]
			),

			'AUTHORS' => (new ManyToMany('AUTHORS', AuthorTable::class))
				->configureTableName('up_LitLab_book_author'),

			'GENRES' => (new ManyToMany('GENRES', GenreTable::class))
				->configureTableName('up_LitLab_genre_book'),

			'BOOK' => new Reference('BOOK', BookBookshelfTable::class, Join::on('this.ID', 'ref.BOOK_ID')),

			'BOOK_RATING' => new ExpressionField(
			'BOOK_RATING', '(select (sum(ESTIMATION) / count(*)) from up_LitLab_rating where BOOK_ID = %s)', ['ID']
			),

			'ESTIMATION_COUNT' => new ExpressionField(
				'ESTIMATION_COUNT', '(select count(*) from up_LitLab_rating where BOOK_ID = %s)', ['ID']
			),
		];
	}

	public static function validateTitle()
	{
		return [
			new LengthValidator(null, 255),
		];
	}

	public static function validatePublicationYear()
	{
		return [
			new LengthValidator(null, 4),
		];
	}

	public static function validateIsbn()
	{
		return [
			new LengthValidator(null, 20),
		];
	}

	public static function validateStatus()
	{
		return [
			new LengthValidator(null, 30),
		];
	}
}