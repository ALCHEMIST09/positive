<?php require_once('../lib/init.php'); ?>
<?php 
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('delete_deposit')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
?>
<?php

	if(isset($_GET['id']) && !empty($_GET['id'])){
		$dpt_id = (int)$_GET['id'];
		if(!is_int($dpt_id)){
			$session->message('Deposit could not be deleted. An invalid value was sent through the URL');
			redirect_to('deposits.php');	
		} else {
			$deposit = Deposit::findById($dpt_id);
			if(is_null($deposit)){
				$session->message('Deposit could not be deleted. Corresponding details could not be found');
				redirect_to('deposits.php');	
			} else {
				if($deposit->delete()){
					$session->message('Deposit deleted');
					redirect_to('deposits.php');	
				} else {
					$session->message('An error occured preventing the deposit from being deleted');
					redirect_to('deposits.php');	
				}
			}
		}
	}

?>