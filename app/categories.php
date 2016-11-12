<?php require_once("../lib/init.php"); ?>
<?php 
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('add_category')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
?>
<?php
	$categories = Category::findAll();
?>
<?php include_layout_template('admin_header.php'); ?>

	<div id="container">
    <h3>Actions</h3>
    <div id="side-bar">
    <?php
        $actions = array("add_sale" => "New Sale", "view_sales" => "Sales", "choose_cat" => "Add Stock", "view_stock" => "Stock");
        echo create_action_links($actions);
    ?>
    </div>
    <div id="main-content">
    
    <a class="new-item" href="add_cat.php" title="add stock category">Add Category</a>
    
    <?php 
		$mesg = $session->message();
		if(!empty($mesg)) { echo output_message($mesg); }
	?>
    
    <h2>Stock Categories</h2>
    
    <?php if(!empty($categories)){ ?>
    <table cellpadding="7" class="bordered">
    <thead>
    <tr>
    	<th align="right">Name of Category</th>
        <th align="right">Date Created</th>
    </tr>
    <tbody>
    <?php foreach($categories as $cat): ?>
    <tr>
    	<td align="right"><?php echo $cat->getName(); ?></td>
        <td align="right"><?php echo $cat->getDateCreated(); ?></td>
        <td><a href="edit_category.php?id=<?php echo $cat->id; ?>">Edit</a></td>
        <td><a class="del-cat" href="delete_cat.php?id=<?php echo $cat->id; ?>">X</a></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
    </thead>
    </table>
    <?php } else {
		echo "<p>No stock cateogries created yet</p>";	
	} 
	?>
    
    </div> <!-- main-content -->
    </div>

<?php include_layout_template('admin_footer.php'); ?>