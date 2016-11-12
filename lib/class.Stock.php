<?php

	/**
	 * Class to keep track of stock
	 *
	 * @date August 04, 2016
	 * @author Luke <mugapedia@gmail.com>
	 */
	class Stock extends DatabaseObject
	{
		public static $table_name = "stock";
		public static $db_fields = array('id', 'cat_id', 'code', 'size', 'color', 'units', 'created', 'last_updated', 'buy_price', 'total_cost');
		public $id;
		protected $_cat_id;
		protected $_code;
		protected $_size;
		protected $_color;
		protected $_units;
		protected $_created;
		protected $_last_updated;
		protected $_buy_price;
		protected $_total_cost;
		
		protected static $_instance;
		
		/**
		 * Get ID of the category that stock item belongs to
		 * @retrurn int $category_id
		 */
		public function getCatgoryId()
		{
			if(isset($this->_cat_id)){
				return $this->_cat_id;	
			}
		}
		
		/**
		 * Set the ID of the category that stock item belongs to
		 * @param int $cat_id
		 */
		public function setCategoryId($cat_id)
		{
			$cat_id = intval($cat_id);
			$this->_cat_id = $cat_id;
		}
		
		/**
		 * Check stock item name for validity
		 * @param mixed $name
		 * @return boolean
		 */
		protected function _isValidName($name)
		{
			$valid_name = '/^[0-9a-zA-Z_\-\h]+$/';
			return preg_match($valid_name, $name) ? true : false;
		}
		
		/**
		 * Get code of stock item
		 * @return mixed 
		 */
		public function getCode()
		{
			if(isset($this->_code)){
				return $this->_code;	
			}
		}
		
		/**
		 * Set code of stock item
		 * @param mixed $code
		 */
		public function setCode($code)
		{
			if($this->_isValidName($code)){
				$this->_code = $code;	
			}
		}
		
		/**
		 * Get size of stock item
		 * @return int 
		 */
		public function getSize()
		{
			if(isset($this->_size)){
				return $this->_size;	
			}
		}
		
		/**
		 * Set size of stock item
		 * @param int $size
		 */
		public function setSize($size)
		{
			if($this->_isValidUnit($size)){
				$this->_size = $size;	
			}
		}
		
		/**
		 * Get color of stock item
		 * @return mixed 
		 */
		public function getColor()
		{
			if(isset($this->_color)){
				return $this->_color;	
			}
		}
		
		/**
		 * Set color of stock item
		 * @param mixed $color
		 */
		public function setColor($color)
		{
			if($this->_isValidName($color)){
				$this->_color = $color;	
			}
		}
		
		/**
		 * Check whether value entered for units is a valid integer
		 * @param int $unit
		 * @return boolean
		 */
		protected function _isValidUnit($unit)
		{
			$valid_unit = '/^[0-9]+$/';
			return preg_match($valid_unit, $unit) ? true : false;
		}
		
		/**
		 * Get the no. of units for a particular stock item
		 * @return mixed
		 */
		public function getUnits()
		{
			if(isset($this->_units)){
				if($this->_units == 0){
					return "<span class='out_of_stock'>OUT OF STOCK</span>";
				}
				return $this->_units;	
			}
		}
		
		/**
		 * Set the no. of units for a particular stock
		 * @param int $units
		 */
		public function setUnits($units)
		{
			if($this->_isValidUnit($units)){
				$this->_units = $units;	
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
		
		/**
		 * Get the date the particular stock was updated
		 * @return string $date
		 */
		public function getDateUpdated()
		{
			if(isset($this->_last_updated)){
				return $this->_last_updated;
			}
		}
		
		/**
		 * Set date particular stock was updated by adding
		 * additional items to stock
		 * @todo Use current timestamp to get date
		 */
		public function setDateUpdated()
		{
			$date = strftime("%Y-%m-%d %H:%I:%S", time());
			$this->_last_updated = $date;
		}
		
		/**
		 * Check unit price of stock item for validity
		 * @param float $price
		 * @return boolean
		 */
		protected function _isValidPrice($price)
		{
			$valid_price = '/^[0-9]+(\.[0-9]{2})?$/';
			return preg_match($valid_price, $price) ? true : false;	
		}
		
		/**
		 * Get the buying price of a unit od stock item
		 * @return float
		 */
		public function getBuyPrice()
		{
			if(isset($this->_buy_price)){
				return $this->_buy_price;	
			}
		}
		
		/**
		 * Set the buying price for a unit of stock item
		 * @param float $price
		 */
		public function setBuyPrice($price)
		{
			if($this->_isValidPrice($price)){
				$this->_buy_price = $price;	
			}
		}
		
		/**
		 * Calculate the total cost of stock items bought
		 * @todo multiply no. of units by price of each unit
		 * @return float
		 */
		protected function _calcCostOfStock()
		{
			$total = floatval($this->_buy_price) * intval($this->_units);
			return $total;
		}
		
		/**
		 * Get total cost of stock items
		 * @return float
		 */
		public function getTotal()
		{
			if(isset($this->_total_cost)){
				return number_format($this->_total_cost);	
			}
		}
		
		/**
		 * Set the total cost of stock items bought
		 * @todo Use calcCostOfStock() method to get cost of stock
		 */
		public function setTotal()
		{
			$cost = $this->_calcCostOfStock();
			$this->_total_cost = $cost;
		}
		
		/**
		 * Create HTML element to display names of stock items
		 * @param int $item_id Stock ID 
		 * @param boolean $in_stock Boolean flag that determines whether to display only items that are in stock
		 */
		public function displayItems($in_stock=false,$item_id="")
		{
			if($in_stock == true){
				$items_array = self::findItemsInStock();	
			} else {
				$items_array = static::findAll();
			}
			if($item_id != ""){
				$html  = '<select name="item" id="item">';
				$html .= '<option value="">Select Item</option>';
				foreach($items_array as $item){
					if(intval($item->id) == intval($item_id)){
						$html .= '<option value="'.$item->id.'" selected="selected">'.$item->getCode().' '.$item->getColor().'['.$item->getSize().']</option>';	
					}
					$html .= '<option value="'.$item->id.'">'.$item->getCode().' '.$item->getColor().'['.$item->getSize().']</option>';	
				}
				$html .= '</select>';
				echo $html;
			} else {
				$html  = '<select name="item" id="item">';
				$html .= '<option value="" selected="selected">Select Item</option>';
				foreach($items_array as $item){
					$html .= '<option value="'.$item->id.'">'.$item->getCode().' '.$item->getColor().'['.$item->getSize().']</option>';	
				}
				$html .= '</select>';
				echo $html;
			}
		}
		
		/**
		 * Get all the items stocked by the shop that have
		 * not yet been exhausted as a result of selling
		 * @return array An array of stock objects
		 */
		public static function findItemsInStock()
		{
			$in_stock = array();
			$shop_items = static::findAll();
			foreach($shop_items as $item){
				if(intval($item->getUnits() >= 1)){
					$in_stock[] = $item;	
				}
			}
			return $in_stock;
		}
		
		/**
		 * Factory method to generate instance of stock class
		 * @return object Stock object
		 */
		public static function getInstance()
		{
			if(!self::$_instance){
				self::$_instance = new self;	
			} 
			return self::$_instance;
		}
		
		/**
		 * Get name of category for stock items
		 * @return string
		 */
		public function getCategoryName()
		{
			$db = Database::getInstance();
			$mysqli = $db->getConnection();
			$sql = "SELECT name FROM category WHERE id = ".$this->_cat_id." LIMIT 1";
			$result = $mysqli->query($sql);
			$cat_name = $result->fetch_assoc();
			return array_shift($cat_name);
		}
		
		/**
		 * Increase the quantity of a particular stock item
		 * @param int $quantity
		 */
		public function addUnits($quantity)
		{
			if($this->_isValidUnit($quantity)){
				$this->_units += $quantity;	
			}
		}
	}

?>