<?php

namespace AndreattaTest\Util;

class Util
{

	public static function randomInt($min = 0, $max = 65535)
	{

		return rand($min, $max);

	}

	public static function randomFloat($min = 0, $max = 255)
	{

		return (double) rand($min * 255, $max * 255) / 255.0;

	}

	public static function randomBool()
	{

		return (bool) rand(0, 1);

	}

	public static function randomString($length = 20, $case = 'random')
	{

		$letters = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

		$result = substr( str_shuffle($letters), 0, $length );

		switch($case)
		{

			case 'lower':

				return strtolower($result);

			case 'upper':

				return strtoupper($result);

		}

		return $result;

	}

	public static function randomEmail()
	{

		return self::randomString(10) . '@' . self::randomString(10) . '.com';

	}

}