<?php

namespace Up\Litlab\API;

class Formatting
{
	public function prepareText($params): array
	{
		$result = [];

		foreach ($params as $key => $param)
		{
			if (gettype($param) == 'string')
			{
				$result[$key] = htmlspecialcharsbx($param);
			}
			else{
				$result[$key] = $param;
			}
		}

		return $result;
	}
}