<?php require_once("../lib/init.php"); ?>
<?php if(!$session->isLoggedIn()) { redirect_to("../index.php"); } ?>
<?php
	$customers = Customer::findAll();
?>
<?php include_layout_template('admin_header.php'); ?>

	<div id="container">
    <h3>Actions</h3>
    <div id="side-bar">
    <?php
        $actions = array("add_customer" => "New Customer", "view_sales" => "Sales", "deposits" => "Deposits", "view_stock" => "Stock");
        echo create_action_links($actions);
    ?>
    </div>
    <div id="main-content">
    
    <a class="new-item" href="add_customer.php" title="add new customer">Add Customer</a>
    
    <h2>Customers</h2>
    
    <?php 
		$mesg = $session->message();
		if(!empty($mesg)) { echo output_message($mesg); }
	?>
    
    <?php if(!empty($customers)){ ?>
    <table cellpadding="8" class="bordered">
    <thead>
   	<tr>
    	<th>Name of Customer</th>
        <th>Phone Number</th>
        <th colspan="3">ACTIONS</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($customers as $cst): ?>
    <tr>
    	<td align="right"><?php echo $cst->getName(); ?></td>
        <td align="right"><?php echo $cst->getPhoneNumber(); ?></td>
        <td align="center"><a href="add_deposit.php?id=<?php echo $cst->id; ?>" title="record deposit from customer">DEPOSIT</a></td>
        <td align="center"><a href="edit_customer.php?id=<?php echo $cst->id; ?>" title="edit customer details">EDIT</a></td>
        <td align="center"><a class="del-customer" href="delete_customer.php?id=<?php echo $cst->id; ?>" title="delete customer"><strong>X</strong></a></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
    <?php } else {
		echo "<p>No customers recorded yet</p>";
	}
	?>
    
    </div> <!-- main-content -->
    </div>

<?php include_layout_template('admin_footer.php'); ?>