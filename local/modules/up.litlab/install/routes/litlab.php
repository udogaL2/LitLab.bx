<?php

use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Routing\Controllers\PublicPageController;
use Bitrix\Main\Routing\RoutingConfigurator;

return function(RoutingConfigurator $routes) {
	$routes->get('/404', new PublicPageController('/local/modules/up.litlab/views/404.php'));

	$routes->get('/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-list.php'));
	$routes->get('/page/{page}/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-list.php'));
	$routes->get('/bookshelves/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-list.php'));

	$routes->get('/books/', new PublicPageController('/local/modules/up.litlab/views/book-list.php'));
	$routes->get('/books/page/{page}/', new PublicPageController('/local/modules/up.litlab/views/book-list.php'));

	$routes->get('/user/{user_id}/bookshelf/{bookshelf_id}/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-detail.php'));
	$routes->get('/user/{user_id}/bookshelf/{bookshelf_id}/page/{page}/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-detail.php'));

	$routes->get('/user/{user_id}/', new PublicPageController('/local/modules/up.litlab/views/profile.php'));
	$routes->get('/user/{user_id}/page/{page}/', new PublicPageController('/local/modules/up.litlab/views/profile.php'));

	$routes->get('/user/{user_id}/saved/', new PublicPageController('/local/modules/up.litlab/views/profile-saved.php'));
	$routes->get('/user/{user_id}/saved/page/{page}/', new PublicPageController('/local/modules/up.litlab/views/profile-saved.php'));

	$routes->get('/book/{id}/', new PublicPageController('/local/modules/up.litlab/views/book-detail.php'));

	$routes->get('/create/book/', new PublicPageController('/local/modules/up.litlab/views/book-create.php'));
	$routes->post('/create/book/', new PublicPageController('/local/modules/up.litlab/views/book-create.php'));

	$routes->get('/create/bookshelf/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-create.php'));
	$routes->post('/create/bookshelf/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-create.php'));

	$routes->get('/create/bookshelf/page/{page}/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-create.php'));

	$routes->get('/edit/bookshelf/{id}/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-edit.php'));
	$routes->post('/edit/bookshelf/{id}/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-edit.php'));

	$routes->get('/register/', new PublicPageController('/local/modules/up.litlab/views/registration.php'));
	$routes->post('/register/', new PublicPageController('/local/modules/up.litlab/views/registration.php'));

	$routes->get('/auth/', new PublicPageController('/local/modules/up.litlab/views/auth.php'));
	$routes->post('/auth/', new PublicPageController('/local/modules/up.litlab/views/auth.php'));

	$routes->get('/logout/', new PublicPageController('/local/modules/up.litlab/views/auth.php'));


	// admin zone_____

	$routes->get('/bookshelves/moderation/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-moderation.php'));
	$routes->get('/bookshelves/moderation/page/{page}/', new PublicPageController('/local/modules/up.litlab/views/bookshelf-moderation.php'));

	$routes->get('/books/request/', new PublicPageController('/local/modules/up.litlab/views/book-request.php'));
	$routes->get('/books/request/page/{page}/', new PublicPageController('/local/modules/up.litlab/views/book-request.php'));

	$routes->get('/edit/book/{book_id}/', new PublicPageController('/local/modules/up.litlab/views/book-edit.php'));
	$routes->post('/edit/book/{book_id}/', new PublicPageController('/local/modules/up.litlab/views/book-edit.php'));
};