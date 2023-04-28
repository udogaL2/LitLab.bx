<?php

namespace Up\Litlab\Model;

use Bitrix\Main\ORM\Data\DataManager, Bitrix\Main\ORM\Fields\DatetimeField, Bitrix\Main\ORM\Fields\IntegerField, Bitrix\Main\ORM\Fields\StringField, Bitrix\Main\ORM\Fields\TextField, Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

class BookshelfTable extends DataManager
{
	public static function getTableName()
	{
		return 'up_LitLab_bookshelf';
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
			'CREATOR_ID' => new IntegerField(
				'CREATOR_ID', [
								'required' => true,
							]
			),

			'CREATOR' => new Reference('CREATOR', UserTable::class, Join::on('this.BOOK_ID', 'ref.ID')),

			'TITLE' => new StringField(
				'TITLE', [
						   'required' => true,
						   'validation' => [__CLASS__, 'validateTitle'],
					   ]
			),

			'DESCRIPTION' => new TextField(
				'DESCRIPTION', [
								 'required' => true,
							 ]
			),
			'LIKES' => new ExpressionField(
				'LIKES', '(select count(*) from up_LitLab_likes where BOOKSHELF_ID = %s)', ['ID'],
			),

			'SAVES' => new ExpressionField(
				'SAVES', '(select count(*) from up_LitLab_user_bookshelf where BOOKSHELF_ID = %s)', ['ID'],
			),

			'TLIKES' => new Reference('LIKES_TABLE', LikesTable::class, Join::on('this.ID', 'ref.BOOKSHELF_ID')),

			'DATE_CREATED' => new DatetimeField(
				'DATE_CREATED', [
								  'required' => true,
							  ]
			),
			'DATE_UPDATED' => new DatetimeField(
				'DATE_UPDATED', [
								  'required' => true,
							  ]
			),
			'STATUS' => new StringField(
				'STATUS', [
							'required' => true,
							'validation' => [__CLASS__, 'validateStatus'],
						]
			),

			'BOOK_COUNT' => new ExpressionField(
				'BOOK_COUNT',
				'(select count(*) as `BOOK_COUNT` from up_LitLab_bookshelf_book where BOOKSHELF_ID = %s)',
				['ID']
			),

			'BOOKSHELF' => new Reference(
				'BOOKSHELF', BookBookshelfTable::class, Join::on('this.ID', 'ref.BOOKSHELF_ID')
			),

			'TAGS' => (new ManyToMany('TAGS', TagTable::class))->configureTableName('up_LitLab_tag_bookshelf'),

			'USERS' => (new ManyToMany('USERS', UserTable::class))->configureTableName(
					'up_LitLab_user_bookshelf'
				),
		];
	}

	public static function validateStatus()
	{
		return [
			new LengthValidator(null, 30),
		];
	}

	public static function validateTitle()
	{
		return [
			new LengthValidator(null, 255),
		];
	}
}