<?php require_once("../lib/init.php"); ?>
<?php 
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('add_stock')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
?>
<?php
	$categories = Category::findAll();
	
	///////////////////////////////////////////////////////////////////
	////////////////////////// PROCESS SUBMIT /////////////////////////
	///////////////////////////////////////////////////////////////////
	
	if(isset($_POST['submit'])){
		$category_id = $_POST['stock_category'];
		if(empty($category_id)){
			$err = "Choose stock category";	
		} else {
			$session->sessionVar('cat_id', $category_id);
			redirect_to('add_stock.php');	
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
    
    <h2>Choose Stock Category</h2>
    
    <?php if(!empty($err)) { echo display_error($err); } ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <table cellpadding="6">
    <tr>
    	<td><label for="stock_category">Name of Stock Category
        <span class="required-field">*</span></label></td>
        <td>
        	<select name="stock_category" id="stock_category">
            	<option value="" selected="selected">Choose Category</option>
                <?php foreach($categories as $cat): ?>
                	<option value="<?php echo $cat->id; ?>"><?php echo $cat->getName(); ?></option>
                <?php endforeach; ?>
            </select>
        </td>
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