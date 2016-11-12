<?php require_once("../lib/init.php"); ?>
<?php 
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('view_reports')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
?>
<?php

	//$users = User::findAll();
	
	///////////////////////////////////////////////////////////////////////////
	/////////////////////////////// PROCESS LOOKUP ////////////////////////////
	///////////////////////////////////////////////////////////////////////////
	
	if(isset($_GET['refresh'])){
		//$user_id  = $_GET['user'];
		$date     = $_GET['date'];
		$start    = $_GET['start_date'];
		$end      = $_GET['end_date'];
		
		$logger = Logger::getInstance();
		
		// Check for errors
		if(empty($date) && empty($start) && empty($end)){
			$err = "Choose date or period for which to show sales report";	
		} elseif(!empty($date) && (!empty($start) || !empty($end))){
			$err  = "You cannot select both a date and a period from which to show sales report";
		} elseif((empty($date) && !empty($start) && empty($end)) || 
		         (empty($date) && empty($start) && !empty($end)))
	    {
			$err  = "When specifying a range, you must specify both the start and end dates ";
			$err .= "of the range";
		}
		
		// Display Logs According to entered parameters
		# Sales For Date
		if(!empty($date)){
			$sales = Sale::findSalesForDate($date);	
			$gross_sale = Sale::calcGrossSaleForDate($date);
			$total_discounts = Sale::calcTotalDiscountsForDate($date);
			$net_sale = Sale::calcNetSaleForDate($date);
		}
		
		# Sales For Period
		if(!empty($start) && !empty($end)){
			$sales = Sale::findSalesForPeriod($start, $end);
			$gross_sale = Sale::calcGrossSaleForPeriod($start, $end);
			$total_discounts = Sale::calcTotalDiscountsForPeriod($start, $end);
			$net_sale = Sale::calcNetSaleForPeriod($start, $end);
		}
		
	} else {
		// Form not submitted
		$sales = "";
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
    
    <h2>Sales Report</h2>
    
    <?php if(!empty($err)) { echo display_error($err); } ?>
    <?php
		$mesg = $session->message();
		echo output_message($mesg);
	?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
    <table cellpadding="5" cols="4">
    <tr>
    	<td><label for="date"><p>For Date
        <span class="required-field">*</span></p></label></td>
        <td><input type="text" name="date" id="date" /></td>
    </tr>
    <tr>
    	<td><p>For Period</td>
        <td><p>Specify Date Range from Fields Below</td>
    </tr>
    <tr>
    	<td><label for="start_date"><p>Start Date
        <span class="required-field">*</span></p></label></td>
    	<td><input type="text" name="start_date" id="start_date" /></td>
        <td><label for="end_date"><p>End Date
        <span class="required-field">*</span></p></label></td>
    	<td><input type="text" name="end_date" id="end_date" /></td>
    </tr>
    <tr>
    	<td colspan="2" align="right">
        	<input type="submit" name="refresh" value="Show Sales" />
        </td>
    </tr>
    </table>
    </form>
    
    <?php 
		if(!empty($start) && !empty($end)){
			$timestamp_01 = strtotime($start);
			$timestamp_02 = strtotime($end);
			$textual_date_01 = strftime("%B %d, %Y", $timestamp_01);
			$textual_date_02 = strftime("%B %d, %Y", $timestamp_02);
			echo "<h2 style=\"margin-bottom:20px;\">Sales Report From {$textual_date_01} to {$textual_date_02}</h2>";
		}
	?>
    
    <?php if(!empty($sales)){ ?>
    <table cellpadding="7" class="bordered">
    <thead>
    <tr>
    	<th align="right">Stock Category</th>
        <th align="right">Code</th>
        <th align="right">Color</th>
        <th align="right">No. of Units</th>
        <th align="right">Price/Unit(Ksh)</th>
        <th align="right">Discount</th>
        <th align="right">Net Sale</th>
        <th align="right">Receipt</th>
        <th align="right">Cashier</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($sales as $transaction): ?>
    <tr>
    	<td align="right"><?php echo $transaction->getStockCategory(); ?></td>
    	<td align="right"><?php echo $transaction->getItem(); ?></td>
        <td align="right"><?php echo $transaction->getStockColor(); ?></td>
        <td align="right"><?php echo $transaction->getQuantity(); ?></td>
        <td align="right"><?php echo $transaction->getUnitPrice(); ?></td>
        <td align="right"><?php echo $transaction->getDiscount(); ?></td>
        <td align="right"><?php echo $transaction->getTotal(); ?></td>
        <td align="right"><?php echo $transaction->getReceiptNo(); ?></td>
        <td align="right"><?php echo $transaction->getCashier(); ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
    	<td colspan="6" class="total-field" align="right">GROSS SALE</td>
        <td class="total-field" align="right"><?php echo $gross_sale; ?></td>
    </tr>
    <tr>
    	<td colspan="6" class="total-field" align="right">TOTAL DISCOUNTS</td>
        <td class="total-field" align="right"><?php echo $total_discounts; ?></td>
    </tr>
    <tr>
    	<td colspan="6" class="total-field" align="right">NET SALE</td>
        <td class="total-field" align="right"><?php echo $net_sale; ?></td>
    </tr>
    </tbody>
    </table>
    <?php } elseif(empty($sales) && isset($_GET['refresh'])) { 
			$mesg = "<p>No sales were recorded during that period</p>";
			echo output_message($mesg);
		}
	?>

	</div> <!-- main-content -->
    </div>

<?php include_layout_template("admin_footer.php"); ?>