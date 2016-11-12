<?php require_once("../lib/init.php"); ?>
<?php 
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('add_sale')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
?>
<?php
	if(isset($_POST['submit'])){
		// echo "<tt><pre>.".var_dump($_POST)."</pre></tt>";
		$stock_id    = trim($_POST['item']);
		$no_of_units = trim($_POST['units']);
		$unit_price  = trim($_POST['price']);
		$discount    = trim($_POST['discount']);
		$total_sale  = trim($_POST['total']);
		$required_fields = array($stock_id, $no_of_units, $unit_price, $total_sale);
		
		$stock = Stock::findById($stock_id);
		if(emptyFieldExists($required_fields)){
			$err = "Form fields marked with an asterix are required";	
		} elseif(intval($no_of_units) > intval($stock->getUnits())){
			$err  = "Quantity specified exceeds the amount in stock. Only ";
			$err .= $stock->getUnits()." units are remaining in stock";	
		} else {
			$sale = new Sale();
			$sale->setStockId($stock);
			$sale->setStockCategory($stock);
			$sale->setItem($stock);
			$sale->setQuantity($no_of_units);
			$sale->setUnitPrice($unit_price);
			$sale->setDiscount($discount);
			$sale->setTotal();
			$sale->setDate();
			$sale->generateReceiptNo();
			$sale->setCashier($session);
			// echo "<tt><pre>".var_dump($sale)."</pre></tt>";
			if($sale->recordSale($stock)){
				$_POST = array();
				$mesg = "Sale Posted";
				$session->message($mesg);
				redirect_to("view_sales.php");	
			} else {
				$err = "An error occured preventing the sale from being posted.";	
			}
		}
	}
?>
<?php include_layout_template("admin_header.php"); ?>

	<div id="container">
    <h3>Actions</h3>
    <div id="side-bar">
    <?php
        $actions = array("add_sale" => "New Sale", "view_sales" => "Sales", "view_stock" => "Stock");
        echo create_action_links($actions);
    ?>
    </div>
    <div id="main-content">
    
    <h2>Record New Sale</h2>
    
    <?php if(!empty($err)) { echo display_error($err); } ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="new_sale">
    <table cellpadding="6">
    <tr>
    	<td><label for="item">Item<span class="required-field">*</span></label></td>
        <td>
        	<?php Stock::getInstance()->displayItems(true); ?>
            <!-- <select name="item" id="item">
            	<option value="" selected="selected">Select Item</option>
                <option value="SL-9">SL-9</option>
            </select> -->
        </td>
        <td><label for="units">No. of Units<span class="required-field">*</span></label></td>
        <td><input type="text" name="units" id="units" value="1" /></td>
        <td><label for="price">Price/Unit<span class="required-field">*</span></label></td>
        <td><input type="text" name="price" id="price" /></td>
        <td><label for="discount">Discount<span class="required-field">*</span></label></td>
        <td><input type="text" name="discount" id="discount" value="0" /></td>
    </tr>
    </table>
    <br />
    <table style="margin-left: 490px;">
    <tr>
    	<td colspan="5" align="right"><label for="total"><span style="font-size:24px; font-weight:bold;">TOTAL&nbsp;&nbsp;</span></label></td>
        <td><input type="text" name="total" id="total" readonly="readonly" style="background:#D7D7D7;" /></td>
    </tr>
    <tr>
    	<td colspan="8" align="right"><input type="submit" name="submit" id="post_sale" value="Post Sale" disabled /></td>
    </tr>
    </table>    
    </form>
    
    </div> <!-- main-content -->
    </div>

<?php include_layout_template("admin_footer.php"); ?>