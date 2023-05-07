<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class up_litlab extends CModule
{
	public $MODULE_ID = 'up.litlab';
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;

	public function __construct()
	{
		$arModuleVersion = [];
		include(__DIR__ . '/version.php');

		if (is_array($arModuleVersion) && $arModuleVersion['VERSION'] && $arModuleVersion['VERSION_DATE'])
		{
			$this->MODULE_VERSION = $arModuleVersion['VERSION'];
			$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		}

		$this->MODULE_NAME = Loc::getMessage('UP_LITLAB_MODULE_NAME');
		$this->MODULE_DESCRIPTION = Loc::getMessage('UP_LITLAB_MODULE_DESCRIPTION');
	}

	public function installDB(): void
	{
		global $DB;

		$DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.litlab/install/db/install.sql');

		ModuleManager::registerModule($this->MODULE_ID);
	}

	public function uninstallDB($arParams = []): void
	{
		global $DB;

		$DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.litlab/install/db/uninstall.sql');

		ModuleManager::unRegisterModule($this->MODULE_ID);
	}

	public function installFiles(): void
	{
		CopyDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.litlab/install/components',
			$_SERVER['DOCUMENT_ROOT'] . '/local/components/',
			true,
			true
		);

		CopyDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.litlab/install/templates',
			$_SERVER['DOCUMENT_ROOT'] . '/local/templates/',
			true,
			true
		);

		CopyDirFiles(
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.litlab/install/routes',
			$_SERVER['DOCUMENT_ROOT'] . '/local/routes/',
			true,
			true
		);
	}

	public function installData(): void
	{
		global $DB;

		$booksSQLScripts = explode(';', file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.litlab/install/data/books.sql'));
		array_pop($booksSQLScripts);

		foreach ($booksSQLScripts as $key => $booksSQLScript){
			$img = ['img' => array_merge(CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'] . "/local/modules/up.litlab/install/data/img/bookIMG_{$key}.jpg"), ["MODULE_ID" => "LitLab"])];
			CFile::SaveForDB($img, 'img', 'img');
			$originalName = "bookIMG_{$key}.jpg";
			$imgId = CFile::GetList(arFilter:["MODULE_ID" => "LitLab", 'ORIGINAL_NAME' => $originalName])->Fetch()['ID'];
			$preparedBooksSQLScript = str_replace('%photo_id%', $imgId, $booksSQLScript);
			$DB->Query($preparedBooksSQLScript);
		}

		$DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/local/modules/up.litlab/install/data/data.sql');
	}

	public function uninstallFiles(): void
	{
		$this->deleteDir($_SERVER['DOCUMENT_ROOT'] . '/local/components/');
		$this->deleteDir($_SERVER['DOCUMENT_ROOT'] . '/local/templates/');
		$this->deleteDir($_SERVER['DOCUMENT_ROOT'] . '/local/routes/');
		$this->deleteDir($_SERVER['DOCUMENT_ROOT'] . '/upload/img/');
	}

	function deleteDir($dirPath) {
		$files = array_diff(scandir ($dirPath), array('..', '.'));;
		foreach ($files as $file) {
			$filePath = $dirPath . $file;
			if (is_dir($filePath)) {
				$this->deleteDir($filePath . '/');
			} else {
				unlink($filePath);
			}
		}
		rmdir($dirPath);
	}

	public function doInstall(): void
	{
		global $USER, $APPLICATION;

		if (!$USER->isAdmin())
		{
			return;
		}

		$this->installDB();
		$this->installFiles();
		$this->installEvents();
		$this->installData();

		$APPLICATION->IncludeAdminFile(
			Loc::getMessage('UP_LITLAB_INSTALL_TITLE'),
			$_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . $this->MODULE_ID . '/install/step.php'
		);

	}

	public function doUninstall(): void
	{
		global $USER, $APPLICATION, $step;

		if (!$USER->isAdmin())
		{
			return;
		}

		$step = (int)$step;
		if($step < 2)
		{
			$APPLICATION->IncludeAdminFile(
				Loc::getMessage('UP_LITLAB_UNINSTALL_TITLE'),
				$_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . $this->MODULE_ID . '/install/unstep1.php'
			);
		}
		elseif($step === 2)
		{
			$this->uninstallDB();
			$this->uninstallFiles();
			$this->uninstallEvents();

			$APPLICATION->IncludeAdminFile(
				Loc::getMessage('UP_LITLAB_UNINSTALL_TITLE'),
				$_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . $this->MODULE_ID . '/install/unstep2.php'
			);
		}
	}
}
