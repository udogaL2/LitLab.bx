<?php

namespace Up\Litlab\API;

use Up\Litlab\Model\UserTable;

class User
{
	public function registerUser(array $params){
		return UserTable::add($params);
	}

	public function getUserId($name){
		$result = UserTable::query()
						   ->setSelect(array('ID'))
						   ->setFilter(array('NAME'=>$name))
						   ->fetchAll();
		return $result[0]['ID'];
	}

	public function getUserRole(int $id){
		return UserTable::query()
		   ->setSelect(['ROLE'])
		   ->setFilter(['ID' => $id])
		   ->fetchAll()
		   [0]['ROLE']
		;
	}

	public function checkLogin($login){
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
		$result = UserTable::query()
						   ->setSelect(array('ID'))
						   ->setFilter(array('PASSWORD'=>$pass))
						   ->fetchAll();
		if ($result){
			return false;
		}
		return true;
	}




	public function getCreatorInfo(int $userId): bool|array
	{
		return UserTable::getByPrimary($userId)->fetch();
	}

	public function getUserName(int $userId){
		$name = UserTable::query()
			->setSelect(['NAME'])
			->setFilter(['ID'=>$userId])
			->fetch();
		return $name['NAME'];
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