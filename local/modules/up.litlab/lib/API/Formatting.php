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

	public function createSearchRequest(int $tagId = null, string $searchSubstr = ''): string
	{
		if ($tagId && $searchSubstr)
		{
			return "genre_id={$tagId}" . "&search={$searchSubstr}";
		}
		elseif ($tagId)
		{
			return "genre_id={$tagId}";
		}
		elseif ($searchSubstr){
			return "search=" . $searchSubstr;
		}

		return '';
	}

}