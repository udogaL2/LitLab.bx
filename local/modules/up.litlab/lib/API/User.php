<?php

namespace Up\Litlab\API;

use Up\Litlab\Model\UserTable;

class User
{
	public function registerUser(array $params){
		return UserTable::add($params);
	}

	public function getUserId($name){
		$name = htmlspecialcharsbx($name);
		$result = UserTable::query()
						   ->setSelect(array('ID'))
						   ->setFilter(array('NAME'=>$name))
						   ->fetchAll();
		return $result[0]['ID'];
	}

	public function checkLogin($login){
		$login = htmlspecialcharsbx($login);
		$result = UserTable::query()
						   ->setSelect(array('ID'))
						   ->setFilter(array('NAME'=>$login))
						   ->fetchAll();
		if ($result){
			return false;
		}
		return true;
	}
	public function checkPass($pass){
		$pass = htmlspecialcharsbx($pass);
		$result = UserTable::query()
						   ->setSelect(array('ID'))
						   ->setFilter(array('PASSWORD'=>$pass))
						   ->fetchAll();
		if ($result){
			return false;
		}
		return true;
	}


	public function validateAuthForm($login, $password)
	{
		if (strlen($login) < 4)
		{
			return 'ERROR4';
		}
		if (strlen($login) > 63)
		{
			return 'ERROR5';
		}
		if (strlen($password) < 7)
		{
			return 'ERROR6';
		}
		if (strlen($password) > 63)
		{
			return 'ERROR7';
		}

		return '';
	}

	public function getCreatorInfo(int $userId): bool|array
	{
		return UserTable::getByPrimary($userId)->fetch();
	}

	public function getUserNames(array $userIds)
	{
		$names = UserTable::query()
			->setSelect(['ID', 'NAME'])
			->where('ID', 'in', $userIds)
			->fetchAll();

		$result = [];

		foreach ($names as $name)
		{
			$result[$name['ID']] = $name['NAME'];
		}

		return $result;
	}
}