<?php require_once("../lib/init.php"); ?>
<?php 
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('add_deposit')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
?>
<?php
	
	if(isset($_GET['id'])){
		$cust_id = intval($_GET['id']);
		$customer = Customer::findById($cust_id);
		if(!is_int($cust_id) || is_null($customer)){
			$session->message('Customer could not be found');
			redirect_to($_SERVER['HTTP_REFERER']);	
		} else {
			$customer_obj_string = $session->serializeObject($customer);
			$session->sessionVar('customer_obj', $customer_obj_string);	
		}
	}
	
	////////////////////////////////////////////////////////////////////
	////////////////////////// PROCESS SUBMIT //////////////////////////
	////////////////////////////////////////////////////////////////////
	
	if(isset($_POST['submit'])){
		$stock_id   = trim($_POST['item']);
		$qty        = trim($_POST['qty']);
		$unit_price = trim($_POST['unit_price']);
		$amount     = trim($_POST['deposit']);
		$date       = trim($_POST['date']);
		$required_fields = array($stock_id, $qty, $unit_price, $amount, $date);
		if(emptyFieldExists($required_fields)){
			$err = "Form fields marked with an asterix are required";	
		} else {
			$stock = Stock::findById($stock_id);
			if(intval($qty) > intval($stock->getUnits())){
				$err  = "Quantity specified exceeds the amount in stock. Only ";
				$err .= $stock->getUnits()." units are remaining in stock";
			} else {
				$customer_obj_string = $session->sessionVar('customer_obj');
				$customer = $session->unSerializeObject($customer_obj_string);			
				$deposit = new Deposit();
				$deposit->setCustomerId($customer);
				$deposit->setCustomerName($customer);
				$deposit->setCustomerPhoneNo($customer);
				$deposit->setStockId($stock);
				$deposit->setStockCategory($stock);
				$deposit->setItemCode($stock);
				$deposit->setItemColor($stock);
				$deposit->setItemSize($stock);
				$deposit->setQuantity($qty);
				$deposit->setUnitPrice($unit_price);
				$deposit->setDate($date);
				$deposit->setAmount($amount);
				$deposit->setSaleValue();
				$deposit->setBalance();
				//echo "<tt><pre>".var_dump($deposit)."</pre></tt>";
				if($deposit->save()){
					$session->message('Deposit recorded');
					redirect_to('deposits.php');	
				} else {
					$err = "An error occured preventing the deposit payment from being recorded. Please try again later";	
				}
			}
		}
	}
	
?>
<?php include_layout_template('admin_header.php'); ?>

	<div id="container">
    <h3>Actions</h3>
    <div id="side-bar">
    <?php
        $actions = array("add_sale" => "New Sale", "view_sales" => "Sales", "categories" => "Categories", "view_stock" => "Stock");
        echo create_action_links($actions);
    ?>
    </div>
    <div id="main-content">
    
    <h2>New Deposit</h2>
    
    <?php if(!empty($err)) { echo display_error($err); } ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <table cellpadding="7">
    <tr>
    	<td align="right"><label for="item">Item
        <span class="required-field">*</span></label></td>
        <td><?php Stock::getInstance()->displayItems(); ?></td>
    </tr>
    <tr>
    	<td align="right"><label for="qty">Quantity
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="qty" id="qty" /></td>
    </tr>
    <tr>
    	<td align="right"><label for="unit_price">Price/Unit
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="unit_price" id="unit_price" /></td>
    </tr>
    <tr>
    	<td align="right"><label for="deposit">Deposit Amount
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="deposit" id="deposit" /></td>
    </tr>
    <tr>
    	<td align="right"><label for="date">Date
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="date" id="date" /></td>
    </tr>
    <tr>
    	<td align="right" colspan="2">
        	<input type="submit" name="submit" value="Submit" />
        </td>
    </tr>
    </table>
    </form>
    
    </div> <!-- main-content -->
    </div>

<?php include_layout_template('admin_footer.php'); ?>