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
	 * API Endpoint
	 */
	const ENDPOINT	= 'http://api.twtmore.com';
	
	/**
	 * API Version
	 */
	const VERSION	= 3;
	
	/**
	 * Local cache of apikey config option
	 *
	 * @var $_apikey
	 * @access protected
	 */
	protected static $_apikey	= false;
	
	/**
	 * Accepted API Response Formats, currently on JSON
	 */
	protected static $_formats	= array( 'json' );
	
	/**
	 * Selected API Response Format
	 */
	protected static $_format	= 'json';
	
	/**
	 * init method called by autoloader
	 *
	 * @throws Twtmore_Exception
	 * @return void
	 */
	public static function _init()
	{
		\Config::load('twtmore', 'twtmore', true);
		
		if (!self::$_apikey = \Config::get('twtmore.apikey'))
		{
			throw new Twtmore_Exception('No API Key set in config.');
		}
		
		if ($format = \Config::get('twtmore.format'))
		{
			if (!in_array($format, self::$_formats))
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
	
	/**
	 * Retrieve information about a tweet
	 *
	 * @param string $id eg: "A" or "XgD"
	 * @return string $response
	 * @see http://dev.twtmore.com/docs/api/tweet
	 */
	public static function tweet($id)
	{
		$body = array(
			'id' => $id
		);
		
		return self::_request('tweet', $body);
	}
	
	/**
	 * Shorten a tweet with twtmore
	 *
	 * @param string $user 
	 * @param string $tweet 
	 * @param string $reply_to_user 
	 * @param string $reply_to_tweet 
	 * @return string $response
	 * @see http://dev.twtmore.com/docs/api/shorten
	 */
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
	
	/**
	 * Update the Twitter Status ID of a twtmore tweet with the "callback_key" returned from the "shorten" API Method
	 *
	 * @param string $key 
	 * @param string $status_id 
	 * @return string $response
	 * @author Tom Arnfeld
	 */
	public static function callback($key, $status_id)
	{
		$body = array(
			'key' => $key,
			'status_id' => $status_id
		);
		
		return self::_request('callback', $body);
	}
	
	/**
	 * Perform a request to the API with a method and body
	 *
	 * @param string $method 
	 * @param string $body 
	 * @return string $response
	 */
	protected static function _request($method, $body = array())
	{
		$ch = curl_init();
		
		$url = self::ENDPOINT . '/v' . self::VERSION . '/' . $method;
		
		curl_setopt($ch, CURLOPT_URL, $url);
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
					throw new Twtmore_Exception_Unauthorized('Invalid API Key');
					break;
				case 400:
					throw new Twtmore_Exception_BadRequest('Bad Request');
					break;
				case 404:
					throw new Twtmore_Exception_NotFound('Method / Version not found');
					break;
				case 500:
					throw new Twtmore_Exception_InternalError('Internal Error');
					break;
				default:
					throw new Twtmore_Exception('API Returned != OK response: ' . $response_status);
					break;
			}
		}
		
		return self::_decode($response, self::$_format);
	}
	
	/**
	 * Decode the response with the passed format
	 *
	 * @param string $data 
	 * @param string $format 
	 * @return $decoded (array/object)
	 */
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