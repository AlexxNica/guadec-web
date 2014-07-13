/*calculate-total.js*/
/*Jquery file to calculate net amount during registration
  GUADEC 2014
  Author: Saumya Dwivedi */

function callTotalCalculate() {
	$.ajax({
	type: "POST",	
	url:"../wp-content/themes/guadec/js/calculate.php",
	data: {functionname: "updateTotal", arguments : [$("form input[value*='lunch_']:checked").size(), $('#arrive').val(), $('#depart').val(), $('input:radio[name=entry-fee]:checked').val(), $("[value=lunch]").prop("checked"), $("[value=accommodation]").prop("checked"), $("[value=room_type]").val() ]},
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
	url:"../wp-content/themes/guadec/js/calculate.php",
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
	url:"../wp-content/themes/guadec/js/calculate.php",
	data: {functionname : "updateAccomTotal", arguments : [$('#arrive').val(),$('#depart').val(), $("[value=accommodation]").prop("checked"), $("[value=room_type]").val()]},
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
		$("[name=arrival]").prop("disabled", false);
		$("[name=departure]").prop("disabled", false);
		$('[name=bday]').prop("disabled", false);
		$("[name=sponsored]").prop("checked", false);
		$(".box-options-accom").removeClass("disabled");
	}
	else {
		$("[name=arrival]").prop("disabled", true);
		$("[name=departure]").prop("disabled", true);
		$("[name=bday]").prop("disabled", true);
		$(".box-options-accom").addClass("disabled");
	}
}
function enableDisableS(obj) {
	if ($(obj).is(":checked")) {
		$('[name=accomodation]').prop("checked", false);
	}
}

function enableDisableL(obj) {
	if ($(obj).is(":checked")) {
		$("form input[value*='lunch_']").prop("disabled", false);
		$("[name=diet]").prop("disabled", false);
		$(".box-options-lunch").removeClass("disabled");
		
	}
	else {
		$("form input[value*='lunch_']").prop("disabled", true);
		$("form input[value*='lunch_']").prop("checked", false);
		$("[name=diet]").prop("disabled", true);
		$(".box-options-lunch").addClass("disabled");
		
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
	$("[value*='lunch_']").click(function(){
		callLunchCalculate();
	})
	

	/*Radio triggered event */
	$('input:radio[name=entry-fee]').click(function() {
	 	callTotalCalculate();
	});               
	
	$('#entry-arb').focusout(function(){
		if($('input:radio[id=entry-fee-arb]').is(':checked')){
	 		$('input:radio[id=entry-fee-arb]').prop('value', $('#entry-arb').val()); 
			callTotalCalculate();
		}
	});
	/* To force entry of numbers in money textbox */
	$('#entry-arb').on('change keyup', function() {
	  // Remove invalid characters
	  var sanitized = $(this).val().replace(/[^0-9]/g, '');
	  // Update value
	   $(this).val(sanitized);

	});
	//Enable Disable Accomodation and Lunch Selection
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
	$(document).ready(function() {
		enableDisableS(this);
		$("[name=sponsored]").click(function() {
			enableDisableS(this);
		});
	});
	// Enable Disable the submit button
	$('input[name=regsub]').attr('disabled','disabled');

     $('input[name="policy"]').click(function() {
        if($(this).is(':checked')) {
           $('input[name=regsub]').removeAttr('disabled');
        }
        else {
           $('input[name=regsub]').attr('disabled','disabled');
        }
     });

     //Enable the arbitary Amount Text Field
     $('#entry-arb').attr('disabled','disabled');
     $('[name=entry-fee]').change(function(){
     	if($('input:radio[id=entry-fee-arb]').is(':checked')) {
           $('#entry-arb').removeAttr('disabled');
        }
        else {
           $('#entry-arb').attr('disabled','disabled');
        }
     });

     /*Form Submit related checks */
     $('form[name=registration]').submit(function(event){
     	if($("[value=accommodation]").is(':checked')){
			if(!$("[name=contact_country]").val() || !$("[name=bday]").val()){
				alert("Make sure you enter the birth country, date of birth and agree to anti-harassment policy");
   				event.preventDefault();
			}
		}
		if(!$("[name=contact_name]").val() || !$("[name=contact_email]").val()) {
			alert("Make sure you enter your name and email", $('.accomfee').html());
   			event.preventDefault();
		}
		if($('.accomfee').html() == 'Incorrect dates'){
			alert("Make sure you enter correct accommodation dates");
   			event.preventDefault();	
		}
     });
     
});

