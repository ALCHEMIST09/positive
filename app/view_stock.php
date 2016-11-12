<?php require_once("../lib/init.php"); ?>
<?php 
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('view_stock')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
?>
<?php
	$stock_items = Stock::findAll();
?>
<?php include_layout_template("admin_header.php"); ?>

	<div id="container">
    <h3>Actions</h3>
    <div id="side-bar">
    <?php
        $actions = array("add_sale" => "New Sale", "view_sales" => "Sales", "deposits" => "Deposits", "view_stock" => "Stock");
        echo create_action_links($actions);
    ?>
    </div>
    <div id="main-content">
    
    <a class="new-item" href="choose_cat.php" title="add stock item">Add Stock</a>
    
    <h2>Stock Items</h2>
    
    <?php 
		$mesg = $session->message();
		if(!empty($mesg)) { echo output_message($mesg); }
	?>
    
    <?php if(!empty($stock_items)){ ?>
    <table cellpadding="7" class="bordered">
    <thead>
    <tr>
    	<th align="right">Stock Category</th>
        <th align="right">Code</th>
        <th align="right">Color</th>
        <th align="right">Size</th>
        <th align="right">No. of Units</th>
        <th align="right">Date Added</th>
        <th align="right">Last Updated</th>
        <th align="right">Unit Price</th>
        <th align="right">Total Cost</th>
    </tr>
    <tbody>
    <?php foreach($stock_items as $item): ?>
    <tr>
    	<td align="right"><?php echo $item->getCategoryName(); ?></td>
    	<td align="right"><?php echo $item->getCode(); ?></td>
        <td align="right"><?php echo $item->getColor(); ?></td>
        <td align="right"><?php echo $item->getSize(); ?></td>
        <td align="right"><?php echo $item->getUnits(); ?></td>
        <td align="right"><?php echo get_formated_date($item->getDateCreated()); ?></td>
        <td align="right"><?php echo get_formated_date($item->getDateUpdated()); ?></td>
        <td align="right"><?php echo $item->getBuyPrice(); ?></td>
        <td align="right"><?php echo $item->getTotal(); ?></td>
        <td><a class="edit-symbol" href="add_stock_quantity.php?id=<?php echo $item->id; ?>" title="add more units">+</a></td>
        <td><a class="edit-symbol" href="edit_stock.php?id=<?php echo $item->id; ?>" title="update item details">Edit</a></td>
        <td><a class="edit-symbol del-stock" href="delete_stock.php?id=<?php echo $item->id; ?>" title="delete item">X</a></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
    </thead>
    </table>
    <?php } else {
		echo "<p>No stock items are available at the moment</p>";	
	}
	?>
    
    </div> <!-- main-content -->
    </div>

<?php include_layout_template("admin_footer.php"); ?>