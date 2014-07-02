/*calculate-total.js*/
/*Jquery file to calculate net amount during registration
  GUADEC 2014
  Author: Saumya Dwivedi */

function callTotalCalculate() {
	$.ajax({
	type: "POST",	
	url:"wp-content/themes/guadec/js/calculate.php",
	data: {functionname: "updateTotal", arguments : [$("form input[value*='lunch_']:checked").size(), $('#arrive').val(), $('#depart').val(), $('input:radio[name=entry-fee]:checked').val(), $("[value=lunch]").prop("checked"), $("[value=accommodation]").prop("checked"), $("[value=sponsored]").prop("checked") ]},
	success:function(obj, status){
		result = new String(obj);
		result = result.trim();
       if(result != 'error' ) {
        	$(".total").html(result);
        	$('[name=tfee]').prop('value', result);
        }
        else {
            console.log(result);
   		}          
	}
	});
}

function callLunchCalculate() {
	$.ajax({
	type: "POST",	
	url:"wp-content/themes/guadec/js/calculate.php",
	data: {functionname : "updateLunchTotal", arguments : [$("form input[value*='lunch_']:checked").size(),  $("[value=lunch]").prop("checked")]},
	success:function(obj, status){
		result = new String(obj);
		result = result.trim();
        if(result != 'error' ) {
	    	$(".lunchfee").html(result);
	    	$('[name=lfee]').prop('value', result);
        }
        else {
            console.log(result);
   		}          
	}
	});
	callTotalCalculate();
}

function callAccomCalculate() {
	$.ajax({
	type: "POST",	
	url:"wp-content/themes/guadec/js/calculate.php",
	data: {functionname : "updateAccomTotal", arguments : [$('#arrive').val(),$('#depart').val(), $("[value=accommodation]").prop("checked"), $("[value=sponsored]").prop("checked")]},
	success:function(obj, status){
		result = new String(obj);
		result = result.trim();
        if(result != 'error' ) {
        	$(".accomfee").html(result);
        	$('[name=afee]').prop('value', result);
        }
        else {
            console.log(result);
   		}          
	}
	});	
	callTotalCalculate();
}
function enableDisableA(obj) {
	if ($(obj).is(":checked")) {
		$("#arrive").prop("disabled", false);
		$("#depart").prop("disabled", false);
	}
	else {
		$("#arrive").prop("disabled", true);
		$("#depart").prop("disabled", true);
	}
}

function enableDisableL(obj) {
	if ($(obj).is(":checked")) {
		$("form input[value*='lunch_']").prop("disabled", false);
		
	}
	else {
		$("form input[value*='lunch_']").prop("disabled", true);
		
	}
}
$(function() {
	
	/*Dropdown triggered event*/
	$('#arrive').on('change' , function(){
		callAccomCalculate();
	})
	$('#depart').on('change' , function(){
		callAccomCalculate();
	})

	 /* Checkboxes call events */
	$("[value=lunch]").change(function(){
		callLunchCalculate();
	})
	$("[value=accommodation]").change(function(){
		callAccomCalculate();
	})
	$("[value=sponsored]").change(function(){
		callAccomCalculate();
	})
	$("[value*='lunch_']").click(function(){
		callLunchCalculate();
	})
	

	/*Radio triggered event */
	$('input:radio[name=entry-fee]').click(function() {
	 	callTotalCalculate();
	})
	
	$('#entry-arb').focusout(function(){
		if($('input:radio[id=entry-fee-arb]').is(':checked')){
	 		$('input:radio[id=entry-fee-arb]').prop('value', $('#entry-arb').val()); 
			callTotalCalculate();
		}
	})
	/* To force entry of numbers in money textbox */
	$('#entry-arb').on('change keyup', function() {
	  // Remove invalid characters
	  var sanitized = $(this).val().replace(/[^0-9]/g, '');
	  // Update value
	   $(this).val(sanitized);

	});
	$(document).ready(function() {
		enableDisableA(this);
		$("[name=accommodation]").click(function() {
			enableDisableA(this);	
		});
	});
	$(document).ready(function() {
		enableDisableL(this);
		$("[name=lunch]").click(function() {
			enableDisableL(this);	
		});
	});
});

