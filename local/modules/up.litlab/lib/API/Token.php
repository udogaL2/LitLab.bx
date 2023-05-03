<?php

namespace Up\Litlab\API;

use Bitrix\Main\Context;
use Bitrix\Main\Filter\Filter;

class Token
{
	public function createToken()
	{
		session_start();
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if ($request === 'GET')
		{
			if (!isset($_SESSION['TOKEN']))
			{
				$_SESSION['TOKEN'] = bin2hex(random_bytes(35));
			}

		}
		return $_SESSION['TOKEN'];
	}
	public function checkToken(?string $token, ?string $sessionToken)
	{
		$request = Context::getCurrent()->getRequest()->getRequestMethod();
		if ($request === 'POST')
		{
			if (!$token || $token !== $sessionToken)
			{
				return 'UP_LITLAB_BAD_TOKEN';
			}
			else{
				return true;
			}
		}
	}
}








