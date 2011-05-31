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

Autoloader::add_core_namespace('Twtmore');

Autoloader::add_classes(array(
	'Twtmore\\Twtmore' => __DIR__ . '/classes/twtmore.php',
	
	'Twtmore\\Twtmore_Exception' => __DIR__ . '/classes/twtmore/exception.php',
	'Twtmore\\Twtmore_Exception_Unauthorized' => __DIR__ . '/classes/twtmore/exception.php',
	'Twtmore\\Twtmore_Exception_BadRequest' => __DIR__ . '/classes/twtmore/exception.php',
	'Twtmore\\Twtmore_Exception_InternalError' => __DIR__ . '/classes/twtmore/exception.php',
	'Twtmore\\Twtmore_Exception_NotFound' => __DIR__ . '/classes/twtmore/exception.php',
));