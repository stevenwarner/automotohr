<!--  -->
<div class="row" style="min-height: 700px;">
 	<div class="col-sm-4 col-sm-offset-4">
 		<!-- Loader -->
		<div class="text-center cs-inner-loader js-inner-loader">
			<i class="fa fa-spinner fa-spin"></i>
		</div>
 		<!--  -->
 		<div id="js-phone-page" class="cs-page">
	 		<h4>Update the phone number</h4>
	 		<hr />
	 		<form action="javascript:void(0)" method="POST" role="form" id="js-phonenumber-form">
	 			<div class="form-group">
	 				<label>Phone Number <span class="cs-required">*</span></label>
	 				<div class="input-group">
	 					<div class="input-group-addon">+1</div>
	 					<input type="text" class="form-control" id="js-phonenumber" placeholder="(XXX) XXX-XXXX" />
	 				</div>
	 				<span class="cs-error" id="js-phone-page-span"></span>
	 			</div>
	 			<button type="submit" class="btn btn-success">Send Verification Code</button>
	 		</form>
 		</div>
 		<!--  -->
 		<div id="js-verification-page" style="display: none;" class="cs-page">
 			<h4>Verify your phone number.</h4>
	 		<hr />
	 		<form action="javascript:void(0)" method="POST" role="form" id="js-phonenumber-form">
	 			<div class="form-group">
	 				<label>Verification Code <span class="cs-required">*</span></label>
	 				<input type="text" class="form-control" id="js-verification-code" />
	 				<span class="cs-error" id="js-verification-code-page-span"></span>
	 			</div>
	 			<button type="submit" class="btn btn-success js-verify-code">Verify</button>
	 			<button type="submit" class="btn btn-default js-back">Resend</button>
	 		</form>
 		</div>
 		<!--  -->
 		<div id="js-thankyou-page" style="display: none;" class="cs-page">
 			<h4>Phone number verified!</h4>
	 		<hr />
	 		<p>Your phone number <span class="js-phonnumber-show-span"></span> has been updated and verified.</p>
 		</div>
 	</div>
</div>

<style>
	.cs-required, .cs-error{ font-weight: bolder; color: #cc0000; }
	/*Inner loader*/
	.cs-inner-loader{ position: absolute; top: 0; bottom: 0; left: 0; right: 0; background: rgba(255,255,255,.5); z-index: 2; font-size: 30px; }
	.cs-inner-loader i{ position: relative; top: 50%; margin-top: -30px; }
	/**/
	.cs-page{
		margin-top: 50px;
		-webkit-box-shadow: 0px 0px 1px 1px #cccccc; 
		-moz-box-shadow: 0px 0px 1px 1px #cccccc; 
		-o-box-shadow: 0px 0px 1px 1px #cccccc; 
		box-shadow: 0px 0px 1px 1px #cccccc; 
		padding: 10px; 
		padding-bottom: 30px; 
		background-color: #eeeeee;  
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		-o-border-radius: 3px;
		border-radius: 3px;
	}
	.cs-page hr{ color: #cccccc; border-color: #cccccc; background-color: #cccccc; }
	/*.cs-page h4{ back }*/
</style>

<script>

	$(function(){
		var megaOBJ = {};
		megaOBJ.id = "<?=$event_array['id'];?>";
		megaOBJ.companyId = "<?=$event_array['cid'];?>";
		megaOBJ.type = "<?=$event_array['type'];?>";

		$('#js-phonenumber').keyup(validatePhonenumber);
		$('#js-phonenumber-form').submit(formHandler);
		$('.js-verify-code').click(verifyCode);
		//
		$('.js-back').click(function(e){
			e.preventDefault();
			$('#js-verification-page').fadeOut(0);
			$('#js-phone-page').fadeIn(300);
			$('#js-phone-page h4').text('Resend verification code on the following number');
		});
		//
		function validatePhonenumber(e){
			e.preventDefault();
			//
			$('#js-phone-page-span').text('');
			//
			var phone = $(this).val().trim(),
			tmp = fpn(phone),
			isValid = fpn(phone, '', true);
			//
			if(typeof(tmp) === 'object'){
				$(this).val(tmp.number);
				setCaretPosition(this, tmp.cur);
			}else $(this).val(tmp);

			if(!isValid) $('#js-phone-page-span').text('Invalid phone number.');
		}
		//
		function formHandler(e){
			e.preventDefault();
			//
			$('#js-phone-page-span').text('');
			//
			var phone = $('#js-phonenumber').val().trim(),
			isValid = fpn(phone, '', true);
			//
			if(!isValid) { $('#js-phone-page-span').text('Invalid phone number.'); return; }
			//
			megaOBJ.action = 'update_phonenumber';
			megaOBJ.phone_number = phone;
			megaOBJ.phone_number_e164 = '+1'+( phone.replace(/\D/g, '') );
			//
			innerLoader('show');
			//
			$.post(
				"<?=base_url('update-phonenumber');?>",
				megaOBJ, 
				function(resp) {
					if(resp.Status === false){
						innerLoader('hide');
						alertify.alert('ERROR!', resp.Response); 
						return;
					}
					$('.js-phonnumber-show-span').html('<b>'+( megaOBJ.phone_number )+'</b>');
					$('#js-phone-page').fadeOut(0);
					$('#js-verification-page').fadeIn(300);
					innerLoader('hide');
				}
			);

			console.log(megaOBJ);
		}
		// Format Phone Number
	    // @param phone_number
	    // The phone number string that 
	    // need to be reformatted
	    // @param format
	    // Match format 
	    // @param is_return
	    // Verify format or change format
	    function fpn(phone_number, format, is_return) {
	        // 
	        var default_number = '(___) ___-____';
	        var cleaned = phone_number.replace(/\D/g, '');
	        if(cleaned.length > 10) cleaned = cleaned.substring(0, 10);
	        match = cleaned.match(/^(1|)?(\d{3})(\d{3})(\d{4})$/);
	        //
	        if (match) {
	            var intlCode = '';
	            if( format == 'e164') intlCode = (match[1] ? '+1 ' : '');
	            return is_return === undefined ? [intlCode, '(', match[2], ') ', match[3], '-', match[4]].join('') : true;
	        } else{
	            var af = '', an = '', cur = 1;
	            if(cleaned.substring(0,1) != '') { af += "(_"; an += '('+cleaned.substring(0,1); cur++; }
	            if(cleaned.substring(1,2) != '') { af += "_";  an += cleaned.substring(1,2); cur++; }
	            if(cleaned.substring(2,3) != '') { af += "_) "; an += cleaned.substring(2,3)+') '; cur = cur + 3; }
	            if(cleaned.substring(3,4) != '') { af += "_"; an += cleaned.substring(3,4);  cur++;}
	            if(cleaned.substring(4,5) != '') { af += "_"; an += cleaned.substring(4,5);  cur++;}
	            if(cleaned.substring(5,6) != '') { af += "_-"; an += cleaned.substring(5,6)+'-';  cur = cur + 2;}
	            if(cleaned.substring(6,7) != '') { af += "_"; an += cleaned.substring(6,7);  cur++;}
	            if(cleaned.substring(7,8) != '') { af += "_"; an += cleaned.substring(7,8);  cur++;}
	            if(cleaned.substring(8,9) != '') { af += "_"; an += cleaned.substring(8,9);  cur++;}
	            if(cleaned.substring(9,10) != '') { af += "_"; an += cleaned.substring(9,10);  cur++;}

	            if(is_return) return match === null ? false : true;

	            return { number: default_number.replace(af, an), cur: cur };
	        }
	    }
	    // Change cursor position in input
	    function setCaretPosition(elem, caretPos) {
	        if(elem != null) {
	            if(elem.createTextRange) {
	                var range = elem.createTextRange();
	                range.move('character', caretPos);
	                range.select();
	            }
	            else {
	                if(elem.selectionStart) {
	                    elem.focus();
	                    elem.setSelectionRange(caretPos, caretPos);
	                } else elem.focus();
	            }
	        }
	    }
	    // Loader
	    function innerLoader(type, target){
	    	//
	    	type = type === undefined || type === 'show' || type === true ? 'show' : type;
	    	target = target === undefined ? $('.js-inner-loader') : target;
	    	//
	    	if(type === 'show') target.show(0)
	    	else target.hide(0);
	    }
	    //
	    function verifyCode(e){
	    	innerLoader('show');
	    	e.preventDefault();
	    	$('#js-verification-code-page-span').text('');
	    	megaOBJ.action = "validate_code";
	    	megaOBJ.code = $('#js-verification-code').val().trim();
	    	//
	    	$.post(
	    		"<?=base_url('update-phonenumber');?>", 
	    		megaOBJ, 
	    		function(resp) {
	    			if(resp.Status === false){
						innerLoader('hide');
						$('#js-verification-code-page-span').text('Invalid verification code.');
						return;
					}

					$('#js-verification-page').fadeOut(0);
					$('#js-thankyou-page').fadeIn(300);
					innerLoader('hide');
	    		}
	    	);
	    }

	    innerLoader('hide');
	});
</script>