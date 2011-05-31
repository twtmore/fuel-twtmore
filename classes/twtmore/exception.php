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

class Twtmore_Exception extends \Exception { }

class Twtmore_Exception_Unauthorized extends Twtmore_Exception { }

class Twtmore_Exception_BadRequest extends Twtmore_Exception { }

class Twtmore_Exception_InternalError extends Twtmore_Exception { }

class Twtmore_Exception_NotFound extends Twtmore_Exception { }