// JavaScript Document
$(function(){
		$('#item').blur(function(){
			var item_name = $(this).val();
			if(item_name == ""){
				$(this).addClass('warning');
			} else {
				$(this).removeClass('warning');
			}
		});	
		$('#item').change(function(){
			var item_name  = $(this).val();
			var no_units   = $('#units').val();
			var unit_price = $('#price').val();
			var discount   = $('#discount').val();
			if(item_name == ""){
				$(this).addClass('warning');
				var sale_value = calcSaleValue();
				$('#total').attr('value', sale_value);
				document.getElementById('post_sale').disabled = true	
			} else if(!isNaN(no_units) && !isNaN(unit_price) && !isNaN(discount)) {
				/*var elem = document.getElementById('price');
				if(elem.className == 'warning'){
					elem.className = "";	
				}*/
				$('#units, #price, #discount').removeClass('warning');
				$(this).removeClass('warning');
				var sale_value = calcSaleValue();
				$('#total').attr('value', sale_value);
				document.getElementById('post_sale').disabled = false;
			}
		});
		$('#units').blur(function(){
			var no_units = $(this).val();	
			if(isNaN(no_units) || no_units == ""){
				$(this).addClass('warning');
				document.getElementById('post_sale').disabled = true;	
			} else {
				$(this).removeClass('warning');
				var sale_value = calcSaleValue();
				$('#total').attr('value', sale_value);	
			}
		});
		$('#units, #price').keyup(function(){
			var no_units = $(this).val();
			var item_name = $('#item').val();
			if(isNaN(no_units)){
				$(this).addClass('warning');
				var disabled = document.getElementById('post_sale').disabled;
				if(!disabled){
					$('#post_sale').attr('disabled', true);
				}
			} else {
				if(item_name == ""){
					document.getElementById('post_sale').disabled = true	
				} else {
					document.getElementById('post_sale').disabled = false;
					$(this).removeClass('warning');	
					var sale_value = calcSaleValue();
					$('#total').attr('value', sale_value);
				}
			}	
		});
		$('#price').blur(function(){
			var unit_price = $(this).val();
			var item_name = $('#item').val();
			if(isNaN(unit_price) || unit_price == "" || item_name == ""){
				$(this).addClass('warning');
				document.getElementById('post_sale').disabled = true;	
			} else {
				$(this).removeClass('warning');	
				var sale_value = calcSaleValue();
				$('#total').attr('value', sale_value);
			}
		});
		$('#discount').keyup(function(){
			var discount = $(this).val();
			var item_name = $('#item').val();
			if(isNaN(discount) || discount == "" || item_name == ""){
				$(this).addClass('warning');
				document.getElementById('post_sale').disabled = true;	
			} else {
				$(this).removeClass('warning');
				//alert(item_name + ":" + typeof item_name + "\n" + no_units + ":" + typeof no_units + "\n" + unit_price + "\n");
				/*if(item_name != '' && typeof no_units == "number" && typeof unit_price == "number" && typeof discount == "number"){
					var sale_value = calcSaleValue(no_units, unit_price, discount);
					$('#total').attr('value', sale_value);
					$('#post_sale').attr('disabled', false);
				}*/
				var sale_value = calcSaleValue();
				$('#total').attr('value', sale_value);
				$('#post_sale').attr('disabled', false);
			}
		});
		
		/*$('#discount').blur(function(){
			var discount = $(this).val();
			var item_name = $('#item').val();
			if(isNaN(discount) || discount == "" || item_name == ""){
				$(this).addClass('warning');
				document.getElementById('post_sale').disabled = true;	
			} else {
				document.getElementById('post_sale').disabled = false;
				$(this).removeClass('warning');	
				var sale_value = calcSaleValue();
				$('#total').attr('value', sale_value);	
			}
		});*/
		
		function calcSaleValue(){
			var item_name  = $('#item').val();
			var no_units   = parseInt($('#units').val());
			var unit_price = parseFloat($('#price').val());
			var discount   = parseFloat($('#discount').val());
			if(item_name != ""){
				var subtotal = parseInt(no_units) * parseFloat(unit_price);
				var net_sale = subtotal - discount;
				return net_sale;
			} else {
				return NaN	
			}
		}
		
		// Highlight Navigation Location
		$('#main-nav a').each(function(i, elem){
			var sCurrHref = elem.href;
			if(document.location.href.indexOf(sCurrHref) == 0) {
				elem.className = 'active-link';
				elem.style.color = '#ffffff';	
			}	
		});
	}
);