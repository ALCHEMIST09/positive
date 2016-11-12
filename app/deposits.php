<?php require_once('../lib/init.php'); ?>
<?php if(!$session->isLoggedIn()) { redirect_to("../index.php"); } ?>
<?php
	
	$deposits = Deposit::findAll();
	$total_deposits = Deposit::calcSumOfAllDeposits();

?>
<?php include_layout_template('admin_header.php'); ?>

	<div id="container">
    <h3>Actions</h3>
    <div id="side-bar">
    <?php
        $actions = array("view_sales" => "Sales", "add_sale" => "New Sale", "view_stock" => "Stock", "view_customers" => "Customers");
        echo create_action_links($actions);
    ?>
    </div>
    <div id="main-content">
    
    <a class="new-item" href="view_customers.php" title="record new deposit">New Deposit</a>
    
    <h2>Customer Deposits</h2>
    
    <?php 
		$mesg = $session->message();
		if(!empty($mesg)) { echo output_message($mesg); }
	?>
    
    <?php if(!empty($deposits)){ ?>
    <table cellpadding="8" class="bordered">
    <thead>
    <tr>
    	<th>Name of Customer</th>
        <th>Phone No.</th>
        <th>Code</th>
        <th>Color</th>
        <th>Size</th>
        <th>Qty</th>
        <th>Price/U</th>
        <th>Date</th>
        <th>Sale Value</th>
        <th>Deposit</th>
        <th>Balance</th>
        <th colspan="3">ACTIONS</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($deposits as $dpt): ?>
    <tr>
    	<td align="right"><?php echo $dpt->getCustomerName(); ?></td>
        <td align="right"><?php echo $dpt->getCustomerPhoneNo(); ?></td>
        <td align="right"><?php echo $dpt->getItemCode(); ?></td>
        <td align="right"><?php echo $dpt->getItemColor(); ?></td>
        <td align="right"><?php echo $dpt->getItemSize(); ?></td>
        <td align="right"><?php echo $dpt->getQuantity(); ?></td>
        <td align="right"><?php echo $dpt->getUnitPrice(); ?></td>
        <td align="right"><?php echo $dpt->getDate(); ?></td>
        <td align="right"><?php echo $dpt->getSaleValue(); ?></td>
        <td align="right"><?php echo $dpt->getAmount(); ?></td>
        <td align="right"><?php echo $dpt->getBalance(); ?></td>
        <td><a href="update_deposit.php?id=<?php echo $dpt->id; ?>" title="deposit increment"><strong>UD</strong></a></td>
        <td><a href="edit_deposit.php?id=<?php echo $dpt->id; ?>" title="edit deposit"><strong>ED</strong></a></td>
        <td><a class="del-deposit" href="delete_deposit.php?id=<?php echo $dpt->id; ?>" title="delete deposit payment"><strong>X</strong></a></td>
    </tr>
    <?php endforeach; ?>
    <tr>
    	<td colspan="10" align="right" class="total-field">TOTAL</td>
        <td align="right" class="total-field"><?php echo $total_deposits; ?></td>
    </tr>
    </tbody>
    </table>
    <?php } else {
		echo "<p>No customer deposits available at the moment</p>";	
	}
	?>
    
    </div> <!-- main-content -->
    </div>

<?php include_layout_template('admin_footer.php'); ?>