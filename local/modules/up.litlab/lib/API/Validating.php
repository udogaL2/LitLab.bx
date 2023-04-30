<?php

namespace Up\Litlab\API;

class Validating{
	public function validateRegisterForm($login, $nickname, $password)
	{
		if ($this->validate($login, 5, 63)!==true){
			return $this->validate($login, 5, 63);
		}
		if ($this->validate($nickname, 5, 30)!==true){
			return $this->validate($nickname, 5, 30);
		}
		if ($this->validate($password, 8, 63)!==true){
			return $this->validate($password, 8, 63);
		}

		return true;
	}

	public function validateAuthForm($login, $password)
	{
		if ($this->validate($login, 5, 63)!==true){
			return $this->validate($login, 5, 63);
		}
		if ($this->validate($password, 8, 63)!==true){
			return $this->validate($password, 8, 63);
		}

		return true;
	}

	public function validate($value, int $min = null, int $max = null): string|bool
	{
		if (!is_string($value)){
			return "UP_LITLAB_TYPE_ERROR";
		}

		if ($value === ''){
			return "UP_LITLAB_EMPTY_ERROR";
		}

		if ($min !== null){
			if (strlen($value) < $min){
				return 'UP_LITLAB_INSUFFICIENT_VALUE_LENGTH';
			}
		}
		if ($max !== null){
			if (strlen($value) > $max){
				return 'UP_LITLAB_EXCEEDING_VALUE_LENGTH';
			}
		}
		$value = str_replace(" ", "", $value);
		if($value === ""){
			return "UP_LITLAB_EMPTY_ERROR";
		}
		return true;
	}
}