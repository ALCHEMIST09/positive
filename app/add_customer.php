<?php require_once('../lib/init.php'); ?>
<?php
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('add_customer')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
?>
<?php
	
	if(isset($_POST['submit'])){
		$name  = trim($_POST['name']);
		$phone = trim($_POST['phone']);	
		if(empty($name) || empty($phone)){
			$err = "Form fields marked with an asterix are requried";	
		} else {
			$customer = new Customer();
			$customer->setName($name);
			$customer->setPhoneNumber($phone);
			if($customer->save()){
				$session->message('Customer details saved');
				redirect_to('view_customers.php');	
			} else {
				$err = "An error occured preventing customer details from being saved. Please try again later";	
			}	
		}
	}
	
?>
<?php include_layout_template('admin_header.php'); ?>

	<div id="container">
    <h3>Actions</h3>
    <div id="side-bar">
    <?php
        $actions = array("add_sale" => "New Sale", "view_sales" => "Sales", "categories" => "Categories", "view_customers" => "Customers");
        echo create_action_links($actions);
    ?>
    </div>
    <div id="main-content">
    
    <h2>New Customer</h2>
    
    <?php if(!empty($err)) { echo display_error($err); } ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <table cellpadding="7">
    <tr>
    	<td align="right"><label for="name">Name
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="name" id="name" /></td>
    </tr>
    <tr>
    	<td align="right"><label for="phone">Phone Number
        <span class="required-field">*</span></td>
        <td><input type="text" name="phone" id="phone" /></td>
    </tr>
    <tr>
    	<td colspan="2" align="right"><input type="submit" name="submit" value="Add Customer" /></td>
    </tr>
    </table>
    </form>
    
    </div> <!-- main-content -->
    </div>

<?php include_layout_template('admin_footer.php'); ?>