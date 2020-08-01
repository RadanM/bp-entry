<?php
declare(strict_types=1);

namespace App\ApiModule;

class ApiHelper
{
	public static function getUri(string $endpoint, string $version = 'v1'): string
	{
		return $_SERVER['SERVER_ADDR'] . "/api/$version/$endpoint";
	}
}