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
			$mesg = "Customer edit could not be performed. An invalid value was sent through the URL";
			$session->message($mesg);
			redirect_to('view_customers.php');	
		}
		$customer = Customer::findById($cst_id);
		# echo "<tt><pre>".var_dump($deposit)."</pre></tt>";
		if(is_null($customer)){
			$mesg = "Customer edit could not be performed. Details of deposit could not be found";
			$session->message($mesg);
			redirect_to('view_customers.php');	
		}
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////// PROCESS SUBMIT ////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////
	
	if(isset($_POST['submit'])){
		$name   = trim($_POST['name']);
		$phone  = trim($_POST['phone']);
		$required_fields = array($name, $phone);
		if(emptyFieldExists($required_fields)){
			$err = "Form fields marked with an asterix are required";	
		} else {
			$customer->setName($name);
			$customer->setPhoneNumber($phone);
			if($customer->save()){
				$session->message('Changes saved');
				redirect_to('view_customers.php');	
			} else {
				$err = "An error occured preventing the customer details from being edited. Please try again later";	
			}
		}
	}

?>
<?php include_layout_template('admin_header.php'); ?>

	<div id="container">
    <h3>Actions</h3>
    <div id="side-bar">
    <?php
        $actions = array("add_sale" => "New Sale", "view_sales" => "Sales", "deposits" => "Deposits");
        echo create_action_links($actions);
    ?>
    </div>
    <div id="main-content">
    
    <?php if(!empty($err)) { echo display_error($err); } ?>
    
    <h2>Edit Customer Details</h2>
    
    <form action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" method="post">
    <table cellpadding="8">
    <tr>
    	<td align="right"><label for="name">Name of Customer</label></td>
        <td><input type="text" name="name" value="<?php echo $customer->getName(); ?>" /></td>
    </tr>
    <tr>
    	<td align="right"><label for="phone">Phone
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="phone" id="phone" value="<?php echo $customer->getPhoneNumber(); ?>" /></td>
    </tr>
    <tr>
    	<td colspan="2" align="right">
        	<input type="submit" name="submit" value="Save Changes" />&nbsp;&nbsp;
            <a href="view_customers.php" title="cancel edit" style="text-decoration:none;"><input type="button" value="Cancel" /></a>
        </td>
    </tr>
    </table>
    </form>
    
    </div> <!-- main-content -->
    </div>

<?php include_layout_template('admin_footer.php'); ?>