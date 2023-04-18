<?php

use Up\Litlab\API\User;
use Up\LitLab\API\UserBookshelf;

return [
	'services' => [
		'value' => [
			'Bookshelf' => [
				'className' => \Up\LitLab\API\Bookshelf::class,
			],
			'Book' => [
				'className' => \Up\LitLab\API\Book::class,
			],
			'Formatting' => [
				'className' => \Up\LitLab\API\Formatting::class,
			],
			'UserBookshelf' => [
				'className' => UserBookshelf::class,
			],
			'User' => [
				'className' => User::class,
			],
		],
		'readonly' => true,
	],
];