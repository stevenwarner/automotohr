<script src="<?=base_url();?>assets/calendar/moment.min.js"></script>
<style>
	.event-detail-box p{ text-align: left; }
	.cs-warning-box p{ font-style: 12px; color: #000; }
	.cs-interviewers i.fa{ padding-right: 5px; font-size: 13px; }
	.event-detail-box .form-group{ padding-bottom: 10px; border-bottom: 1px solid #eee; }
	.event-detail-box img{ width: 100%; }
	.thank-you-text{ /*border-bottom: 1px solid #eee;*/ margin-bottom: 30px;}
	.end-user-agreement-wrp{ padding: 0; }
	form, .event-detail-box{ padding: 10px; }
	.event-detail-box{ padding-bottom: 0; }
	.main-content{ margin: 0 0 50px; }
	.thank-you-text span{ font-size: 14px }
	.ui-datepicker-header,
	.ui-datepicker{ box-shadow: none; margin-bottom: 20px; width: 100%; height: auto; }
	.select2-container--default .select2-selection--single{ border: 1px solid #aaa !important; padding-left: 5px; }
	.select2-container .select2-selection__rendered{ font-size: 20px; }
	.cs-required{ font-size: 16px; color: #cc1100 !important; font-weight: bolder; }
</style>

<?php
	//
	$old_date = $event_details['event_date_gc'];
	$event_details['event_date'] = $event_details['event_date_gc'] = reset_datetime(array(
        'datetime' => ($event_details['event_date_ac'].$event_details['event_start_time']),
        'from_format' => 'Y-m-dh:iA',
        'format' => 'M d Y, D',
        '_this' => $this,
        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
        'new_zone' => $timezone
    ));

    $event_start_time_with_date = reset_datetime(array(
        'datetime' => ($old_date.$event_details['event_start_time']),
        'from_format' => 'Ymdh:iA',
        'format' => 'Y-m-d H:i:s',
        '_this' => $this,
        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
        'new_zone' => $timezone
    ));

    $event_details['event_start_time'] = reset_datetime(array(
        'datetime' => ($old_date.$event_details['event_start_time']),
        'from_format' => 'Ymdh:iA',
        'format' => 'h:iA',
        '_this' => $this,
        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
        'new_zone' => $timezone
    ));

    $event_end_time_with_date = reset_datetime(array(
        'datetime' => ($old_date.$event_details['event_end_time']),
        'from_format' => 'Ymdh:iA',
        'format' => 'Y-m-d H:i:s',
        '_this' => $this,
        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
        'new_zone' => $timezone
    ));

    $event_details['event_end_time'] = reset_datetime(array(
        'datetime' => ($old_date.$event_details['event_end_time']),
        'from_format' => 'Ymdh:iA',
        'format' => 'h:iA',
        '_this' => $this,
        'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
        'new_zone' => $timezone
    ));

	//
	$date  = DateTime::createFromFormat('Y-m-d H:i:s', $event_start_time_with_date);
	$date2 = DateTime::createFromFormat('Y-m-d H:i:s', $event_end_time_with_date);
	if($date > $date2)
		$date2->add(new DateInterval('P01D'));
	// Event Duration
    $duration = explode(' ', get_date_difference_duration($date2, $date) );
	// 
	$note = 'You are about to request an event cancellation.';
	if($event_array['type'] == 'confirmed')
		$note = 'You have requested to confirm the event.';
	if($event_array['type'] == 'reschedule')
		$note = 'You are about to request for a reschedule of an event.';

	if($event_type == 'training-session'){
		$note = 'You are about to cancel the event.';
		if($event_array['type'] == 'confirmed')
			$note = 'You have confirmed the event.';
		if($event_array['type'] == 'attended')
			$note = 'You have attended the event.';		
		if($event_array['type'] == 'reschedule')
			$note = 'You are about to request for a reschedule of an event.';
	}

	// Reset categories
    switch (strtolower($event_details['category_uc'])) {
        case 'interview': $event_category = 'In-Person Interview'; break;
        case 'interview-phone': $event_category = 'Phone Interview'; break;
        case 'interview-voip': $event_category = 'Voip Interview'; break;
        case 'training-session': $event_category = 'Training Session'; break;
        case 'other': $event_category = 'Other Appointment'; break;
        default: $event_category = ucwords($event_details['category_uc']); break;
    }

	$class = "col-lg-8 col-md-8 col-xs-12 col-sm-12 col-md-offset-2";
	// if($event_array['type'] == 'confirmed') $class="col-lg-12 col-md-12 col-xs-12 col-sm-12";

	$event_details['event_start_time'] = strlen($event_details['event_start_time']) != 7 ? '0'.$event_details['event_start_time'] : $event_details['event_start_time'];
	// Event info box
	$event_info_box = '';
	$event_info_box .= '<!-- event detail box -->';
	$event_info_box .= '<div class="event-detail-box">';
	$event_info_box .= '<h3>Current Scheduled Event Details ('.$timezone.')</h3>';
	$event_info_box .= '<hr />';	
	// Date
	$event_info_box .= '	<div class="form-group">';
	$event_info_box .= '		<div class="row">';
	$event_info_box .= '			<div class="col-sm-3 col-xs-6">';
	$event_info_box .= '				<label>Event Date</label>';
	$event_info_box .= '				<p>'.( $event_details['event_date'] ).'</p>';
	$event_info_box .= '			</div>';
	$event_info_box .= '			<div class="col-sm-3 col-xs-6">';
	$event_info_box .= '				<label>Start Time</label>';
	$event_info_box .= '				<p>'.( $event_details['event_start_time'] ).'</p>';
	$event_info_box .= '			</div>';
	$event_info_box .= '			<div class="col-sm-3 col-xs-6">';
	$event_info_box .= '				<label>Event Duration</label>';
	$event_info_box .= '				<p>'.( implode(' ', $duration) ).'</p>';
	$event_info_box .= '			</div>';
	$event_info_box .= '			<div class="col-sm-3 col-xs-6">';
	$event_info_box .= '				<label>Event Type</label>';
	$event_info_box .= '				<p>'.( $event_category ).'</p>';
	$event_info_box .= '			</div>';
	$event_info_box .= '		</div>';
	$event_info_box .= '	</div>';
	
	if(sizeof($event_details['interviewers'])){
		// Interviewers
		$event_info_box .= '	<div class="form-group">';
		$event_info_box .= '		<div class="row cs-interviewers">';
		$event_info_box .= '			<div class="col-sm-12">';
		if($event_type == 'training-session')
			$event_info_box .= '				<label>Particpant(s)</label>';
		else
			$event_info_box .= '				<label>Interviewer(s)</label>';
		$event_info_box .= '			</div>';
		foreach ($event_details['interviewers'] as $k0 => $v0) {
			$timezone_str = '';
			if(array_key_exists('timezone',$v0)){
				$timezone_str = ' ('.$v0['timezone'].')';
				if(empty($v0['timezone'])){
					$timezone_str = ' ('.$company_timezone.')';
				}
			}
			
			$event_info_box .= '			<div class="col-sm-12 col-xs-12">';
			$event_info_box .= '				<p><i class="fa fa-check"></i>'.( ucwords($v0['value']) ).' '.( $v0['show_email'] == 1 ? '('.( $v0['email_address'] ).')' : '' ).$timezone_str.'</p>';
			$event_info_box .= '			</div>';
		}
		$event_info_box .= '		</div>';
		$event_info_box .= '	</div>';
	}

	if($event_details['address'] != '' || $event_details['address'] != NULL){
		// Location Box
		$event_info_box .= '	<div class="form-group">';
		$event_info_box .= '		<div class="row">';
		$event_info_box .= '			<div class="col-sm-12 col-xs-12">';
		$event_info_box .= '				<label>Address</label>';
		$event_info_box .= '			</div>';
		$event_info_box .= '			<div class="col-sm-12 col-xs-12">';
		$event_info_box .= '				<p>'.( $event_details['address'] ).'</p>';
		$event_info_box .= '			</div>';
		$event_info_box .= '			<div class="col-sm-12 col-xs-12">';
		$event_info_box .= '				<a href="https://maps.google.com/maps?z=12&t=m&q='.(urlencode($event_details['address'])).'">';
		$event_info_box .= '					<img src="https://maps.googleapis.com/maps/api/staticmap?center='.(urlencode($event_details['address'])).'&zoom=13&size=250x150&key='.GOOGLE_MAP_API_KEY.'&markers=color:blue|label:|' . urlencode($event_details['address']).'" />';
		$event_info_box .= '				</a>';
		$event_info_box .= '			</div>';
		$event_info_box .= '		</div>';
		$event_info_box .= '	</div>';
	}

	$event_info_box .= '</div>';

?>

<!--   -->
    <div class="row">
		<div class="main-content" style="min-height: 500px;">
            <div class="<?=$class;?> js-after">
            	<?php if(isset($error_msg)){?>
            		<div class="col-sm-12">
            			<h1><?=  $error_msg ?></h1>
            		</div>
            		<div class="col-sm-9">
	            		<div class="cs-notconfirmed-box">
			                <div class="end-user-agreement-wrp">
			                    <div class="thankyou-page-wrp text-left">
			                    	<?=$event_info_box;?>
			                    </div>
			                </div>
			            </div>
			        </div>
            		<!-- <div class="alert alert-danger"><?=$error_msg;?></div> -->
            	<?php }else{ ?>
            	<?php if(($event_array['type'] == 'notconfirmed' || $event_array['type'] == 'reschedule') && !isset($error_msg)){ ?>
            	<!-- <div class="col-sm-12">
            		<h1><?=$event_array['type'] == 'notconfirmed' ? 'Cancel' : 'Reschedule';?> This Event</h1>
            		<br />
            	</div> -->
            	<?php } ?>
            	<!-- Main area  -->
				<div class="col-sm-9 col-xs-12">
					<!--  -->
	            	<?php if(($event_array['type'] == 'confirmed' || $event_array['type'] == 'attended') && !isset($error_msg)){ ?>
	            	<div class="cs-confirm-box">
		                <div class="end-user-agreement-wrp">
		                    <div class="thankyou-page-wrp">
		                        <div class="thanks-page-icon">
		                            <div class="icon-circle"><i class="fa fa-check"></i></div>
		                        </div>
		                        <div class="thank-you-text">
                                    <h1>Thank You</h1>
                                    <span>
	                            	<?php if($event_type == 'training-session' && $event_array['type'] == 'attended') { ?>
	                                	You have Attended the event.
	                            	<?php }else if($event_type == 'training-session') { ?>
	                                	You have Confirmed the event.
	                            	<?php }else{ ?>
	                                	You have Confirmed your <b><?=$event_category;?></b>.
	                            	<?php } ?>
	                            	</span>
		                            <br />
		                            <br />
		                        </div>
		                    </div>
		                		<?=$event_info_box;?>
		                </div>
		            <?php } ?>
					<!--  -->
		            <?php if($event_array['type'] == 'notconfirmed' && !isset($error_msg)){ ?>
	            	<div class="cs-notconfirmed-box">
		                <div class="end-user-agreement-wrp">
		                    <div class="thankyou-page-wrp text-left">
		                    	<?=$event_info_box;?>
		                    </div>
		                </div>
		                <div class="end-user-agreement-wrp" style="margin-top: 30px;">
		                    <div class="thankyou-page-wrp text-left">
	                        	<div class="col-sm-12">
		                        	<h2>Cancel This Event</h2>
	                    			<hr />
	                        	</div>
		                        <div class="thank-you-text">
		                            <form action="javascript:void(0)" id="js-event-nc" method="POST">
		                            	<div class="form-group">
		                            		<label>Please Provide a Reason why this event is being Cancelled. <span class="cs-required">*</span></label>
		                            		<textarea rows="5" class="form-control" name="txt_reason" required="true"></textarea> 
		                            	</div>

		                            	<div class="form-group">
		                            		<input type="submit" class="btn btn-danger" value="Cancel Interview" /> 
		                            	</div>
		                            </form>
		                        </div>
		                    </div>
		                </div>
		             <?php } ?>
					<!--  -->
		            <?php if($event_array['type'] == 'reschedule' && !isset($error_msg)){ ?>

		            <?php 
		            	/**
		            	 * Generate times
		            	 * Created on: 24-05-2019
		            	 *
		            	 * @param $selected String
		            	 * @param $type String
		            	 *
		            	 * @return String
		            	 */
		            	if(!function_exists('generate_time_options')){
			            	function generate_time_options($selected, $type = 'AM'){
			            		$options = '';
			            		for($i = 1; $i <= 11; $i++) {
		        					$lt = (strlen($i) == 1 ? '0' : '').$i;
		        					$rt = '00';
		        					$time = $lt.':'.$rt.' '.$type;
		        					$options .= '<option '.($selected == str_replace(' ', '', $time) ? 'selected="true"' : '').' value="'.$time.'">'.$time.'</option>';
		        					for($j = 5; $j <= 45; $j = $j+5) {
		        						$rt = (strlen($j) == 1 ? 0 : '').$j;
		        						$time = $lt.':'.$rt.' '.$type;
		        						$options .= '<option '.($selected == str_replace(' ', '', $time) ? 'selected="true"' : '').' value="'.$time.'">'.$time.'</option>';
		        					}
		        				}
		        				return $options;
			            	}
		            	}
		            ?>
	            	<div class="cs-reschedule-box">
		                <div class="end-user-agreement-wrp">
		                    <div class="thankyou-page-wrp text-left">
		                    	<?=$event_info_box;?>
	                    	</div>
	                    </div>
	                    <div class="end-user-agreement-wrp" style="margin-top: 30px;">
		                    <div class="thankyou-page-wrp text-left">
	                    		<div class="col-sm-12">
	                    			<h1>Reschedule This Event</h1>
	                    			<hr />
		                    		<p><strong>Please provide us with the Date and Time and the Reason that you would like to Reschedule this event.</strong></p>
		                    		<hr />
	                    		</div>
		                        <div class="thank-you-text">
		                            <form action="javascript:void(0)" id="js-event-rs" method="POST">
		                            	<div class="form-group">
		                            		<div class="row">
		                            			<div class="col-sm-4">
				                            		<label style="font-size: 18px;">Date <span class="cs-required">*</span></label>
				                            		<div class="datepicker js-date"></div>
		                            			</div>
		                            			<div class="col-sm-8">
				                            		<label style="font-size: 18px;">Time <span class="cs-required">*</span></label>
				                            		<br />
				                            		<label style="font-size: 16px;">AM Time</label>
				                            		<select name="txt_start_time_am" class="form-control js-time" required="true">
				                            			<option value="0">[No AM Selected]</option>
				                            			<?=generate_time_options($event_details['event_start_time'], 'AM');?>
				                            		</select>
		                            			</div>
		                            			<div class="col-sm-8">
		                            				<label>&nbsp;</label>
		                            				<br />
				                            		<label style="font-size: 16px;">PM Time</label>
				                            		<select name="txt_start_time_pm" class="form-control js-time" required="true">
				                            			<option value="0">[No PM Selected]</option>
				                            			<?=generate_time_options($event_details['event_start_time'], 'PM');?>
				                            		</select>
		                            			</div>
		                            			
				                            	<input type="hidden" name="txt_date"/>
		                            		</div>
		                            	</div>

		                            	<div class="form-group">
		                            		<label>Please Provide a Reason why this event is being Reschedule. <span class="cs-required">*</span></label>
		                            		<textarea rows="5" class="form-control" name="txt_reason" required="true"></textarea> 
		                            	</div>

		                            	<div class="form-group">
		                            		<input type="submit" class="btn btn-success" value="Request Reschedule" /> 
		                            	</div>
		                            </form>
		                        </div>
		                    </div>
		                </div>

		                <link href="<?=base_url();?>assets/css/select2.css" rel="stylesheet"/>
    					<script src="<?=base_url();?>assets/js/select2.js"></script>

		                <script>

		                	$('select[name="txt_start_time_pm"]').select2();
							$('select[name="txt_start_time_am"]').select2();

		                	var datetime = { 
		                		date: "<?=date('m-d-Y', strtotime('+1 day'));?>",
		                		start_time: '08:00AM',
		                		end_time: '08:00PM'
		                	};
		                	// Load date calendar
	        				$(".datepicker").datepicker({dateFormat: 'mm-dd-yy', minDate: 1}).val();
	        				// Loads time plugin for start time field
					        $('#js-event-start-time').datetimepicker({
					            datepicker: false,
					            format: 'g:iA',
					            formatTime: 'g:iA',
					            minTime: '10:00AM',
					            maxTime: '11:00PM',
					            step: 15,
					            onShow: function (ct) {
					                this.setOptions({
					                    maxTime: $('#js-event-end-time').val() ? $('#js-event-end-time').val() : false
					                });
					            }
					        });
					        // Loads time plugin for end time field
					        $('#js-event-end-time').datetimepicker({
					            datepicker: false,
					            format: 'g:iA',
					            formatTime: 'g:iA',
					            step: 15,
					            onShow: function (ct) {
					                time = $('#js-event-start-time').val();
					                timeAr = time.split(":");
					                last = parseInt(timeAr[1].substr(0, 2)) + 15;
					                if (last == 0)
					                    last = "00";
					                mm = timeAr[1].substr(2, 2);
					                timeFinal = timeAr[0] + ":" + last + mm;
					                this.setOptions({
					                        minTime: $('#js-event-start-time').val() ? timeFinal : false
					                    }
					                )
					            }
					        });

					        $('select[name="txt_start_time_pm"]').change(function() {
					        	if($(this).val() != 0)
					        		$('select[name="txt_start_time_am"]').select2('val', [0]);
					        });
					        $('select[name="txt_start_time_am"]').change(function() {
					        	if($(this).val() != 0)
					        		$('select[name="txt_start_time_pm"]').select2('val', [0]);
					        });
					        // Set defaults
					        $('.datepicker').datepicker('setDate', datetime.date);
					        $('.datepicker').on('change', function(){
					        	$('input[name="txt_date"]').val($(this).val());
					        });
					        $('input[name="txt_date"]').val(datetime.date);
							$('input[name="txt_start_time"]').val(datetime.start_time);
							$('input[name="txt_end_time"]').val(datetime.end_time);
		                </script>
		             <?php } ?>
	            	</div>
            	</div>
            	<?php } ?>
            	<!-- Notice box -->
            	<div class="col-sm-3 col-xs-12">
            		<div class="alert alert-warning cs-warning-box">
            			<?php if(!isset($error_msg)) { ?>
            			<p><strong>Note</strong> <br /><?=$note;?></p>
            			<br />
            			<?php } ?>
            			<p><strong>Event Date</strong> <br />Represents on which date the event is occurring.</p>
            			<p><strong>Start Time</strong> <br />Represents the start time of the event.</p>
            			<p><strong>Duration</strong> <br />The total time of the event.</p>
            			<p><strong>Event Type</strong> <br />Represents the type of the event.</p>
            			<?php if($event_type == 'training-session') { ?>
            			<p><strong>Particpant(s)</strong> <br />Represents the people who are invited for the event.</p>
            			<?php }else{ ?>
            			<p><strong>Interviewers</strong> <br />Represents the people who will be conducting the interview.</p>
            			<?php }?>
            		</div>
            	</div>
            </div>
        </div>
    </div>
    



<?php if(($event_array['type'] == 'reschedule' || $event_array['type'] == 'notconfirmed')  && !isset($error_msg)) { ?>
	
	<script>
		$(function(){
			$('form#js-event-nc').submit(function(e){
				e.preventDefault();
				alertify.confirm('Do you really want to cancel the event?', function(){
					load_resp(true);
					var data = {};
					data.type = 'cannotattend';
					data.event_id = <?=$event_array['eid'];?>;
					data.user_id = <?=$event_array['id'];?>;
					data.user_name = "<?=$event_array['name'];?>";
					data.user_email = "<?=$event_array['email'];?>";
					data.user_type = "<?=$event_array['etype'];?>";
					data.cat = "<?=$event_type;?>";
					data.lcid = "<?=isset($event_array['training_session_sid']) ? $event_array['training_session_sid'] : 0;?>";
					data.event_reason = $('form#js-event-nc').find('textarea[name="txt_reason"]').val().trim();
					if(data.event_reason == ''){
						load_resp(false); alertify.alert('Cancellation reason is required.'); return false;
					}
					// $('form#js-event-nc').find('textarea[name="txt_reason"]').val('');
					var btn = $('form#js-event-nc').find('input[type="submit"]');
					var post = $.post("<?=base_url();?>event-handler/", data, function(res){
						if(res.Status === false && res.Erase === true) { erase(res.Response, 'form#js-event-nc') }
						if(res.Status === false) load_resp(res.Response, 'danger');
						else { load_resp(res.Response, 'success'); btn.remove(); }
					});
				}, function(){ return; });
			});

			$('form#js-event-rs').submit(function(e){
				e.preventDefault();
				var cnf = alertify.confirm('Do you really want to reschedule the event?', function(){ 
					load_resp(true);
					var data = {};
					data.type = 'reschedule';
					data.timezone = "<?=$timezone;?>";
					data.event_id = <?=$event_array['eid'];?>;
					data.user_id = <?=$event_array['id'];?>;
					data.user_name = "<?=$event_array['name'];?>";
					data.user_email = "<?=$event_array['email'];?>";
					data.user_type = "<?=$event_array['etype'];?>";
					data.event_date = $('form#js-event-rs').find('input[name="txt_date"]').val();
					data.event_start_time = $('select[name="txt_start_time_pm"]').val() != 0 ? $('select[name="txt_start_time_pm"]').val() : $('select[name="txt_start_time_am"]').val();
					data.event_start_time = data.event_start_time.replace(' ', '');
					// data.event_start_time = $(this).find('input[name="txt_start_time"]').val();
					// data.event_end_time = $(this).find('input[name="txt_end_time"]').val();
					if(data.event_start_time == 0)
						data.event_start_time = '08:00AM';
					data.event_end_time = moment(data.event_start_time, 'h:mmA').add(<?=$duration[0];?>, "<?=$duration[1];?>").format('h:mmA');
					//
					if(data.event_date == '' || data.event_date == null){
						load_resp(false); alertify.alert('Event date is required'); return false;
					}

					if(data.event_start_time == '' || data.event_start_time == null){
						load_resp(false); alertify.alert('Event start-time is required'); return false;
					}

					if(data.event_end_time == '' || data.event_end_time == null){
						load_resp(false); alertify.alert('Event end-time is required'); return false;
					}

					data.event_reason = $('form#js-event-rs').find('textarea[name="txt_reason"]').val().trim();
					
					if(data.event_reason == ''){
						load_resp(false); alertify.alert('Reschedule reason is required.'); return false;
					}

					//
					$('form#js-event-rs').find('input[name="txt_date"]').val(datetime.date);
					$('form#js-event-rs').find('input[name="txt_start_time"]').val(datetime.start_time);
					$('form#js-event-rs').find('input[name="txt_end_time"]').val(datetime.end_time);
					// $(this).find('textarea[name="txt_reason"]').val('');
					//
					var btn = $('form#js-event-rs').find('input[type="submit"]');
					var post = $.post("<?=base_url();?>event-handler/", data, function(res){
						if(res.Status === false && res.Erase === true) { erase(res.Response, 'form#js-event-rc') }
						else if(res.Status === false) load_resp(res.Response, 'danger');
						else { load_resp(res.Response, 'success'); btn.remove(); }

					});
				}, function(){ return; });
			});


			// Shows message on form
			// @param msg
			// Actual message to show
			// @param cl
			// Message class
			function load_resp(msg, cl){
				//
				$('.js-alert').remove();
				$('.btn').removeClass('disabled');
				// Check if loader is enabled
				if(msg === true){
					$('.btn').addClass('disabled');
					$('form').prepend('<div class="alert alert-info js-alert"><i class="fa fa-spinner fa-spin"></i></div>');
				}else if(msg === false) $('.btn').removeClass('disabled');
				else
					$('form').prepend('<div class="alert alert-'+cl+' js-alert"><p>'+msg+'</p></div>');
			}

			// Erase forms and show error
			// @param msg
			// Actual message to show
			// @param el
			// Name of el tobe removed
			function erase(msg, el){
				$(el).remove();
				$('.js-after').html('<div class="alert alert-danger">'+msg+'</div>');
			}
		});
	</script>
<?php } ?>


<script>
	if($('form').length !== 0){
		$(window).load(function(){
	        $('form').css('background-color', '#fcf8e3');
			$('html, body').animate({
	            scrollTop: $("form").offset().top - 200
	        }, 1000);

	        $('form').animate({'background-color': 'none'}, 3000);
		});
	}
</script>