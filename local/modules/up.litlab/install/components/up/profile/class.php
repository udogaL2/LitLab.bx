<?php
use Bitrix\Main\DI\ServiceLocator;
use Up\Litlab\API\User;

class LitlabProfileComponent extends CBitrixComponent
{
	public function executeComponent()
	{
		$this->prepareTemplateParams();
		if($_SERVER['REQUEST_URI'] === '/logout/'){
			$this->logout();
		}
		$this->fetchUserBookshelfList();
		$this->includeComponentTemplate();
	}

	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	protected function prepareTemplateParams()
	{
	}

	protected function fetchBookshelfList()
	{

	}
	protected function fetchUserBookshelfList()
	{
		$userBookshelfApi = new \Up\Litlab\API\Bookshelf();
		$formattingApi = ServiceLocator::getInstance()->get('Formatting');
		$userApi = new User;

		$this->arResult['userApi'] = $userApi;
		$this->arResult['userBookshelfApi'] = $userBookshelfApi;
		$this->arResult['FormattingApi'] = $formattingApi;
	}

	protected function logout(){
		$_SESSION = array();
		session_destroy();
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
			}
		}
		LocalRedirect('/');
	}
}