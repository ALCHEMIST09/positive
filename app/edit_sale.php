<?php require_once("../lib/init.php"); ?>
<?php 
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('edit_sale')){
		$mesg = "Unauthorized Permission";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
?>
<?php

	if(isset($_GET['id']) && !empty($_GET['id'])){
		$sale_id = (int)$_GET['id'];
		if(!is_int($sale_id)){
			$session->message("Sale record edit failed. An invalid value was sent throught the URL");
			redirect_to("view_sales.php");	
		} else {
			$sale = Sale::findById($sale_id);
		}
	}
	
	/////////////////////////////////////////////////////////////////
	///////////////////////// PROCESS SUBMIT ////////////////////////
	/////////////////////////////////////////////////////////////////
	
	if(isset($_POST['submit'])){
		$no_of_units   = trim($_POST['units']);
		$unit_price    = trim($_POST['unit_price']);
		$discount      = trim($_POST['discount']);
		$required_fields = array($no_of_units, $unit_price);
		
		if(emptyFieldExists($required_fields)){
			$err = "Form fields marked with an asterix are required";  
		} else {
			$sale->setQuantity($no_of_units);
			$sale->setUnitPrice($unit_price);
			if(!empty($discount)) { $sale->setDiscount($discount); }
			$sale->setTotal();
			if($sale->save()){
				$session->message('Changes Saved');
				$_POST = array();
				redirect_to("view_sales.php");	
			} else {
				$err = "An error occured preventing the sale record from being edited";	
			}
		}
	} else {
		// Form not submitted
		$err = "";	
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
    
    <?php if(!empty($err)) { echo display_error($err); } ?>
    
    <h2>Edit Sale Details</h2>
    
    <?php if(!empty($err)) { echo display_error($err); } ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" method="post">
    <table cellpadding="6">
    <tr>
        <td><label for="units">No. of Units
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="units" id="units" value="<?php echo $sale->getQuantity(); ?>" /></td>
    </tr>
    <tr>
        <td><label for="unit_price">Unit Cost Price(Ksh)
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="unit_price" id="unit_price" value="<?php echo $sale->getUnitPrice(); ?>" /></td>
    </tr>
    <tr>
        <td><label for="discount">Discount(Ksh)
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="discount" id="discount" value="<?php echo $sale->getDiscount(); ?>" /></td>
    </tr>
    <tr>
    	<td colspan="2" align="right">
        	<input type="submit" name="submit" value="Save Changes" />
            <a class="btn-cancel" href="view_sales.php"><input type="button" value="Cancel" /></a>
        </td>
    </tr>
    </table>    
    </form>

	</div> <!-- main-content -->
    </div>

<?php include_layout_template('admin_footer.php'); ?>