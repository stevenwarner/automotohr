<?php 
	//
	$ats_phonenumber = trim($applicant_info['phone_number']);
	if($ats_phonenumber == null || $ats_phonenumber == 'null' ) $ats_phonenumber = '';
	//
	$ats_phonenumber = phonenumber_format($ats_phonenumber, TRUE);
	$ats_phonenumber_e164 = '+1'.preg_replace('/[^0-9]/', '', phonenumber_format($ats_phonenumber));
	//
	$ats_fullname = trim($applicant_info['first_name'].' '.$applicant_info['last_name']);
	$ats_sid      = trim($applicant_info['sid']);
	//
	$ats_email_address = trim($applicant_info['email']);
	if($ats_email_address == '' || $ats_email_address == null || $ats_email_address == 'null') $ats_email_address = '';
?>

<div class="row">
	<?php if((int)phonenumber_validate($ats_phonenumber_e164) === 0){ ?>
	<!-- Modify phone -->
	<div class="col-sm-4 col-sm-offset-4 js-sms-view-phone">
		<!-- Loader -->
		<div class="text-center cs-inner-loader js-inner-loader">
			<i class="fa fa-spinner fa-spin"></i>
		</div>
		<?php if($ats_email_address != '') { ?>
		<div class="alert alert-success js-msg-area" style="display: none;"></div>
		<h4>Applicant phone number is invalid. Send an email to the applicant.</h4>
		<form action="javascript:void(0)" id="js-phone-update-form">
			<!--  -->
			<div class="form-group">
				<input type="hidden" name="txt_sid" value="<?=$ats_sid;?>" />
				<input type="hidden" name="txt_name" value="<?=$ats_fullname;?>" />
				<input type="hidden" name="txt_email_address" value="<?=$ats_email_address;?>" />
				<input type="submit" value="Send Email" class="btn btn-success"/>
			</div>
		</form>
		<?php } else { ?>
			<h4>Applicant phone number & email address is invalid. To proceed update the email address.</h4>
		<?php } ?>
	</div>
	<?php } else { ?>
	<div class="col-sm-12 js-sms-view-block">
		<!--  -->
		<div class="row">
			<div class="col-sm-12 js-sms-view-form">
				<!-- Loader -->
				<div class="text-center cs-inner-loader js-inner-loader">
					<i class="fa fa-spinner fa-spin"></i>
				</div>
				<form action="javascript:void(0)" id="js-sms-form">
					<div class="form-group">
						<label>Phone number</label>
						<div class="input-group">
							<div class="input-group-addon">+1</div>
							<input type="text" name="txt_phonenumber" class="form-control" readonly="true" value="<?=$ats_phonenumber;?>"/>
						</div>
					</div>

					<div class="form-group">
						<label>Message</label>
						<textarea class="form-control" id="js-sms-form-message" rows="7"></textarea>
						<p><span class="js-words">0</span> words / <span class="js-sms">0</span> sms (160 words/sms )</p>
						<span class="cs-error js-msg-error"></span>
					</div>
					<!--  -->
					<div class="form-group">
						<input type="submit" value="Send Message" class="btn btn-success"/>
					</div>
				</form>
			</div>
			<div class="col-sm-12 js-sms-view-area">
				<hr />
				<h3 style="background-color: #81b431; padding:10px;">SMS History</h3>
				<div class="js-page-error">
					<!-- Loader -->
					<div class="text-center cs-inner-loader js-load-loader">
						<i class="fa fa-spinner fa-spin"></i>
					</div>
					<p class="text-center">Please, wait while we are loading messages.....</p>
				</div>
				<!--  -->
				<div class="cs-ms-window js-ms-window"><ul></ul></div>
			</div>
		</div>
	</div>
	<?php } ?>
</div>


<script>
	$(function(){
		<?php if((int)phonenumber_validate($ats_phonenumber_e164) !== 0){ ?>
		//
		var word_limit = 160,
		have_all_records = false;
		//
		$('#js-sms-form-message').keyup(function(event) {
			var total_words = $(this).val().length;
			$('.js-words').text(total_words);
			$('.js-sms').text(total_words === 0 ? 0 : (total_words <= word_limit ? 1 : Math.ceil(total_words/word_limit)));
		});
		<?php } ?>
		//
		var megaOBJ = {};
		megaOBJ.id  = megaOBJ.applicant_id = <?=$applicant_info['sid'];?>;
		megaOBJ.type = 'applicant';
		megaOBJ.action = 'send_sms';
		megaOBJ.phone  = "<?=$ats_phonenumber?>";
		megaOBJ.phone_e16 = "<?=$ats_phonenumber_e164?>";

		<?php if((int)phonenumber_validate($ats_phonenumber_e164) === 0){ ?>
			//
			$('.js-sms-view-block').hide(0);
			$('.js-sms-view-phone').show(0);
			//
			$('.js-sms-phone').keyup(function() {
				var tmp = fpn($(this).val().trim());
				if(typeof(tmp) === 'object'){
					$(this).val(tmp.number);
					setCaretPosition(this, tmp.cur);
				}else $(this).val(tmp);
			});
			//
			$('.js-sms-phone').trigger('keyup');
			//
			// $('#js-phone-update-form').submit(function(e){
			// 	e.preventDefault();
			// 	$(this).find('.js-error').hide(0);
			// 	//
			// 	megaOBJ.phone = $(this).find('input[name="txt_phonenumber"]').val().trim();
			// 	// Validation
			// 	if(!fpn(megaOBJ.phone, null, true)){
			// 		$(this).find('.js-error').text('Phone number is invalid.').show();
			// 		return;
			// 	}

			// 	megaOBJ.action = 'update_phone_number';
			// 	megaOBJ.phone_e16 = '+1'+(megaOBJ.phone.toString().replace(/\D/g, ''));

			// 	$.post("<?=base_url('application_tracking_system/handler');?>", megaOBJ, function(resp) {
			// 		if(resp.Status === false){
			// 			alertify.alert('Error!', resp.Response);
			// 			return;
			// 		}
			// 		//
			// 		$('.js-sms-view-phone').remove();
			// 		$('.js-sms-view-block').show(0);
			// 		// Change phone number
			// 		$('#js-sms-form').find('input[name="txt_phonenumber"]').val(megaOBJ.phone);
			// 	});
			// });

			$('#js-phone-update-form').submit(function(e){
				$('.js-msg-area').text('').hide(0);
				e.preventDefault();
				$(this).find('.js-error').hide(0);
				//
				megaOBJ.email_address = $(this).find('input[name="txt_email_address"]').val().trim();
				megaOBJ.name = $(this).find('input[name="txt_name"]').val().trim();
				megaOBJ.sid = $(this).find('input[name="txt_sid"]').val().trim();
				megaOBJ.action = 'send_email_to_update_number';
				inner_loader('show');
				//
				$.post("<?=base_url('application_tracking_system/handler');?>", megaOBJ, function(resp) {
					inner_loader('hide');
					if(resp.Status === false){
						alertify.alert('Error!', resp.Response);
						return;
					}
					//
					$('.js-msg-area').text(resp.Response).show(0);
				});
			});
		<?php } else{ ?>
			$('.js-sms-view-block').show(0);
			$('.js-sms-view-phone').hide(0);
		<?php } ?>

		$('#js-sms-form').submit(function(e) {
			e.preventDefault();
			var _this = $(this);
			$(this).find('span.js-msg-error').hide(0);
			megaOBJ.action = 'send_sms';
			megaOBJ.message = $(this).find('textarea').val().trim();

			if(megaOBJ.message == ''){
				$(this).find('span.js-msg-error').text('Message is required.').show(0);
				return;
			}
			inner_loader();
			//
			$.post("<?=base_url('application_tracking_system/handler');?>", megaOBJ, function(resp) {
				if(resp.Status === false){
					alertify.alert('Error!', resp.Response);
					return;
				}
				//
				inner_loader('hide');
				_this.find('textarea').val('');

				last_fetched_id = 0;
				have_all_records = false;
				$('.js-words').text(0);
				$('.js-sms').text(0);
				fetch_sms();
			});
		});

	    <?php if((int)phonenumber_validate($ats_phonenumber_e164) !== 0){ ?>
		// Set pagination object
		var last_fetched_id = 0,
		xhr = null;

		fetch_sms();

		$(document).on('click', '.js-sms-load-btn', fetch_sms);

		// Fetch SMS
	    function fetch_sms(){
	    	if(have_all_records === true) return;
	    	if(xhr != null) return;
	    	inner_loader('show', $('.js-load-loader'));
	    	xhr = $.post("<?=base_url('application_tracking_system/handler');?>",{
	    		action: 'fetch_sms_ats',
	    		type: 'applicant',
	    		module: 'ats',
	    		id: "<?=$applicant_info['sid'];?>",
	    		last_fetched_id: last_fetched_id
	    	}, function(resp) {
	    		xhr = null;
	    		//
	    		if(resp.Status === false){
	    			have_all_records = true;
	    			$('.js-sms-load-btn').addClass('disabled').prop('disabled', true).unbind('click');
	    			inner_loader('hide', $('.js-load-loader'));
	    			$('.js-page-error p').html(resp.Response);
	    			return;
	    		}
	    		//
	    		load_response(resp);
	    	});
	    }
	    //
	    function load_response(resp){
	    	var rows = '';
	    	$.each(resp.Data, function(i, v) {
                rows += '<li class="'+( v.message_type == 'received' ? 'cs-right' : '' )+'">';
                rows += '    <p class="cs-li-head">'+( v.full_name )+' <br /><span>'+( v.created_at )+'</span></p>';
                rows += '    <p class="cs-li-body">';
                rows += '       '+( v.message_body )+'';
                rows += '    </p>';
                rows += '</li>';
            });

            $('.js-page-error p').remove();
	    	//
	    	if(last_fetched_id == 0) { $('.js-sms-load-btn').parent().remove(); $('.js-ms-window > ul').html(rows); $('.js-ms-window > ul').after('<div class="col-sm-12 text-center"><button class="btn btn-success js-sms-load-btn"><i class="fa fa-plus" style="font-size: 14px;"></i>&nbsp;Load More</button></div>') }
	    	else $('.js-ms-window > ul').append(rows);

	    	inner_loader('hide', $('.js-load-loader'));
	    	//
            last_fetched_id = resp.LastId;
	    }
	    //
	    function load_sms (e) {
            if(($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) && have_all_records === false){
                fetch_sms(megaOBJ);
            }
        }
    	<?php } ?>
    	inner_loader('hide');
    	// Loader
	    function inner_loader(type, target){
	    	//
	    	type = type === undefined || type === 'show' || type === true ? 'show' : type;
	    	target = target === undefined ? $('.js-inner-loader') : target;
	    	//
	    	if(type === 'show') target.show(0)
	    	else target.hide(0);
	    }
	   
	})
</script>

<style>
	.cs-error{ color: #cc0000; font-weight: 700; }

	/*Inner loader*/
	.cs-inner-loader{ position: absolute; top: 0; bottom: 0; left: 0; right: 0; background: rgba(255,255,255,.5); z-index: 2; font-size: 30px; }
	.cs-inner-loader i{ position: relative; top: 50%; margin-top: -30px; }

	/**/
	.cs-ms-window{ overflow-y: auto; }
    .cs-ms-window ul{ list-style: none; }
    .cs-ms-window ul li{ display: block; border-bottom: 1px solid #eee; margin-top: 10px; padding: 5px; }
    .cs-ms-window ul li.cs-right{ background: rgba(129, 180, 49, .1); /*text-align: right;*/ }
    .cs-ms-window ul li p.cs-li-head{ font-weight: bold; }
    .cs-ms-window ul li p.cs-li-head span{ font-size: 11px; }

    @media  screen and (max-width: 1920px){
    	.resp-tabs-list li{ max-width: 14.34%; }
	}
</style>

