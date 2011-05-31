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
	
	const ENDPOINT	= 'http://api.twtmore.new.local'; //api.twtmore.com
	
	const VERSION	= 1;
	
	const FORMATS	= array('json');
	
	/**
	 * Local cache of apikey config option
	 *
	 * @var $_apikey
	 * @access protected
	 */
	protected static $_apikey	= false;
	
	protected static $_format	= 'json';
	
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
			throw new Twtmore_Exception('No API Key set in config.');
		}
		
		if ($format = \Config::get('twtmore.format'))
		{
			if (!in_array($format, self::FORMATS))
			{
				throw new Twtmore_Exception('Invalid API Format.');
			}
			
			self::$_format = $format;
		}
		
		if (!function_exists('curl_init'))
		{
			throw new Twtmore_Exception('cURL must be installed,');
		}
	}
	
	public static function tweet($id)
	{
		$body = array(
			'id' => $id
		);
		
		return self::_request('tweet', $body);
	}
	
	public static function shorten($user, $tweet, $reply_to_user = false, $reply_to_tweet = false)
	{
		$body = array(
			'user' => $user,
			'tweet' => $tweet
		);
		
		if ($reply_to_user && $reply_to_tweet)
		{
			$body['reply_to_user'] = $reply_to_user;
			$body['reply_to_tweet'] = $reply_to_tweet;
		}
		
		return self::_request('shorten', $body);
	}
	
	protected static function _request($method, $body = array())
	{
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, self::ENDPOINT . '/v' . self::VERSION . '/' . $method);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		// Merge in the API Key
		$body = array_merge($body, array( 'apikey' => self::$_apikey ));
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
		
		$response = curl_exec($ch);
		
		if ($error = curl_error($ch))
		{
			throw new Twtmore_Exception_CurlError($error);
		}
		
		$response_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		if ($response_status != 200)
		{
			switch ($response_status)
			{
				case 401:
					throw new Twtmore_Exception_Unauthorized('');
					break;
				case 400:
					throw new Twtmore_Exception_BadRequest('');
					break;
				case 404:
					throw new Twtmore_Exception_Unauthorized('');
					break;
				case 500:
					throw new Twtmore_Exception_InternalError('');
					break;
				default:
					throw new Twtmore_Exception('API Returned != OK response: ' . $response_status);
					break;
			}
		}
		
		return self::_decode($response, self::$_format);
	}
	
	protected static function _decode($data, $format = false)
	{
		if (!$format)
		{
			$format = self::$_format;
		}
		
		if ($format == 'json')
		{
			return json_decode($data);
		}
		
		return $data;
	}
}