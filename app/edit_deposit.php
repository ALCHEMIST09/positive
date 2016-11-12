<?php require_once('../lib/init.php'); ?>
<?php 
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('edit_deposit')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
?>
<?php

	if(isset($_GET['id']) && !empty($_GET['id'])){
		$dpt_id = (int)$_GET['id'];
		if(!is_int($dpt_id)){
			$mesg = "Deposit edit could not be performed. An invalid value was sent through the URL";
			$session->message($mesg);
			redirect_to('deposits.php');	
		}
		$deposit = Deposit::findById($dpt_id);
		# echo "<tt><pre>".var_dump($deposit)."</pre></tt>";
		if(is_null($deposit)){
			$mesg = "Deposit edit could not be performed. Details of deposit could not be found";
			$session->message($mesg);
			redirect_to('deposits.php');	
		}
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////// PROCESS SUBMIT ////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////
	
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
			# echo "<tt><pre>".var_dump($deposit)."</pre></tt>";
			if($deposit->save()){
				$session->message('Deposit edit performed');
				redirect_to('deposits.php');	
			} else {
				$err = "An error occured preventing the deposit payment from being edited. Please try again later";	
			}
		}
	}

?>
<?php include_layout_template('admin_header.php'); ?>

	<div id="container">
    <h3>Actions</h3>
    <div id="side-bar">
    <?php
        $actions = array("add_sale" => "New Sale", "view_sales" => "Sales", "view_stock" => "Stock");
        echo create_action_links($actions);
    ?>
    </div>
    <div id="main-content">
    
    <h2>Edit Deposit</h2>
    
    <?php if(!empty($err)) { echo display_error($err); } ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" method="post">
    <table cellpadding="7">
    <tr>
    	<td align="right"><label for="item">Item
        <span class="required-field">*</span></label></td>
        <td><?php Stock::getInstance()->displayItems(false, $deposit->getStockId()); ?></td>
    </tr>
    <tr>
    	<td align="right"><label for="qty">Quantity
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="qty" id="qty" value="<?php echo $deposit->getQuantity(); ?>" /></td>
    </tr>
    <tr>
    	<td align="right"><label for="unit_price">Price/Unit
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="unit_price" id="unit_price" value="<?php echo $deposit->getUnitPrice(); ?>" /></td>
    </tr>
    <tr>
    	<td align="right"><label for="deposit">Deposit Amount
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="deposit" id="deposit" value="<?php echo $deposit->getAmount(); ?>" /></td>
    </tr>
    <tr>
    	<td align="right"><label for="date">Date
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="date" id="date" value="<?php echo $deposit->getDate(); ?>" /></td>
    </tr>
    <tr>
    	<td align="right" colspan="2">
        	<input type="submit" name="submit" value="Save Changes" />&nbsp;&nbsp;
            <a href="deposits.php" title="cancel edit" style="text-decoration:none;"><input type="button" value="Cancel" /></a>
        </td>
    </tr>
    </table>
    </form>
    
    </div> <!-- main-content -->
    </div>

<?php include_layout_template('admin_footer.php'); ?>