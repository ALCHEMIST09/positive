<?php

	require_once('../lib/init.php');
	if(!$session->isLoggedIn()) { redirect_to('../index.php'); }
	if(!$ac->hasPermission('delete_catgory')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to('categories.php');
	}
	
	if(isset($_GET['id']) && !empty($_GET['id'])){
		$cat_id = (int)$_GET['id'];
		if(!is_int($cat_id)){
			$mesg = "Stock category could not be deleted. An invalid value was sent through the URL";
			$session->message($mesg);
			redirect_to('categories.php');	
		} else {
			$category = Category::findById($cat_id);
			if($category->delete()){
				$mesg = "Category deleted";
				$session->message($mesg);
				redirect_to('categories.php');	
			} else {
				$mesg = "An error occured preventing the category from being deleted";
				$session->message($mesg);
				redirect_to('categories.php');	
			}
		}
	} 

?>