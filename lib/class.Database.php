<?php

	/**
	 * Database access class
	 * Implements the Singleton design pattern 
	 * to reduce requests for new connections instances to database
	 * by allowing only one connection
	 *
	 * @author Luke <mugapedia@gmail.com>
	 * @date April 06, 2015
	 */
	class Database
	{
		
		const DB_SERVER = 'localhost';
		const DB_USER   = 'pos_admin';
		const DB_PASS   = 'store_operations123';
		const DB_NAME   = 'app_pos';
		
		// Holds the single instance of the database
		private static $_instance;
		
		// The database connection
		private $_connection;
		
		/**
		 * Constructor
		 * Set its visibility to private to prevent the creation of
		 * another instance using the new operator
		 */
		private function __construct()
		{
			$this->_connection = new MySQLi(self::DB_SERVER, self::DB_USER, self::DB_PASS, self::DB_NAME);
			if(mysqli_connect_error()){
				trigger_error('Database connection could not be established: '.mysqli_connect_error(), E_USER_ERROR);	
			}
		}
		
		/**
		 * Empty clone magic method to prevent duplication
		 * Set its visibility to private to prevent the creation 
		 * of another object using the clone operators
		 */ 
		private function __clone() {}
		
		/**
		 * Get an instance of the database
		 * @return Database
		 */
		public static function getInstance()
		{
			if(!self::$_instance){
				self::$_instance = new self();	
			}
			return self::$_instance;
		}
		
		/**
		 * Get a connection to the database
		 */
		public function getConnection()
		{
			return $this->_connection;
		}
	}

?>