<?php

class Moji {

	/**
	 * All of the helpers that have been registered.
	 *
	 * @var array
	 */
	static public $helpers = array();

	/**
	 * Register a template helper.
	 *
	 * @param  string   $name
	 * @param  Closure  $helper
	 * @return void
	 */  
	public static function helper($name, Closure $helper)
	{
		static::$helpers[$name] = $helper;
	}

	/**
	 * Parse the content for template tags.
	 *
	 * @return string
	 */
	public static function parse($content)
	{
		if(count(static::$helpers) == 0)
		{
			// The regular expression could match some unwanted tags if there are
			// no helpers. To prevent this from happening, parsing will be
			// forced to end here.
			return $content;
		}

		$names = array();

		foreach(static::$helpers as $name => $helper)
		{
			$names[] = preg_quote($name, '/');
		}

		$regexp = '/\{\{('.implode('|', $names).')(.*?)\}\}/u';

		return preg_replace_callback($regexp, function($match)
		{
			list(, $name, $params) = $match;

			if( ! empty($params))
			{
				$params = trim($params);

				preg_match_all("/(\S+?)\s*=\s*(\042|\047)([^\\2]*?)\\2/is", $params, $matches, PREG_SET_ORDER);

				$params = array();

				foreach($matches as $match)
				{
					$params[$match[1]] = $match[3];
				}
			}

			return Moji::call($name, $params);
		}, $content);
	}

	/**
	 * Call a template helper.
	 *
	 * @param  string  $name
	 * @param  array   $params
	 * @return mixed
	 */
	public static function call($name, $params = array())
	{
		$helper = static::$helpers[$name];

		return $helper($params);
	}
}