<?php

	/**
	 * Stock category class
	 * @author Luke <mugapedia@gmail.com>
	 * @date August 09, 2016
	 */
	class Category extends DatabaseObject
	{
		protected static $table_name = "category";
		protected static $db_fields = array('id', 'name', 'created');
		public $id;
		protected $_name;
		protected $_created;
		
		/**
		 * Validate name of a stock product
		 * @param mixed $_name
		 * @return boolean
		 */
		protected function _isValidName($name)
		{
			$valid_name = '/^[a-zA-Z0-9\-_\h]+$/';
			return preg_match($valid_name, $name) ? true : false;
		}
		
		/**
		 * Get name of a stock product
		 * @return mixed
		 */
		public function getName()
		{
			if(isset($this->_name)){
				return $this->_name;	
			}
		}
		
		/**
		 * Set the name of a stock item
		 * @param mixed $name
		 */
		public function setName($name)
		{
			if($this->_isValidName($name)){
				$this->_name = $name;	
			}
		}
		
		/**
		 * Check if a stock category already exists
		 * @param mixed $cat_name
		 * @return boolean
		 */
		public function categoryExists($cat_name)
		{
			$db = Database::getInstance();
			$mysqli = $db->getConnection();
			$sql  = "SELECT name FROM ".self::$table_name." WHERE name = '";
			$sql .= $mysqli->real_escape_string($cat_name)."' LIMIT 1";	
			$result = $mysqli->query($sql);
			return ($result->num_rows == 1) ? true : false;		
		}
		
		/**
		 * Format name of stock category such that each word
		 * starts with a capital letter followed by small letters
		 * @param mixed $cat_name
		 * @return mixed $cat_name
		 */
		public function formatName($cate_name)
		{
			$parts = explode(' ', $cate_name);
			$cased_parts = array_map('strtolower', $parts);
			$cased_parts = array_map('ucfirst', $cased_parts);
			if(count($cased_parts) == 1){
				return array_shift($cased_parts);	
			} else {
				$name = implode(' ', $cased_parts);
				return $name;	
			}
		}
		
		/**
		 * Check if a date is a valid Gregorian date
		 * @param string $date
		 * @return boolean
		 */
		protected function _isValidDate($date)
		{
			$timestamp = strtotime($date);
			$date_format = strftime("%m-%d-%Y", $timestamp);
			$valid_date = str_replace("-", ",", $date_format);
			$date_parts = explode(",", $valid_date);
			$month = $date_parts[0];
			$day   = $date_parts[1];
			$year  = $date_parts[2];
			return checkdate($month, $day, $year) ? true : false;
		}
		
		/**
		 * Get the date the particular stock was added
		 * @return string 
		 */
		public function getDateCreated()
		{
			if(isset($this->_created)){
				return $this->_created;
			}
		}
		
		/**
		 * Set date particular stock was added
		 * @param string $created
		 */
		public function setDateCreated($created)
		{
			if($this->_isValidDate($created)){
				$time = strftime("%H:%I:%S", time());
				$this->_created = $created." ".$time;	
			}
		}
	}

?>