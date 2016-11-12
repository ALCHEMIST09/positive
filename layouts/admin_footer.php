    <div id="footer">
	<?php
          $session = $GLOBALS['session'];
          if($session->isLoggedIn()) { echo "<p>".$session->sessionVar('user'); }
          echo "&nbsp;";
          echo "<a href=\"logout.php\" title=\"sign out\">Sign Out</a></p>";
    ?>
    </div> <!-- footer -->
</div> <!-- wrapper -->
	<!-- JAVASCRIPTS -->
    <script type="text/javascript" src="../js/ux.js"></script>
    <script type="text/javascript">
		$(function() {
			$("#created, #left, #start_date, #end_date, #date").datepicker({
				dateFormat : "yy-mm-dd",	
				changeMonth: true,
				changeYear: true
			});
			
			$("a.del-user, a.del-cat, a.del-stock, a.del-sale, a.rtn-sale, a.del-deposit, a.del-customer").click(
				function(evt){
					var className = $(this).attr("class");
					if(className == "del-user"){
						var confirmDelete = confirm("Are you sure you want to delete this user?");
						if(!confirmDelete){
							return false;	
						}	
					}
					if(className == "del-cat"){
						var confirmDelete = confirm("Deleting this category will delete all stock items under it. Are you sure you want to proceed?");
						if(!confirmDelete){
							return false;	
						}	
					}
					if(className.indexOf('del-stock') >= 1){
						var confirmDelete = confirm("Are you sure you want to delete this stock items?");
						if(!confirmDelete){
							return false;	
						}	
					}
					if(className.indexOf('del-sale') >= 1){
						var confirmDelete = confirm("Are you sure you want to delete this sale record?");
						if(!confirmDelete){
							return false;	
						}	
					}
					if(className.indexOf('rtn-sale') >= 1){
						var confirmDelete = confirm("Are you sure you want to return this sale?");
						if(!confirmDelete){
							return false;	
						}	
					}
					if(className.indexOf('del-deposit') >= 0){
						var confirmDelete = confirm("Are you sure you want to delete this deposit payment?");
						if(!confirmDelete){
							return false;	
						}	
					}
					if(className.indexOf('del-customer') >= 0){
						var confirmDelete = confirm("Are you sure you want to delete this customer?");
						if(!confirmDelete){
							return false;	
						}	
					}
				}
			);
		});
		
		function printWithCss()
		{
			// Get the HTML pf div
			var title = document.title;
			var divElements = document.getElementById('outerHTML').innerHTML;
			var printWindow = window.open('report', '_blank', 'left=0, top=0, scrollbars=1');	
			// Open the window
			printWindow.document.open();
			// Write the HTML to the new window, link to CSS file
			printWindow.document.write('<html><head><title>' + title + '</title><link rel="stylesheet" type="text/css" href="../css/print.css" media="print" /></head><body>');
			//printWindow.document.styleSheets = '../css/print.css';
			printWindow.document.write(divElements);
			printWindow.document.write('</body></html>');
			printWindow.document.close();
			printWindow.focus();
			printWindow.print();
			printWindow.close();
		}
		
	</script>

</body>
</html>