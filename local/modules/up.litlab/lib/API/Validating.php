<?php

namespace Up\Litlab\API;

class Validating{
	public function validateRegisterForm($login, $nickname, $password)
	{
		if (strlen($login) < 4)
		{
			return 'UP_LITLAB_INSUFFICIENT_LOGIN_LENGTH';
		}
		if (strlen($login) > 63)
		{
			return 'UP_LITLAB_EXCEEDING_LOGIN_LENGTH';
		}
		if (strlen($nickname) < 4)
		{
			return 'UP_LITLAB_INSUFFICIENT_NICKNAME_LENGTH';
		}
		if (strlen($nickname) > 30)
		{
			return 'UP_LITLAB_EXCEEDING_NICKNAME_LENGTH';
		}
		if (strlen($password) < 7)
		{
			return 'UP_LITLAB_INSUFFICIENT_PASSWORD_LENGTH';
		}
		if (strlen($password) > 63)
		{
			return 'UP_LITLAB_EXCEEDING_PASSWORD_LENGTH';
		}

		return true;
	}

	public function validateAuthForm($login, $password)
	{
		if (strlen($login) < 4)
		{
			return 'UP_LITLAB_INSUFFICIENT_LOGIN_LENGTH';
		}
		if (strlen($login) > 63)
		{
			return 'UP_LITLAB_EXCEEDING_LOGIN_LENGTH';
		}

		if (strlen($password) < 7)
		{
			return 'UP_LITLAB_INSUFFICIENT_PASSWORD_LENGTH';
		}
		if (strlen($password) > 63)
		{
			return 'UP_LITLAB_EXCEEDING_PASSWORD_LENGTH';
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