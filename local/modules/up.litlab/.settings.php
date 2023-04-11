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
		],
		'readonly' => true,
	],
];