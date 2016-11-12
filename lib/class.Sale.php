<?php

	/**
	 * Class that manages sales of stock items
	 * 
	 * @author Luke <mugapedia@gmail.com>
	 * @date August 04, 2016
	 */
	class Sale extends DatabaseObject
	{
		protected static $table_name = "sale";
		protected static $db_fields = array('id', 'stk_id', 'stk_cat', 'item', 'quantity', 'unit_price', 'discount', 'total', 'date', 'receipt', 'cashier');
		public $id;
		protected $_stk_id;
		protected $_stk_cat;
		protected $_item;
		protected $_quantity;
		protected $_unit_price;
		protected $_discount = 0;
		protected $_total;
		protected $_date;
		protected $_receipt;
		protected $_cashier;
		
		/**
		 * Magic __construct
		 * @todo Initialize the $receipt property by
		 * the receipt number generated during the last transaction
		 */
		function __construct()
		{
			$this->_receipt = $this->_lastReceiptNo();
		}
		
		/**
		 * Magic __set
		 * @param string $name Proctected property name
		 * @param mixed $value Value to set property to
		 */
		function __set($name, $value)
		{
			$protected_property_name = '_'.$name;
			if(property_exists($this, $protected_property_name)){
				$this->$protected_property_name = $value;	
			}
			// Unable to access property, trigger error
			trigger_error('Undefined property via __set: '.$name, E_USER_NOTICE);
			return NULL;
		}
		
		/**
		 * Automatically generate the receipt number issued
		 * for the last transaction
		 * @return int
		 */
		protected function _lastReceiptNo()
		{
			$db = Database::getInstance();
			$mysqli = $db->getConnection();
			$sql  = "SELECT receipt FROM ".static::$table_name." ";
			$sql .= "ORDER BY id DESC LIMIT 1";
			$result = $mysqli->query($sql);
			$row = $result->fetch_assoc();
			$receipt_no = $row['receipt'];
			return $receipt_no;	
		}
		
		/**
		 * Get the stock ID for the item sold
		 * @return int
		 */
		public function getStockId()
		{
			if(isset($this->_stk_id)){
				return $this->_stk_id;	
			}
		}
		
		/**
		 * Set stock ID of item sold
		 * @param object $stock_obj An instance of stock object
		 */
		public function setStockId(Stock $stock_obj)
		{
			if($stock_obj instanceof Stock){
				$this->_stk_id = $stock_obj->id;	
			}
		}
		
		/**
		 * Check stock name for validity
		 * @param string $name
		 * @return boolean
		 */
		protected function _isValidName($name)
		{
			$valid_string = "/^[0-9a-zA-Z\-_\'\h]+$/";
			return preg_match($valid_string, $name) ? true : false;
		}
		
		/**
		 * Get the code for a stock item
		 * @return string
		 */
		public function getItem()
		{
			if(isset($this->_item)){
				return $this->_item;	
			}
		}	
		
		/**
		 * Set code of stock item
		 * @param object $stock_obj An instance of stock class
		 */
		public function setItem(Stock $stock_obj){
			if($stock_obj instanceof Stock){
				$this->_item = $stock_obj->getCode();	
			}	
		}
		
		/**
		 * Get stock category for a sale
		 * @return string
		 */
		public function getStockCategory()
		{
			if(isset($this->_stk_cat)){
				return $this->_stk_cat;	
			}
		}
		
		/**
		 * Get color of stock items sold
		 * @return string $color
		 */
		public function getStockColor()
		{
			return Stock::findById($this->_stk_id)->getColor();
		}
		
		/**
		 * Get size of stock items sold
		 * @return string $size
		 */
		public function getStockSize()
		{
			return Stock::findById($this->_stk_id)->getSize();
		}
		
		/**
		 * Set stock category name for the sale
		 * @param object $stock_obj An instance of Stock object
		 */
		public function setStockCategory(Stock $stock_obj)
		{
			$cat_id = (int)$stock_obj->getCatgoryId();
			$this->_stk_cat = Category::findById($cat_id)->getName();
		}
		
		/**
		 * Check to determine quantity is an integer value
		 * @param int $qty Quantity of stock
		 * @return boolean
		 */
		protected function _isValidQuantity($qty)
		{
			$valid_qty = "/^[0-9]+$/";
			return preg_match($valid_qty, $qty) ? true : false;	
		}
		
		/**
		 * Get the quantity of items for a sale
		 * @return int
		 */
		public function getQuantity()
		{
			if(isset($this->_quantity)){
				return $this->_quantity;	
			}
		}
		
		/**
		 * Set the quantity of items for particular sale
		 * @param int Quantity
		 */
		public function setQuantity($qty)
		{
			if($this->_isValidQuantity($qty)){
				$this->_quantity = $qty;	
			}
		}
		
		/**
		 * Test unit selling price of stock item for validity
		 * @param mixed $price Unit price of stock item
		 * @return boolean
		 */
		protected function _isValidUnitPrice($price)
		{
			$valid_price = '/^[0-9]+(\.[0-9]{2})?$/';
			return preg_match($valid_price, $price) ? true : false;	
		}
		
		/**
		 * Get unit selling price of a stock item
		 * @return mixed
		 */
		public function getUnitPrice()
		{
			if(isset($this->_unit_price)){
				return $this->_unit_price;	
			}	
		}
		
		/**
		 * Set unit selling price of an item of stock
		 * @param float $price 
		 */
		public function setUnitPrice($price)
		{
			if($this->_isValidUnitPrice($price)){
				$this->_unit_price = $price;	
			}
		}
		
		/**
		 * Get price discount for a particular sale
		 * @return int / float
		 */
		public function getDiscount()
		{
			if(isset($this->_discount)){
				return $this->_discount;	
			}
		}
		
		/**
		 * Set the discount amount for a particular sale
		 * @param float / int $amount
		 */
		public function setDiscount($amount)
		{
			if($this->_isValidUnitPrice($amount)){
				 $this->_discount = $amount;	
			}
		}
		
		/**
		 * Get the net amount of a particular transaction after 
		 * multiplying number of items sold by the unit price of each
		 * item then deducting the discount offered
		 * 
		 */
		public function getTotal()
		{
			if(isset($this->_total)){
				return $this->_total;	
			}		
		}
		
		/**
		 * Set the total value for a particular sale transaction
		 * @todo Use protected function to calculate sale total
		 */
		public function setTotal()
		{
			$total = $this->_calcNetTransaction();
			$this->_total = $total;
		}
		
		/**
		 * Get the net amount of a particular transaction after 
		 * multiplying number of items sold by the unit price of each
		 * item then deducting the discount offered
		 * @return float Net amount of sale transaction
		 */
		protected function _calcNetTransaction()
		{
			$sale_price = floatval($this->_unit_price) * intval($this->_quantity);
			$net_amount = $sale_price - floatval($this->_discount);
			return $net_amount;
		}
		
		/**
		 * Get the date the sale was made
		 * @return string
		 */
		public function getDate()
		{
			if(isset($this->_date)){
				return $this->_date;	
			}
		}
		
		/**
		 * Set date sale was made
		 * @todo Use current timestamp to generate date
		 */
		public function setDate()
		{
			$this->_date = strftime("%Y-%m-%d %H:%I:%S", time());
		}	
		
		/**
		 * Get the receipt number issued for the transaction
		 * @retun string
		 */
		public function getReceiptNo()
		{
			if(isset($this->_receipt)){
				return $this->_receipt;	
			}
		}
		
		/**
		 * Set the receipt number issued for the transaction
		 * @todo Pad the transaction_id code with zeros to
		 * create unique numbers of uniform length
		 */
		public function generateReceiptNo()
		{
			$date = substr($this->_receipt, 0, 6);
			$number = substr($this->_receipt, 6, 3);
			$chars = preg_split('//', $date, -1, PREG_SPLIT_NO_EMPTY);
			$chars_new = array_chunk($chars, 2);
			$original_date = array();
			foreach($chars_new as $k=>$v){
				$original_date[$k] = join('', $v);	
			}
			$when = join('-', $original_date);
			$today = strftime("%y-%m-%d", time());
			if(strtotime($today) > strtotime($when)){
				$number = 0;
				$raw_num = ++$number;
				$prefix = strftime("%y%m%d", time());
				$padded_num = str_pad(strval($raw_num), 3, '0', STR_PAD_LEFT);	
				$this->_receipt = $prefix.$padded_num;
			} else {
				$raw_num = ++$number;
				$padded_num = str_pad(strval($raw_num), 3, '0', STR_PAD_LEFT);
				$this->_receipt = $date.$padded_num;	
			}
		}
		
		/**
		 * Get name of cashier who made the transaction
		 * @return string
		 */
		public function getCashier()
		{
			if(isset($this->_cashier)){
				return $this->_cashier;	
			}
		}
		
		/**
		 * Set name of cashier who made transaction
		 * @param object $session_obj An instance of session class
		 * @todo Use session ID to lookup user
		 */
		public function setCashier(Session $session_obj)
		{
			if($session_obj instanceof Session){
				$this->_cashier = User::findById($session_obj->userID)->getFullName();	
			}
		}	
		
		/**
		 * Record a sale transaction and update stock accordingly
		 * @param object $stock_obj An instance of the stock object
		 * @return boolean
		 */
		public function recordSale(Stock $stock_obj)
		{
			$db = Database::getInstance();
			$mysqli = $db->getConnection();
			// Adjust no. of units in existence
			$curr_units = $stock_obj->getUnits();
			$new_units = (int)$curr_units - (int)$this->_quantity;
			$stock_obj->setUnits($new_units);
			$stock_obj->setTotal();
			$mysqli->autocommit(false);
			if(!$stock_obj->save()){
				$mysqli->rollback();
				$mysqli->autocommit(true);
				return false;	
			}
			if(!$this->save()){
				$mysqli->rollback();
				$mysqli->autocommit(true);
				return false;	
			}
			if(!$mysqli->commit()){
				$mysqli->rollback();
				$mysqli->autocommit(true);
				return false;	
			}
			$mysqli->autocommit(true);
			return true;
		}
		
		/**
		 * Return sold item(s), increasing no. of items in stock by the 
		 * number returned while reducing the sale value of the transaction 
		 * by the number of items returned
		 * @param int $qty The no of items returned
		 * @return boolean
		 */
		public function returnSale($qty)
		{
			$db = Database::getInstance();
			$mysqli = $db->getConnection();
			$stock_id = (int)$this->getStockId();
			$stock = Stock::findById($stock_id);
			// Adjust no. of units in existence
			$curr_units = $stock->getUnits();
			$new_units = (int)$curr_units + (int)$qty;
			$stock->setUnits($new_units);
			// Recalculate Sale Value
			$this->_quantity = (int)$this->_quantity - (int)$qty;
			$this->setTotal();
			$mysqli->autocommit(false);
			if(!$stock->save()){
				$mysqli->rollback();
				$mysqli->autocommit(true);
				return false;	
			}
			if($qty == 1 || ((int)$this->_quantity == (int)$qty)){
				$deleted = $this->delete();
				if(!$deleted){
					$mysqli->rollback();
					$mysqli->autocommit(true);
					return false;	
				} else {
					unset($this);	
				}
			} else {
				if(!$this->save()){
					$mysqli->rollback();
					$mysqli->autocommit(true);
					return false;
				}
			}
			if(!$mysqli->commit()){
				$mysqli->rollback();
				$mysqli->autocommit(true);
				return false;	
			} else {
				$mysqli->autocommit(true);
				return true;
			}
		}
		
		/**
		 * Delete sale record and adjust stock upwards
		 * by the amount specified in the sale quantity
		 * @return boolean
		 */ 
		public function deleteSale()
		{
			$db = Database::getInstance();
			$mysqli = $db->getConnection();
			$stock_id = (int)$this->_stk_id;
			$stock = Stock::findById($stock_id);
			$num_units = (int)$this->_quantity;
			$curr_units = (int)$stock->getUnits();
			$new_stock_level = $curr_units + $num_units;
			$stock->setUnits($new_stock_level);
			$mysqli->autocommit(false);
			$stock->save();
			$this->delete();
			if(!$mysqli->commit()){
				$mysqli->rollback();
				$mysqli->autocommit(true);
				return false;
			}
			$mysqli->autocommit(true);
			return true;
		}
		
		/**
		 * Get the sales recorded during a particular date
		 * @param string $date 
		 * @return array An array of sale objects
		 */
		public static function findSalesForDate($date)
		{
			$db = Database::getInstance();
			$mysqli = $db->getConnection();
			$sql  = "SELECT * FROM ".static::$table_name." WHERE date LIKE '";
			$sql .= $mysqli->real_escape_string($date)."%'";
			return static::findBySql($sql);
		}
		
		/**
		 * Get records of sales transactions made during a certain period
		 * @param string $start
		 * @param sting  $end
		 * @return array An array of sale objects
		 */
		public static function findSalesForPeriod($start, $end)
		{
			$db = Database::getInstance();
			$mysqli = $db->getConnection();
			$sql  = "SELECT * FROM ".static::$table_name." WHERE date >= '";
			$sql .= $mysqli->real_escape_string($start)."' AND date <= '";
			$sql .= $mysqli->real_escape_string($end)."'";
			return static::findBySql($sql);
		}
		
		/**
		 * Calculate total of sales recorded during a particular day
		 * @param string $date
		 * @return int $total
		 */
		public static function calcGrossSaleForDate($date)
		{
			$sales = static::findSalesForDate($date);
			$num_sales = count($sales);
			$total = 0;
			$count = 0;
			while($count < $num_sales){
				$sale   = $sales[$count];
				$amount = $sale->getTotal();
				$amount = str_replace(",", "", $amount);
				$total += (int)$amount;
				$count++;	
			}
			$total = number_format($total);
			return $total; 
		}
		
		/**
		 * Calculate total discounts given during a particular day
		 * @param string $date
		 * @return int $total
		 */
		public static function calcTotalDiscountsForDate($date)
		{
			$sales = static::findSalesForDate($date);
			$num_sales = count($sales);
			$total = 0;
			$count = 0;
			while($count < $num_sales){
				$sale   = $sales[$count];
				$amount = $sale->getDiscount();
				$amount = str_replace(",", "", $amount);
				$total += (int)$amount;
				$count++;	
			}
			$total = number_format($total);
			return $total; 
		}
		
		/**
		 * Calculate net sale for a particular day after subtracting discount
		 * @param string $date 
		 * @return int $net_sale
		 */
		public static function calcNetSaleForDate($date)
		{
			$gross_sale = self::calcGrossSaleForDate($date);
			$gross_sale = (int)str_replace(',', '', $gross_sale);
			$total_discounts = self::calcTotalDiscountsForDate($date);
			$total_discounts = (int)str_replace(',', '', $total_discounts);
			$net_sale = $gross_sale - $total_discounts;
			return number_format($net_sale);
		}
		
		/**
		 * Calculate the gross amount of sales recorded during a particular period
		 * @param string $start Date string specifying start of the period
		 * @param string $end Date string specifying end of period
		 * @return int $total_sales 
		 */
		public static function calcGrossSaleForPeriod($start, $end)
		{
			$sales = static::findSalesForPeriod($start, $end);
			$num_sales = count($sales);
			$total = 0;
			$count = 0;
			while($count < $num_sales){
				$sale   = $sales[$count];
				$amount = $sale->getTotal();
				$amount = str_replace(",", "", $amount);
				$total += (int)$amount;
				$count++;	
			}
			$total = number_format($total);
			return $total; 
		}
		
		/**
		 * Calculate sum of all discounts given during a particular period
		 * @param string $start Date string specifying start of period
		 * @param string $end Date string specifying end of period
		 * @return int $total_discounts
		 */
		public static function calcTotalDiscountsForPeriod($start, $end)
		{
			$sales = static::findSalesForPeriod($start, $end);
			$num_sales = count($sales);
			$total = 0;
			$count = 0;
			while($count < $num_sales){
				$sale   = $sales[$count];
				$amount = $sale->getDiscount();
				$amount = str_replace(",", "", $amount);
				$total += (int)$amount;
				$count++;	
			}
			$total = number_format($total);
			return $total;
		}
		
		/**
		 * Calculate net sale for a particular period after subtracting 
		 * total discounts given during that period
		 * @param string $start Date string specifying start of the period
		 * @param string $end Date string specifying end of the period
		 * @return int $net_sale
		 */
		public static function calcNetSaleForPeriod($start, $end)
		{
			$gross_sale = self::calcGrossSaleForPeriod($start, $end);
			$gross_sale = (int)str_replace(',', '', $gross_sale);
			$total_discounts = self::calcTotalDiscountsForPeriod($start, $end);
			$total_discounts = (int)str_replace(',', '', $total_discounts);
			$net_sale = $gross_sale - $total_discounts;
			return number_format($net_sale);
		}
	}

?>