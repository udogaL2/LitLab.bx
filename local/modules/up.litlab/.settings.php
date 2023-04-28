<?php

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
			'User' => [
				'className' => \Up\LitLab\API\User::class,
			],
			'Validating' => [
				'className' => \Up\LitLab\API\Validating::class,
			],
		],
		'readonly' => true,
	],
];