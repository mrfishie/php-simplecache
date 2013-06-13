<?php
/**
 * PHP SimpleCache Class
 *
 * A class using simple logic and the inbuilt Memcache class to easily store
 * and get information without much changes to other code.
 *
 * @category  Cache
 * @package   PHPSimpleCache
 * @author    Tom Barham <me@mrfishie.com>
 * @copyright 2013 mrfishie Studios
 * @license   This work is licensed under the Creative Commons Attribution 3.0 Unported License
 * @version   1.0.0
 * @since     Class available since Release 1.0.0
 */
if (!class_exists("SimpleCache")) {
  class SimpleCache {
		private $obj;
		private $_error_handler;
		
		/**
		 * SimpleCache Constructor
		 *
		 * Called when the object is created
		 *
		 * @param host Set the host for the memcache object. Must be a string. Default of localhost
		 * @param port Set the port for the memcache object. Must be an int. Default of 11211
		 */
		public function __construct($host = "localhost", $port = 11211) {
			$this->_error_handler = die;
			$obj = new Memcache;
			$obj->connect($host, $port) or $this->_error("Cannot connect to cache.");
		}
		
		/**
		 * Getter/Setter for key
		 *
		 * Calls the function if they key does not exist and stores the value.
		 * Then it returns the value of the key
		 *
		 * @param key Set the key to be used to store. Must be a string. No default
		 * @param function A function to be called if the key does not exist. Must be a callable. No default
		 * @param expire Set when the cached object will expire. Must be an int. Default of 0 (infinite)
		 * @param compress Decide whether to compress on-the-fly. Must be a bool. Default of true
		 */
		public function Key($key, $function, $expire = 0, $compress = true) {
			if (!isset($this->obj->get($key))) {
				$func_return = $function();
				$flag = $compress ? MEMCACHE_COMPRESSED : 0;
				$this->obj->set($key, $func_return, $flag, $expire) or $this->_error("Cannot set key '" . $key . "'.");;
			}
			return $this->obj->get($key);
		}
		
		/**
		 * Error Setter
		 *
		 * Set the function to be called when the caching system has an error. Default of die.
		 *
		 * @param function The function to be called on an error. A string is passed as the first parameter. Must be a callable. Default of die
		 */
		public function SetError($function) {
			$this->error_handler = $function;
		}
		
		private function _error($text) {
			$this->_error_handler("SimpleCache - " . $text);
		}
	}
}
?>
