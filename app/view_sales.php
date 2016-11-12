<?php require_once("../lib/init.php"); ?>
<?php if(!$session->isLoggedIn()) { redirect_to("../index.php"); } ?>
<?php
	$date = strftime("%Y-%m-%d", time());
	$sales = Sale::findSalesForDate($date);
	
	$gross_sale = Sale::calcGrossSaleForDate($date);
	$total_discounts = Sale::calcTotalDiscountsForDate($date);
	$net_sale = Sale::calcNetSaleForDate($date);
	
	if(isset($_GET['submit'])){
		$date = trim($_GET['date']);	
		if(empty($date)){
			$err = "Choose date for which to show sales";	
		} elseif(!isValidDate($date)){
			$err = "The date entered does not seem to be a valid date";	
		} else {
			$sales = Sale::findSalesForDate($date);	
			$gross_sale = Sale::calcGrossSaleForDate($date);
			$total_discounts = Sale::calcTotalDiscountsForDate($date);
			$net_sale = Sale::calcNetSaleForDate($date);
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
    
    <a class="new-item" href="add_sale.php" title="record new sale">Add Stock</a>
    
    <h2>Sales for <?php echo get_formated_date($date); ?></h2>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
    <table cellpadding="7">
    <tr>
    	<td><label for="date">Choose Date<span class="required-field">*</span></label></td>
        <td><input type="text" name="date" id="date" /></td>
        <td><input type="submit" name="submit" value="Search" /></td>
    </tr>
    </table>
    </form>
    
    <?php 
		$mesg = $session->message();
		if(!empty($mesg)) { echo output_message($mesg); }
	?>
    
    <?php if(!empty($sales)) { ?>
    <table cellpadding="7" class="bordered">
    <thead>
    <tr>
    	<th align="right">Stock Category</th>
        <th align="right">Code</th>
        <th align="right">Color</th>
        <th align="right">Size</th>
        <th align="right">No. of Units</th>
        <th align="right">Price/Unit(Ksh)</th>
        <th align="right">Discount</th>
        <th align="right">Net Sale</th>
        <th align="right">Receipt</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($sales as $transaction): ?>
    <tr>
    	<td align="right"><?php echo $transaction->getStockCategory(); ?></td>
    	<td align="right"><?php echo $transaction->getItem(); ?></td>
        <td align="right"><?php echo $transaction->getStockColor(); ?></td>
        <td align="right"><?php echo $transaction->getStockSize(); ?></td>
        <td align="right"><?php echo $transaction->getQuantity(); ?></td>
        <td align="right"><?php echo $transaction->getUnitPrice(); ?></td>
        <td align="right"><?php echo $transaction->getDiscount(); ?></td>
        <td align="right"><?php echo $transaction->getTotal(); ?></td>
        <td align="right"><?php echo $transaction->getReceiptNo(); ?></td>
        <td align="right"><a class="edit-symbol" href="edit_sale.php?id=<?php echo $transaction->id; ?>" title="edit sale">Edit</a></td>
        <td align="right"><a class="edit-symbol del-sale" href="delete_sale.php?id=<?php echo $transaction->id; ?>" title="delete sale">X</a></td>
        <td align="right"><a class="edit-symbol rtn-sale" href="sale_return.php?id=<?php echo $transaction->id; ?>" title="return this sale">RTN</a></td>
    </tr>
    <?php endforeach; ?>
    <tr>
    	<td colspan="7">Gross Sales</td>
    	<td align="right"><?php echo $gross_sale; ?></td>
    </tr>
    <tr>
    <tr>
    	<td colspan="7">Total Discounts</td>	
        <td align="right"><?php echo $total_discounts; ?></td>
    </tr>
    	<td colspan="7">Net Sale</td>
        <td align="right"><?php echo $net_sale; ?></td>
    </tr>
    </tbody>
    </table>
    <?php } else {
		echo "<p>No sales have been recorded today yet</p>";	
	} 
	?>

	</div> <!-- main-content -->
    </div>

<?php include_layout_template("admin_footer.php"); ?>