<?php

	require_once('../lib/init.php');
	if(!$session->isLoggedIn()) { redirect_to('../index.php'); }
	if(!$ac->hasPermission('delete_sale')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to('view_sales.php');
	}
	
	if(isset($_GET['id']) && !empty($_GET['id'])){
		$item_id = (int)$_GET['id'];
		if(!is_int($item_id)){
			$mesg = "Sale record could not be deleted. An invalid value was sent through the URL";
			$session->message($mesg);
			redirect_to('view_sales.php');	
		} else {
			$sale_record = Sale::findById($item_id);
			if($sale_record->deleteSale()){
				$mesg = "Sale record deleted";
				$session->message($mesg);
				redirect_to('view_sales.php');	
			} else {
				$mesg = "An error occured preventing the sale record from being deleted";
				$session->message($mesg);
				redirect_to('view_sales.php');	
			}
		}
	} 

?>