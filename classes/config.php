<?php

namespace Sprisimulator;

use Fuel\Core\Log;

class Config extends \Fuel\Core\Config
{
	const string PREFIX_CONF_REFERENCE = 'config:';

	public static function get($item, $default = null)
	{
		$response = parent::get($item, $default);

		if (is_string($response) && strlen($response) > strlen(static::PREFIX_CONF_REFERENCE) && str_starts_with($response, static::PREFIX_CONF_REFERENCE))
		{
			return parent::get(substr($response, strlen(static::PREFIX_CONF_REFERENCE)), $default);
		}

		return $response;
	}
}