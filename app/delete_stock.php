<?php

	require_once('../lib/init.php');
	if(!$session->isLoggedIn()) { redirect_to('../index.php'); }
	if(!$ac->hasPermission('delete_stock')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to('view_stock.php');
	}
	
	if(isset($_GET['id']) && !empty($_GET['id'])){
		$item_id = (int)$_GET['id'];
		if(!is_int($item_id)){
			$mesg = "Stock item could not be deleted. An invalid value was sent through the URL";
			$session->message($mesg);
			redirect_to('view_stock.php');	
		} else {
			$item = Stock::findById($item_id);
			if($item->delete()){
				$mesg = "Stock item deleted";
				$session->message($mesg);
				redirect_to('view_stock.php');	
			} else {
				$mesg = "An error occured preventing the stock item from being deleted";
				$session->message($mesg);
				redirect_to('view_stock.php');	
			}
		}
	} 

?>