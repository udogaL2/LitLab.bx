<?php

use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;

function __litlabMigrate(int $nextVersion, callable $callback): void
{
	global $DB;
	$moduleId = 'up.litlab';

	if (!ModuleManager::isModuleInstalled($moduleId))
	{
		return;
	}

	$currentVersion = intval(Option::get($moduleId, '~database_schema_version', 0));

	if ($currentVersion < $nextVersion)
	{
		include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/update_class.php');
		$updater = new \CUpdater();
		$updater->Init('', 'mysql', '', '', $moduleId, 'DB');

		$callback($updater, $DB, 'mysql');
		Option::set($moduleId, '~database_schema_version', $nextVersion);
	}
}

__litlabMigrate(1, function($updater, $DB) {
	if ($updater->CanUpdateDatabase() && !$updater->TableExists('up_LitLab_book'))
	{
		$DB->query(
			'CREATE TABLE IF NOT EXISTS up_LitLab_book
			(
				ID               INT AUTO_INCREMENT NOT NULL,
				TITLE            VARCHAR(255)       NOT NULL,
				DESCRIPTION      TEXT               NOT NULL,
				IMAGE_ID         INT                NOT NULL,
				PUBLICATION_YEAR VARCHAR(4)         NOT NULL,
				ISBN             VARCHAR(13)        NOT NULL,
				STATUS           VARCHAR(30)        NOT NULL,
				DATE_CREATED     DATETIME           NOT NULL,
				PRIMARY KEY (ID)
			);'
		);
	}
});

__litlabMigrate(2, function($updater, $DB) {
	if ($updater->CanUpdateDatabase() && !$updater->TableExists('up_LitLab_genre'))
	{
		$DB->query(
			'CREATE TABLE IF NOT EXISTS up_LitLab_genre
			(
				ID    INT AUTO_INCREMENT NOT NULL,
				TITLE VARCHAR(150)       NOT NULL,
				PRIMARY KEY (ID)
			);'
		);
	}
});

__litlabMigrate(3, function($updater, $DB) {
	if ($updater->CanUpdateDatabase() && !$updater->TableExists('up_LitLab_genre_book'))
	{
		$DB->query(
			'CREATE TABLE IF NOT EXISTS up_LitLab_genre_book
			(
				GENRE_ID INT NOT NULL,
				BOOK_ID  INT NOT NULL,
				PRIMARY KEY (GENRE_ID, BOOK_ID)
			);'
		);
	}
});

__litlabMigrate(4, function($updater, $DB) {
	if ($updater->CanUpdateDatabase() && !$updater->TableExists('up_LitLab_rating'))
	{
		$DB->query(
			'CREATE TABLE IF NOT EXISTS up_LitLab_rating
			(
				BOOK_ID    INT NOT NULL,
				USER_ID    INT NOT NULL,
				ESTIMATION INT NOT NULL
			);'
		);
	}
});

__litlabMigrate(5, function($updater, $DB) {
	if ($updater->CanUpdateDatabase() && !$updater->TableExists('up_LitLab_author'))
	{
		$DB->query(
			'CREATE TABLE IF NOT EXISTS up_LitLab_author
			(
				ID   INT AUTO_INCREMENT NOT NULL,
				NAME VARCHAR(255)       NOT NULL,
				PRIMARY KEY (ID)
			);'
		);
	}
});

__litlabMigrate(6, function($updater, $DB) {
	if ($updater->CanUpdateDatabase() && !$updater->TableExists('up_LitLab_book_author'))
	{
		$DB->query(
			'CREATE TABLE IF NOT EXISTS up_LitLab_book_author
			(
				BOOK_ID   INT NOT NULL,
				AUTHOR_ID INT NOT NULL,
				PRIMARY KEY (BOOK_ID, AUTHOR_ID)
			);'
		);
	}
});

__litlabMigrate(7, function($updater, $DB) {
	if ($updater->CanUpdateDatabase() && !$updater->TableExists('up_LitLab_image'))
	{
		$DB->query(
			'CREATE TABLE IF NOT EXISTS up_LitLab_image
			(
				ID   INT AUTO_INCREMENT NOT NULL,
				PATH VARCHAR(255)       NOT NULL,
				PRIMARY KEY (ID)
			);'
		);
	}
});

__litlabMigrate(8, function($updater, $DB) {
	if ($updater->CanUpdateDatabase() && !$updater->TableExists('up_LitLab_bookshelf'))
	{
		$DB->query(
			'CREATE TABLE IF NOT EXISTS up_LitLab_bookshelf
			(
				ID           INT AUTO_INCREMENT NOT NULL,
				CREATOR_ID   INT                NOT NULL,
				TITLE        VARCHAR(255)       NOT NULL,
				DESCRIPTION  TEXT               NOT NULL,
				LIKES        INT                NOT NULL,
				DATE_CREATED DATETIME           NOT NULL,
				DATE_UPDATED DATETIME           NOT NULL,
				STATUS       VARCHAR(30)        NOT NULL,
				PRIMARY KEY (ID)
			);'
		);
	}
});

__litlabMigrate(9, function($updater, $DB) {
	if ($updater->CanUpdateDatabase() && !$updater->TableExists('up_LitLab_user'))
	{
		$DB->query(
			'CREATE TABLE IF NOT EXISTS up_LitLab_user
			(
				ID       INT AUTO_INCREMENT NOT NULL,
				NAME     VARCHAR(255)       NOT NULL,
				USERNAME VARCHAR(30)        NOT NULL,
				PASSWORD VARCHAR(255)       NOT NULL,
				ROLE     VARCHAR(50)        NOT NULL,
				PRIMARY KEY (ID)
			);'
		);
	}
});

__litlabMigrate(10, function($updater, $DB) {
	if ($updater->CanUpdateDatabase() && !$updater->TableExists('up_LitLab_user_bookshelf'))
	{
		$DB->query(
			'CREATE TABLE IF NOT EXISTS up_LitLab_user_bookshelf
			(
				USER_ID      INT NOT NULL,
				BOOKSHELF_ID INT NOT NULL,
				PRIMARY KEY (USER_ID, BOOKSHELF_ID)
			);'
		);
	}
});

__litlabMigrate(11, function($updater, $DB) {
	if ($updater->CanUpdateDatabase() && !$updater->TableExists('up_LitLab_bookshelf_book'))
	{
		$DB->query(
			'CREATE TABLE IF NOT EXISTS up_LitLab_bookshelf_book
			(
				BOOKSHELF_ID INT NOT NULL,
				BOOK_ID      INT NOT NULL,
				COMMENT      VARCHAR(400),
				PRIMARY KEY (BOOKSHELF_ID, BOOK_ID)
			);'
		);
	}
});

__litlabMigrate(12, function($updater, $DB) {
	if ($updater->CanUpdateDatabase() && !$updater->TableExists('up_LitLab_tag'))
	{
		$DB->query(
			'CREATE TABLE IF NOT EXISTS up_LitLab_tag
			(
				ID    INT AUTO_INCREMENT NOT NULL,
				TITLE VARCHAR(150)       NOT NULL,
				PRIMARY KEY (ID)
			);'
		);
	}
});

__litlabMigrate(13, function($updater, $DB) {
	if ($updater->CanUpdateDatabase() && !$updater->TableExists('up_LitLab_tag_bookshelf'))
	{
		$DB->query(
			'CREATE TABLE IF NOT EXISTS up_LitLab_tag_bookshelf
			(
				TAG_ID       INT NOT NULL,
				BOOKSHELF_ID INT NOT NULL,
				PRIMARY KEY (TAG_ID, BOOKSHELF_ID)
			);'
		);
	}
});

__litlabMigrate(15, function($updater, $DB) {
	if ($updater->CanUpdateDatabase() && !$updater->TableExists('up_LitLab_book'))
	{
		$DB->query(
			'alter table up_LitLab_book modify ISBN varchar(20) null;'
		);
	}
});
