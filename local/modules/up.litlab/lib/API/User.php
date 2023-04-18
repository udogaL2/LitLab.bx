<?php

namespace Up\Litlab\API;

use Up\LitLab\Model\UserTable;

class User
{
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