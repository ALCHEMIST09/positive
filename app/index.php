<?php include_once("../lib/init.php"); ?>
<?php if(!$session->isLoggedIn()) { redirect_to("../index.php"); } ?>

<?php include_layout_template('admin_header.php'); ?>
        
        <div id="container">
        <h3>Actions</h3>
        <div id="side-bar">
        <?php
            $actions = array("add_sale" => "New Sale", "view_sales" => "Sales", "view_stock" => "Stock", "view_customers" => "Customers", "deposits" => "Deposits");
            echo create_action_links($actions);
        ?>
        </div>
        <div id="main-content">
        
        <?php 
            $mesg = $session->message();
            if(!empty($mesg)) { echo output_message($mesg); }
        ?>
        
        </div> <!-- main-content -->
        </div>
        
<?php include_layout_template('admin_footer.php'); ?>