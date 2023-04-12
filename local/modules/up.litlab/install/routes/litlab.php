<?php

use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Routing\Controllers\PublicPageController;
use Bitrix\Main\Routing\RoutingConfigurator;

return function(RoutingConfigurator $routes) {
	$routes->get('/404', new PublicPageController('/local/modules/up.litlab/views/404.php'));

	$routes->get('/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-list.php'));
	$routes->get('/bookshelves', new PublicPageController('/local/modules/up.litlab/views/bookshelf-list.php'));
	$routes->get('/bookshelves/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-list.php'));
	$routes->get('/bookshelves/{page}/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-list.php'));

	$routes->get('/books', new PublicPageController('/local/modules/up.litlab/views/book-list.php'));
	$routes->get('/books/', new PublicPageController('/local/modules/up.litlab/views/book-list.php'));
	$routes->get('/books/page/', new PublicPageController('/local/modules/up.litlab/views/book-list.php'));
	$routes->get('/books/page/{page}/', new PublicPageController('/local/modules/up.litlab/views/book-list.php'));

	$routes->get('/user/{user_id}/bookshelf/{bookshelf_id}', new PublicPageController('/local/modules/up.litlab/views/bookshelf-detail.php'));
	$routes->get('/user/{user_id}/bookshelf/{bookshelf_id}/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-detail.php'));
	$routes->get('/user/{user_id}/bookshelf/{bookshelf_id}/page/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-detail.php'));
	$routes->get('/user/{user_id}/bookshelf/{bookshelf_id}/page/{page}/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-detail.php'));

	$routes->get('/user/{user_id}', new PublicPageController('/local/modules/up.litlab/views/profile.php'));
	$routes->get('/user/{user_id}/', new PublicPageController('/local/modules/up.litlab/views/profile.php'));
	$routes->get('/user/{user_id}/page/', new PublicPageController('/local/modules/up.litlab/views/profile.php'));
	$routes->get('/user/{user_id}/page/{page}/', new PublicPageController('/local/modules/up.litlab/views/profile.php'));

	$routes->get('/book/{id}', new PublicPageController('/local/modules/up.litlab/views/book-detail.php'));
	$routes->get('/book/{id}/', new PublicPageController('/local/modules/up.litlab/views/book-detail.php'));

	$routes->get('/create/book', new PublicPageController('/local/modules/up.litlab/views/book-create.php'));
	$routes->get('/create/book/', new PublicPageController('/local/modules/up.litlab/views/book-create.php'));

	$routes->get('/create/bookshelf', new PublicPageController('/local/modules/up.litlab/views/bookshelf-create.php'));
	$routes->get('/create/bookshelf/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-create.php'));
	$routes->get('/create/bookshelf/page/{page}', new PublicPageController('/local/modules/up.litlab/views/bookshelf-create.php'));
	$routes->get('/create/bookshelf/page/{page}/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-create.php'));
};