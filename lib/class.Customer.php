<?php

	/**
	 * Class to handle information about customers
	 * that patnorize the store. Used for capturing
	 * customer details, their frequency of buying
	 * items, or any deposits made for products
	 * 
	 * @author Luke <mugapedia@gmail.com>
	 * @date September 01, 2016
	 */
	class Customer extends DatabaseObject
	{
		protected static $table_name = "customer";
		protected static $db_fields = array('id', 'name', 'phone');
		public $id;
		protected $_name;
		protected $_phone;
		
		/**
		 * Get customers name
		 * @return string
		 */
		public function getName()
		{
			if(isset($this->_name)){
				return $this->_name;	
			}
		}
		
		/**
		 * Set the customer's name
		 * @param string $name
		 */
		public function setName($name)
		{
			if($this->_isValidName($name)){
				$this->_name = $name;	
			}
		}
		
		/**
		 * Test customer's name for validity
		 * @param string $name
		 * @return boolean
		 */
		protected function _isValidName($name)
		{
			$valid_name = '/^[a-zA-Z\'\s]+$/';
			return preg_match($valid_name, $name) ? true : false;
		}
		
		/**
		 * Get the customer's phone number
		 * @return int
		 */
		public function getPhoneNumber()
		{
			if(isset($this->_phone)){
				return $this->_phone;	
			}
		}
		
		/**
		 * Set the phone number of a customer
		 * @param int $phone_no
		 */
		public function setPhoneNumber($phone_no)
		{
			if($this->_isValidPhoneNumber($phone_no)){
				$this->_phone = $phone_no;	
			}
		}
		
		/**
		 * Check if a phone number is valid
		 * @param int $phone_no
		 * @return boolean
		 */
		protected function _isValidPhoneNumber($phone_no)
		{
			$valid_phone_no = "#^[0]{1}[0-9]{9}$|^\+?[254]{3}[0-9]{9}$#";
			return preg_match($valid_phone_no, $phone_no) ? true : false;
		}
	}

?>