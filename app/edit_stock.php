<?php require_once("../lib/init.php"); ?>
<?php 
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('edit_stock')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
?>
<?php

	if(isset($_GET['id']) && !empty($_GET['id'])){
		$item_id = (int)$_GET['id'];
		if(!is_int($item_id)){
			$session->message("Stock edit failed. An invalid value was sent throught the URL");
			redirect_to("view_stock.php");	
		} else {
			$item = Stock::findById($item_id);
		}
	}
	
	/////////////////////////////////////////////////////////////////
	///////////////////////// PROCESS SUBMIT ////////////////////////
	/////////////////////////////////////////////////////////////////
	
	if(isset($_POST['submit'])){
		$stock_code    = trim($_POST['stock_code']);
		$size          = trim($_POST['size']);
		$color         = trim($_POST['color']);
		$no_of_units   = trim($_POST['units']);
		$created       = trim($_POST['created']);
		$unit_price    = trim($_POST['unit_price']);
		$required_fields = array($stock_code, $no_of_units, $size, $color, $created, $unit_price);
		
		if(emptyFieldExists($required_fields)){
			$err = "Form fields marked with an asterix are required";  
		} else {
			$item->setCode($stock_code);
			$item->setColor($color);
			$item->setSize($size);
			$item->setUnits($no_of_units);
			$item->setDateCreated($created);
			$item->setBuyPrice($unit_price);
			$item->setTotal();
			if($item->save()){
				$mesg = "Changes Saved";
				$session->message($mesg);
				$session->destroySessionVar('cat_id');
				$_POST = array();
				redirect_to("view_stock.php");	
			} else {
				$err = "An error occured preventing the stock item from being edited";	
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
        $actions = array("add_sale" => "New Sale", "view_sales" => "Sales", "categories" => "Categories", "view_stock" => "Stock");
        echo create_action_links($actions);
    ?>
    </div>
    <div id="main-content">
    
    <h2>Edit Stock Details</h2>
    
    <?php if(!empty($err)) { echo display_error($err); } ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" method="post">
    <table cellpadding="6">
    <tr>
    	<td><label for="stock_code">Code of Item(s)
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="stock_code" id="stock_code" value="<?php echo $item->getCode(); ?>" /></td>
    </tr>
    <tr>
    	<td><label for="size">Size
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="size" id="size" value="<?php echo $item->getSize(); ?>" /></td>
    </tr>
    <tr>
    	<td><label for="color">Color
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="color" id="color" value="<?php echo $item->getColor(); ?>" /></td>
    </tr>
    <tr>
        <td><label for="units">No. of Units
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="units" id="units" value="<?php echo $item->getUnits(); ?>" /></td>
    </tr>
    <tr>
        <td><label for="created">Date Added (yy/mm/dd)
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="created" id="created" value="<?php echo $item->getDateCreated(); ?>" /></td>
    </tr>
    <tr>
        <td><label for="unit_price">Unit Cost Price(Ksh)
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="unit_price" id="unit_price" value="<?php echo $item->getBuyPrice(); ?>" /></td>
    </tr>
    <tr>
    	<td colspan="2" align="right">
        	<input type="submit" name="submit" value="Save Changes" />
            <a class="btn-cancel" href="view_stock.php"><input type="button" value="Cancel" /></a>
        </td>
    </tr>
    </table>    
    </form>
    
    </div> <!-- main-content -->
    </div>

<?php include_layout_template('admin_footer.php'); ?>