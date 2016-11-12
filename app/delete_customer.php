<?php require_once('../lib/init.php'); ?>
<?php 
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('edit_customer')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
?>
<?php

	if(isset($_GET['id']) && !empty($_GET['id'])){
		$cst_id = (int)$_GET['id'];
		if(!is_int($cst_id)){
			$mesg = "Customer could not be deleted. An invalid value was sent through the URL";
			$session->message($mesg);
			redirect_to('view_customers.php');	
		}
		$customer = Customer::findById($cst_id);

		if(is_null($customer)){
			$mesg = "Customer could not be deleted. Details of deposit could not be found";
			$session->message($mesg);
			redirect_to('view_customers.php');	
		}
		
		if($customer->delete()){
			$session->message('Customer deleted');
			redirect_to('view_customers.php');	
		} else {
			$session->message('An error occured preventing the customer from being deleted. Please try again later');
			redirect_to('view_customers.php');	
		}
	}
	
?>