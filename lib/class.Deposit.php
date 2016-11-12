<?php

	/**
	 * Class to handle deposit payments made by 
	 * customers for items in the store
	 *
	 * @author Luke <mugapedia@gmail.com>
	 * @date September 01, 2016
	 */
	class Deposit extends DatabaseObject	
	{
		protected static $table_name = "deposit";
		protected static $db_fields = array('id', 'cus_id', 'customer', 'phone', 'stk_id', 'stk_cat', 'item', 'color', 'size', 'quantity', 'unit_price', 'date', 'amount', 'sale_value', 'balance');
		public $id;
		protected $_cus_id;
		protected $_customer;
		protected $_phone;
		protected $_stk_id;
		protected $_stk_cat;
		protected $_item;
		protected $_color;
		protected $_size;
		protected $_quantity;
		protected $_unit_price;
		protected $_date;
		protected $_amount;
		protected $_balance;
		protected $_sale_value;
		
		/**
		 * Get the ID that identifies the customer who made the deposit
		 * @return int
		 */
		public function getCustomerId()
		{
			if(isset($this->_cus_id)){
				return $this->_cus_id;	
			}
		}
		
		/**
		 * Set ID that identifies customer who made deposit
		 * @param object $customer_obj Customer object
		 */
		public function setCustomerId(Customer $customer_obj)
		{
			if($customer_obj instanceof Customer){
				$this->_cus_id = $customer_obj->id;	
			}
		}
		
		/**
		 * Get name of customer who has made the deposit payment
		 * @return string
		 */
		public function getCustomerName()
		{
			if(isset($this->_customer)){
				return $this->_customer;	
			}
		}
		
		/**
		 * Set name of customer who made deposit payment
		 * @param object $customer_obj Instance of Customer class
		 */
		public function setCustomerName(Customer $customer_obj)
		{
			if($customer_obj instanceof Customer){
				$this->_customer = $customer_obj->getName();	
			}
		}
		
		/**
		 * Get phone number of customer who made deposit payment
		 * @return int
		 */
		public function getCustomerPhoneNo()
		{
			if(isset($this->_phone)){
				return $this->_phone;	
			}
		}
		
		/**
		 * Set phone number of customer who made deposit payment
		 * @param object $customer_obj Instance of Customer class
		 */
		public function setCustomerPhoneNo(Customer $customer_obj)
		{
			if($customer_obj instanceof Customer){
				$this->_phone = $customer_obj->getPhoneNumber();	
			}
		}
		
		/**
		 * Get ID that identifies the stock item for
		 * which the deposit payment is being made
		 * @return int $stock_id
		 */
		public function getStockId()
		{
			if(isset($this->_stk_id)){
				return $this->_stk_id;	
			}
		}
		
		/**
		 * Set ID that identifies the stock item for
		 * which the deposit payment is being made
		 * @param object $stock_obj Stock object
		 */
		public function setStockId(Stock $stock_obj)
		{
			if($stock_obj instanceof Stock){
				$this->_stk_id = $stock_obj->id;	
			}
		}
		
		/**
		 * Get stock category of the item for which 
		 * the deposit payment is being made
		 * @return string Name of stock category
		 */
		public function getStockCategory()
		{
			if(isset($this->_stk_cat)){
				return $this->_stk_cat;	
			}
		}
		
		/**
		 * Set stock category of the item for which the
		 * deposit payment is being made
		 * @param object $stock_obj Stock object
		 */
		public function setStockCategory(Stock $stock_obj)
		{
			$cat_id = (int)$stock_obj->getCatgoryId();
			$this->_stk_cat = Category::findById($cat_id)->getName();	
		}
		
		/**
		 * Get the stock code of the item for which
		 * the deposit payment is being made
		 */
		public function getItemCode()
		{
			if(isset($this->_item)){
				return $this->_item;	
			}
		}
		
		/**
		 * Set the stock code of the item for which
		 * the deposit payment is being made
		 * @param object $stock_obj Instance of Stock class
		 */
		public function setItemCode(Stock $stock_obj)
		{
			if($stock_obj instanceof Stock){
				$this->_item = $stock_obj->getCode();	
			}
		}
		
		/**
		 * Get the color of the stock item for which 
		 * the deposit payment was made
		 * @return string
		 */
		public function getItemColor()
		{
			if(isset($this->_color)){
				return $this->_color;	
			}
		}
		
		/**
		 * Set color of stock items for which the deposit
		 * payment is being made
		 * @param object $stock_obj Instance of Stock class
		 */
		public function setItemColor(Stock $stock_obj)
		{
			if($stock_obj instanceof Stock){
				$this->_color = $stock_obj->getColor();	
			}	
		}
		
		/**
		 * Get size of items for which deposit is being made
		 * @return int
		 */
		public function getItemSize()
		{
			if(isset($this->_size)){
				return $this->_size;	
			}
		}
		
		/**
		 * Set size of items for which deposit is being made
		 * @param object $stock_obj Instance of Stock Class
		 */
		public function setItemSize(Stock $stock_obj)
		{
			if($stock_obj instanceof Stock){
				$this->_size = $stock_obj->getSize();	
			}
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
		 * Get no. of items of a particular kind of stock item 
		 * for which the deposit payment is being made
		 * @return int
		 */
		public function getQuantity()
		{
			if(isset($this->_quantity)){
				return $this->_quantity;	
			}
		}
		
		/**
		 * Set no. of items of a particular kind of stock item
		 * for which the deposit is being made
		 * @param int $qty
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
		 * Get selling price per unit of the stock item
		 * for which the deposit payment is being made
		 * @return int/float
		 */
		public function getUnitPrice()
		{
			if(isset($this->_unit_price)){
				return $this->_unit_price;	
			}
		}
		
		/**
		 * Set selling price per unit of the stock item for
		 * which the deposit payment is being made
		 * @param int/float $price
		 */
		public function setUnitPrice($price)
		{
			if($this->_isValidUnitPrice($price)){
				$this->_unit_price = $price;	
			}
		}
		
		/**
		 * Get date deposit payment was made
		 * @return string
		 */
		public function getDate()
		{
			if(isset($this->_date)){
				return $this->_date;
			}
		}
		
		/**
		 * Set date deposit payment was made
		 * @param string $date
		 */
		public function setDate($date)
		{
			if($this->_isValidDate($date)){
				$this->_date = $date;	
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
		 * Get the amount of deposit payment made
		 * @return int
		 */
		public function getAmount()
		{
			if(isset($this->_amount)){
				return $this->_amount;	
			}
		}
		
		/**
		 * Set amount of deposit payment made
		 * @param int $amount
		 */
		public function setAmount($amount)
		{
			if($this->_isValidAmount($amount)){
				$this->_amount = $amount;	
			}
		}
		
		/**
		 * Test amount given to be a valid numeric, currency value
		 * @param int $amount Deposit amount
		 * @return boolean
		 */
		protected function _isValidAmount($amount)
		{
			$valid_amount = '/^[0-9]+(\.[0-9]{2})?$/';
			return preg_match($valid_amount, $amount) ? true : false;
		}
		
		/**
		 * Get the sale value of goods for which deposit is made
		 * @return int
		 */
		public function getSaleValue()
		{
			if(isset($this->_sale_value)){
				return $this->_sale_value;	
			}
		}
		
		/**
		 * Calculate the sale value of goods for which deposit is made
		 * @todo Set the _sale_value property of the class to product of 
		 *  number of units and price per unit
		 */
		public function setSaleValue()
		{
			$sale_value = intval($this->_quantity) * intval($this->_unit_price);
			$this->_sale_value = $sale_value;
		}
		
		/**
		 * Get outstanding amount needed to complete sale
		 * @return int 
		 */
		public function getBalance()
		{
			if(isset($this->_balance)){
				return $this->_balance;	
			}
		}
		
		/**
		 * Calculate the sale value of goods for which deposit
		 * is being made less deposit payment made
		 */
		public function setBalance()
		{
			$sale_value = $this->getSaleValue();
			$deposit = $this->getAmount();
			$balance = intval($sale_value) - intval($deposit);
			$this->_balance = $balance;
		}
		
		/**
		 * Add a new depsoit payment on the existing payment. If the increment
		 * equals balance owed by client, delete deposit entry and create new
		 * sale entry
		 * @todo Increase deposit and decrease balance accordingly
		 * @param int $amount The amount with which to increase the deposit
		 * @param object $session Session object that holds information about current session
		 * @return boolean
		 */
		public function updateDeposit($amount, Session $session)
		{
			$amount = (int)str_replace(',', '', $amount);
			if($amount < (int)$this->_balance){
				(int)$this->_amount += $amount;
				$new_balance = (int)$this->_sale_value - (int)$this->_amount;
				$this->_balance = $new_balance;
				$this->_date = strftime("%Y-%m-%d", time());
				return ($this->save()) ? true : false;
			} else {
				# Complete Sale
				$db = Database::getInstance();
				$mysqli = $db->getConnection();
				$sale = new Sale();
				$stock = Stock::findById($this->_stk_id);
				// Adjust no. of units in existence
				$curr_units = $stock->getUnits();
				$new_units = (int)$curr_units - (int)$this->_quantity;
				$stock->setUnits($new_units);
				$stock->setTotal();
				$sale->setStockId($stock);
				$sale->setStockCategory($stock);
				$sale->setItem($stock);
				$sale->setQuantity($this->_quantity);
				$sale->setUnitPrice($this->_unit_price);
				$sale->setTotal();
				$sale->setDate();
				$sale->generateReceiptNo();
				$sale->setCashier($session);	
				$mysqli->autocommit(false);
				$stock->save();
				$sale->recordSale($stock);
				$this->delete();
				if(!$mysqli->commit()){
					$mysqli->rollback();
					$mysqli->autocommit(true);
					return false;	
				}
				$mysqli->autocommit(true);
				return true;
			}
		}
		
		/**
		 * Get the deposit payments recorded during a particular date
		 * @param string $date Date string specifying particular day
		 * @return array An array of deposit objects
		 */
		public static function findDepositsForDate($date)
		{
			$db = Database::getInstance();
			$mysqli = $db->getConnection();
			$sql  = "SELECT * FROM ".static::$table_name." WHERE date LIKE '";
			$sql .= $mysqli->real_escape_string($date)."%'";
			return static::findBySql($sql);
		}
		
		/**
		 * Get records of deposit payments made during a certain period
		 * @param string $start Date string specifying start of period
		 * @param sting  $end Date string specifying end of period
		 * @return array An array of deposit objects
		 */
		public static function findDepositsForPeriod($start, $end)
		{
			$db = Database::getInstance();
			$mysqli = $db->getConnection();
			$sql  = "SELECT * FROM ".static::$table_name." WHERE date >= '";
			$sql .= $mysqli->real_escape_string($start)."' AND date <= '";
			$sql .= $mysqli->real_escape_string($end)."'";
			return static::findBySql($sql);
		}	
		
		/**
		 * Calculate total of deposit payments made during a particular day
		 * @param string $date Date string specifying day payments were made
		 * @return int $total Total of deposit payments for that day
		 */
		public static function calcDepositPaymentsForDate($date)
		{
			$deposits = static::findDepositsForDate($date);
			$num_deposits = count($deposits);
			$total = 0;
			$count = 0;
			while($count < $num_deposits){
				$dpt    = $deposits[$count];
				$amount = $dpt->getAmount();
				$amount = str_replace(",", "", $amount);
				$total += (int)$amount;
				$count++;	
			}
			$total = number_format($total);
			return $total; 
		}
		
		/**
		 * Calculate total of deposit payments made during a particular period
		 * @param string $start Date string specifying start of the period
		 * @param string $end Date string specifying end of the period
		 * @return int $total Total of deposit payments for that day
		 */
		public static function calcDepositPaymentsForPeriod($start, $end)
		{
			$deposits = static::findDepositsForPeriod($start, $end);
			$num_deposits = count($deposits);
			$total = 0;
			$count = 0;
			while($count < $num_deposits){
				$dpt    = $deposits[$count];
				$amount = $dpt->getAmount();
				$amount = str_replace(",", "", $amount);
				$total += (int)$amount;
				$count++;	
			}
			$total = number_format($total);
			return $total; 
		}
		
		/**
		 * Calculate the sum of all deposit payments made by customers
		 * to the business regardless of time
		 * @return int $total_deposits
		 */
		public static function calcSumOfAllDeposits()
		{
			$deposits = static::findAll();
			$num_deposits = count($deposits);
			$total = 0;
			$count = 0;
			while($count < $num_deposits){
				$dpt    = $deposits[$count];
				$amount = $dpt->getAmount();
				$amount = str_replace(",", "", $amount);
				$total += (int)$amount;
				$count++;	
			}
			$total = number_format($total);
			return $total;
		}
	}

?>