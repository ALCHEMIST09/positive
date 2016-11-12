<?php require_once("../lib/init.php"); ?>
<?php 
	if(!$session->isLoggedIn()) { redirect_to("../index.php"); }
	if(!$ac->hasPermission('add_stock')){
		$mesg = "Unauthorized Operation";
		$session->message($mesg);
		redirect_to($_SERVER['HTTP_REFERER']);
	}
	
	if(isset($_GET['id']) && !empty($_GET['id'])){
		$stock_id = (int)$_GET['id'];
		if(!is_int($stock_id)){
			$mesg = "Stock items could not be added. An invalid value was sent through the URL";
			$session->message($mesg);
			redirect_to('view_stock.php');	
		} else {
			$stock = Stock::findById($stock_id);	
		}
	}
	
	////////////////////////////////////////////////////////////////////////////
	///////////////////////////// PROCESS SUBMIT ///////////////////////////////
	////////////////////////////////////////////////////////////////////////////
	
	if(isset($_POST['submit'])){
		$no_of_units = trim($_POST['units']);
		$required_fields = array($no_of_units);	
		if(emptyFieldExists($required_fields)){
			$err = "Form fields marked with an aserix are requierd";	
		} else {
			$stock->addUnits($no_of_units);
			$stock->setDateUpdated();
			$stock->setTotal();
			if($stock->save()){
				$session->message('Changes Saved');
				redirect_to('view_stock.php');	
			} else {
				$err = "An error occured preventing the chages from being saved";	
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
    
    <h2>Add Units of Stock Item</h2>
    
    <?php if(!empty($err)) { echo display_error($err); } ?>
    
    <form action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" method="post">
    <table cellpadding="6">
    <tr>
    	<td><label for="units">No. of Items
        <span class="required-field">*</span></label></td>
        <td><input type="text" name="units" id="units" /></td>
    </tr>
    <tr>
    	<td colspan="2" align="right">
        	<input type="submit" name="submit" value="Add" />&nbsp;&nbsp;
            <a class="btn-cancel" href="view_stock.php"><input type="button" value="Cancel" /></a>
        </td>
    </tr>
    </table>
    </form>
    
    </div> <!-- main-content -->
    </div>

<?php include_layout_template('admin_footer.php'); ?>