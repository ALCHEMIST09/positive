<?php require_once("../lib/init.php"); ?>
<?php 
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('add_stock')){
		$mesg = "You don't have permission to access this page";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
?>
<?php
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
			$stock = new Stock();
			$cat_id = $session->sessionVar('cat_id');
			$stock->setCategoryId($cat_id);
			$stock->setCode($stock_code);
			$stock->setColor($color);
			$stock->setSize($size);
			$stock->setUnits($no_of_units);
			$stock->setDateCreated($created);
			$stock->setBuyPrice($unit_price);
			$stock->setTotal();
			if($stock->save()){
				$mesg = "Stock entry recorded";
				$session->message($mesg);
				$session->destroySessionVar('cat_id');
				$_POST = array();
				redirect_to("view_stock.php");	
			} else {
				$err = "An error occured preventing the stock entry from being recorded";	
			}
		}
	}
?>
<?php include_layout_template("admin_header.php"); ?>

	<div id="container">
    <h3>Actions</h3>
    <div id="side-bar">
    <?php
        $actions = array("add_sale" => "New Sale", "view_sales" => "Sales", "categories" => "Categories", "view_stock" => "Stock");
        echo create_action_links($actions);
    ?>
    </div>
    <div id="main-content">
    
    <?php 
        $mesg = $session->message();
        if(!empty($mesg)) { echo output_message($mesg); }
    ?>
    
    <h2>New Stock Entry</h2>
    
    <?php if(!empty($err)) { echo display_error($err); } ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <table cellpadding="6">
    <tr>
    	<td><label for="stock_code">Code of Item(s)
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="stock_code" id="stock_code" /></td>
    </tr>
    <tr>
    	<td><label for="size">Size
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="size" id="size" /></td>
    </tr>
    <tr>
    	<td><label for="color">Color
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="color" id="color" /></td>
    </tr>
    <tr>
        <td><label for="units">No. of Units
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="units" id="units" /></td>
    </tr>
    <tr>
        <td><label for="created">Date Added
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="created" id="created" /></td>
    </tr>
    <tr>
        <td><label for="unit_price">Unit Cost Price(Ksh)
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="unit_price" id="unit_price" /></td>
    </tr>
    <tr>
    	<td colspan="2" align="right">
        	<input type="submit" name="submit" value="Add Stock" />
        </td>
    </tr>
    </table>    
    </form>
    
    </div> <!-- main-content -->
    </div>

<?php include_layout_template("admin_footer.php"); ?>