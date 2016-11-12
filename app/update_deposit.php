<?php require_once('../lib/init.php'); ?>
<?php 
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('update_deposit')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
?>
<?php

	if(isset($_GET['id']) && !empty($_GET['id'])){
		$dpt_id = (int)$_GET['id'];
		if(!is_int($dpt_id)){
			$mesg = "Deposit update could not be performed. An invalid value was sent through the URL";
			$session->message($mesg);
			redirect_to('deposits.php');	
		}
		$deposit = Deposit::findById($dpt_id);
		if(is_null($deposit)){
			$mesg = "Deposit update could not be performed. Details of deposit could not be found";
			$session->message($mesg);
			redirect_to('deposits.php');	
		}
	}
	
	////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////// PROCESS SUBMIT /////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	
	if(isset($_POST['submit'])){
		$increment = trim($_POST['increment']);
		if(empty($increment)){
			$err = "Please specify the amount with which to increment the deposit";	
		} else if($increment > (int)$deposit->getBalance()){
			$err = "Increment exceeds amount owed by client";	
		} else {
			$balance = $deposit->getBalance();
			if($deposit->updateDeposit($increment, $session)){
				if(intval($increment) == intval($balance)){
					$session->message('New sale from clearance of deposit');
					redirect_to('view_sales.php');	
				} else {
					$session->message('Deposit amount updated');	
					redirect_to('deposits.php');
				}
			} else {
				$err = "An error occured preventing the deposit from being recorded";	
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
    
    <a class="new-item" href="add_deposit.php" title="record new deposit">New Deposit</a>
    
    <?php if(!empty($err)) { echo display_error($err); } ?>
    
    <h2>Update Deposit Payment</h2>
    
    <form action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" method="post">
    <table cellpadding="8">
    <tr>
    	<td align="right"><label for="curr_amount">Current Amount</label></td>
        <td><input type="text" name="curr_amount" value="<?php echo $deposit->getAmount(); ?>" readonly="readonly" style="background:#D7D7D7;" /></td>
    </tr>
    <tr>
    	<td align="right"><label for="increment">Increment
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="increment" id="increment" /></td>
    </tr>
    <tr>
    	<td colspan="2" align="right">
        	<input type="submit" name="submit" value="Update Deposit" />
        </td>
    </tr>
    </table>
    </form>
    
    </div> <!-- main-content -->
    </div>

<?php include_layout_template('admin_footer.php'); ?>