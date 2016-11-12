<?php require_once("../lib/init.php"); ?>
<?php 
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('edit_category')){
		$mesg = "You don't have permission to access this page";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
?>
<?php

	if(isset($_GET['id']) && !empty($_GET['id'])){
		$cat_id = (int)$_GET['id'];
		if(!is_int($cat_id)){
			$session->message("Stock category edit failed. An invalid value was sent throught the URL");
			redirect_to("categories.php");	
		} else {
			$category = Category::findById($cat_id);	
		}
	}
	
	/////////////////////////////////////////////////////////////////
	///////////////////////// PROCESS SUBMIT ////////////////////////
	/////////////////////////////////////////////////////////////////
	
	if(isset($_POST['submit'])){
		$cat_name = $_POST['name_of_stock'];
		$created  = $_POST['created'];
		
		if(empty($cat_name) || empty($created)){
			$err = "Form fields marked with an asterix are required";  
		} else {
			$cat_name = $category->formatName($cat_name);
			$category->setName($cat_name);
			$category->setDateCreated($created);			
			if($category->save()){
				$session->message("Changes Saved");
				redirect_to("categories.php");	
			} else {
				$err = "An error occured preventing the changes from being saved";	
			}
		}
	} else {
		// Form not submitted
		$err = "";	
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

	<h2>Edit Stock Category</h2>
    
    <?php if(!empty($err)) { echo display_error($err); } ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" method="post">
    <table cellpadding="6">
    <tr>
    	<td><label for="name_of_stock">Name of Stock Category
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="name_of_stock" id="name_of_stock" value="<?php echo $category->getName(); ?>" /></td>
    </tr>
    <tr>
        <td><label for="created">Date Created (yy/mm/dd)
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="created" id="created" value="<?php echo $category->getDateCreated(); ?>" /></td>
    </tr>
    <tr>
    	<td colspan="2" align="right">
        	<input type="submit" name="submit" value="Save Changes" />
        </td>
    </tr>
    </table>
    </form>
    
    </div> <!-- main-content -->
    </div>

<?php include_layout_template('admin_footer.php'); ?>