<?php

/**
 * Twtmore API Client Library for FueLPHP
 *
 * @package		Fuel-Twtmore
 * @version		1.0
 * @author		Tom Arnfeld (dev@twtmore.com)
 * @link		http://github.com/twtmore/fuel-twtmore
 * 
 */

namespace Twtmore;

class Twtmore {
	
	/**
	 * Local cache of apikey config option
	 *
	 * @var $_apikey
	 * @access protected
	 */
	protected static $_apikey = false;
	
	/**
	 * init method called by autoloader
	 *
	 * @throws Twtmore_Exception
	 * @return void
	 */
	public static function _init()
	{
		\Config::load('twtmore');
		
		if (!self::$_apikey = \Config::get('twtmore.apikey'))
		{
			throw new Twtmore_Exception('No API Key set in config');
		}
	}
}