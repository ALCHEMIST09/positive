<?php require_once("../lib/init.php"); ?>
<?php 
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('return_sale')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
?>
<?php

	if(isset($_GET['id']) && !empty($_GET['id'])){
		$sale_id = (int)$_GET['id'];
		if(!is_int($sale_id)){
			$session->message('Sale return failed. An invalid value was sent through the URL');
			redirect_to('view_sales.php');	
		} else {
			$sale = Sale::findById($sale_id);
		}
	}
	
	/////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////// PROCESS SUBMIT /////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////
	
	if(isset($_POST['submit'])){
		$qty = trim($_POST['qty']);
		if(empty($qty)){
			$err = "Form fields marked with an asterix are required";	
		} else if($qty > (int)$sale->getQuantity()){
			$err = "Quantity entered exceeds number of items purchased";	
		} else {
			if($sale->returnSale($qty)){
				$session->message('Sale return posted');
				redirect_to('view_sales.php');	
			} else {
				$session->message('An error prevented the sale return from being posted');
				redirect_to('view_sales.php');	
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
    
    <h2>Return of Sold Items</h2>
    
    <?php if(!empty($err)) { echo display_error($err); } ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" method="post">
    <table cellpadding="6">
    <tr>
    	<td align="right"><label for="qty">Quantity
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="qty" id="qty" /></td>
    </tr>
    <tr>
    	<td colspan="2" align="right">
        	<input type="submit" name="submit" value="Post Return" />
        </td>
    </tr>
    </table>
    </form>

	</div> <!-- main-content -->
    </div>

<?php include_layout_template('admin_footer.php'); ?>