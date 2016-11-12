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

	if(isset($_POST['submit'])){
		$cat_name = trim($_POST['name_of_stock']);
		$created  = trim($_POST['created']);
		if(empty($cat_name) || empty($created)){
			$err = "Form fields marked with an asterix are required";	
		} else {
			$stock_cat = new Category();
			$cat_name = $stock_cat->formatName($cat_name);
			if($stock_cat->categoryExists($cat_name)){
				$err = "Stock category already in system";
			} else {
				// Process $_POST
				$stock_cat->setName($cat_name);
				$stock_cat->setDateCreated($created);	
				if($stock_cat->save()){
					$mesg = "Stock category created";
					$session->message($mesg);
					redirect_to('categories.php');	
				} else  {
					$err = "An error occured preveting the stock category from being created";	
				}
			}
		}
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
    
    <?php 
        $mesg = $session->message();
        if(!empty($mesg)) { echo output_message($mesg); }
    ?>
    
    <h2>New Stock Category</h2>
    
    <?php if(!empty($err)) { echo display_error($err); } ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <table cellpadding="6">
    <tr>
    	<td><label for="name_of_stock">Name of Stock Category
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="name_of_stock" id="name_of_stock" /></td>
    </tr>
    <tr>
        <td><label for="created">Date Created
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="created" id="created" /></td>
    </tr>
    <tr>
    	<td colspan="2" align="right">
        	<input type="submit" name="submit" value="Submit" />
        </td>
    </tr>
    </table>
    </form>
    
    </div> <!-- main-content -->
    </div>

<?php include_layout_template('admin_footer.php'); ?>