<link href="<?=base_url('assets');?>/calendar/fullcalendar.css" rel="stylesheet"/>
<link href="<?=base_url('assets');?>/calendar/fullcalendar.print.css" rel="stylesheet" media="print"/>
<script src="<?=base_url('assets');?>/calendar/moment.min.js"></script>
<script src="<?=base_url('assets');?>/calendar/fullcalendar.min.js"></script>

<!-- lodash -->
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.11/lodash.min.js"></script>


<style> 
	.cs-loader-file{ z-index: 1061 !important; display: block !important; height: 1353px !important; }
	.cs-loader-box{ position: fixed; top: 100px; bottom: 0; right: 0; left: 0; max-width: 300px; margin: auto; z-index: 1539; }
	.cs-loader-box i{ font-size: 14em; color: #81b431; }
	.cs-loader-box div.cs-loader-text{ display: block; padding: 10px; color: #000; background-color: #fff; border-radius: 5px; text-align: center; font-weight: 600; margin-top: 35px; }
	.cs-calendar{ margin-top: 10px; }
	/* Calendar CSS Overrides*/
	.fc-day-header.fc-widget-header { background-color: #81b431; color: #fff; padding: 12px; text-transform: uppercase; }
	.fc-axis,.fc-widget-header { color: #fff; background-color: #4496d2; }
	.fc-basic-view .fc-week-number, .fc-basic-view .fc-day-number { padding: 8px 12px !important; }
	.fc-row .fc-content-skeleton td, .fc-row .fc-helper-skeleton td{ border-color: #e0dfdf !important; }
    .fc-event-cc{ border-width: 20px; border-bottom: 0 !important; border-top: 0 !important; border-right: 0 !important; padding: 5px; margin-bottom: 1px; }
    .fc-more-popover .fc-event-container{ max-height: 303px; overflow: auto; }
    .fc-ltr .fc-popover .fc-header .fc-title{ color: #000000; }
    .fc-unthemed .fc-today{ background: #fcf8e3 !important; }
    /* button in page heading*/
    .js-fix-btn > a{ position: absolute; top: 13px; left: 10px; }
    #js-event-colors td{ text-transform: capitalize; }
    .cs-pill-wrap{ margin-top: 60px; }
    .modal-backdrop { z-index: 1040 !important; } 
    .modal-dialog { z-index: 1100 !important; }
    /* fix of NAV icons for IE*/
    .fc-icon::after{ margin: 0 0 !important; }
    ul.ui-front{ z-index: 9999 !important; }
    /* To set loader precedence */
    /* higher than modal*/
    #my_loader{ z-index: 9999; }
    /* Trianing session tab bg*/
    .btn-event-training-session{ color: #ffffff; background-color: #337ab7; }
    .ui-autocomplete{ z-index: 1234; }
    /*Event tab border*/
    .fc-event-cc{ border-width: 20px; border-bottom: 0 !important; border-top: 0 !important; border-right: 0 !important; padding: 5px; margin-bottom: 1px; }
    /*Added on: 06-05-2019*/
    .fc-more-popover .fc-event-container{ max-height: 303px; overflow: auto; }
    .fc-ltr .fc-popover .fc-header .fc-title{ color: #000000; }
    /**/
	.cs-select{ background: none !important; border: none !important; padding: 0 !important;}
	/**/
	.select2-container{ display: block; }
	/**/
	.control--radio{ display: inline-block; margin-bottom: 10px; }
	/**/
	.form-control{ border-radius: 0 !important; }
    .select2-choices{ z-index: 10; max-height: 50px; overflow-y: auto !important; }
</style>

<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
            	<!-- Side Menu -->
            	<?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
            	<div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
	                <div class="dashboard-content">
	                	<div class="dash-inner-block">
	                		<div class="row">
	                			<!-- Block heading -->
	                			<div class="col-sm-12">
                    				<div class="heading-title page-title">
                                    	<h1 class="page-title"><i class="fa fa-envelope"></i>SMS</h1>                                        
                                        <a class="btn black-btn pull-right" href="<?=base_url('manage_admin/dashboard');?>"><i class="fa fa-long-arrow-left"></i>Dashboard</a>
                                    </div>   
                                </div>
                                <?php if($page_view === 'send'){ ?>
                                <!-- SMS Area -->
	                			<div class="col-sm-12" style="margin: 30px 0;">
	                				<form action="javascript:void(0)" method="POST" role="form" id="js-send-sms-form">
	                					<!-- Message mode -->
	                					<div class="form-group">
		                					<div class="control control--radio">
		                						<label>
		                							<input type="radio" name="txt_message_mode" id="js-sandbox" class="js-message-mode" value="sandbox" checked="checked" />
		                							Sandbox
		                							<div class="control__indicator"></div>
		                						</label>
		                					</div>
											&nbsp;&nbsp;&nbsp;
		                					<div class="control control--radio">
		                						<label>
		                							<input type="radio" name="txt_message_mode" id="js-production" class="js-message-mode" value="production" />
		                							Production
		                							<div class="control__indicator"></div>
		                						</label>
		                					</div>
	                					</div>
	                				
	                					<!-- Sender field -->
	                					<div class="form-group js-sender-phone-row">
	                						<label>Sender Phone Number <span class="cs-required">*</span></label>
	                						<select id="js-sender-phone-number" style="width: 100%;">
                								<option value="MG359e34ef1e42c763d3afc96c5ff28eaf" data-type="primary" selected="true">+1 559 702 8989 (Default)</option>
                								<option value="MG9edd56970bbc26e38cab2855cb360c95" data-type="secondary_one">+1 909 757 0288</option>
	                						</select>
	                					</div>

	                					<!-- Receiver field -->
	                					<div class="form-group">
	                						<label>Receiver Phone Number <span class="cs-required">*</span></label>
	                						<div class="input-group">
	                							<div class="input-group-addon">+1</div>
	                							<input type="text" class="form-control js-receiver-phone js-sandbox-receiver-phone" name="txt_receiver_phone_sandbox" value="<?=phonenumber_format('571981265131', true);?>" readonly="true" />
	                							<input type="text" class="form-control js-receiver-phone js-production-receiver-phone" name="txt_receiver_phone_production" required="true" value="(___) ___-____" />
	                						</div>
	                					</div>


	                					<!-- Message field -->
	                					<div class="form-group">
	                						<label>Message<span class="cs-required">*</span></label>
	                						<textarea name="txt_message" rows="10" class="form-control js-message"></textarea>
	                						<p><span class="js-words">0</span> words / <span class="js-sms">0</span> sms (160 words/sms )</p>
	                					</div>

	                					<button type="submit" class="btn btn-primary">Send SMS</button>
	                				</form>
                                </div>
                            	<?php } ?>
                                <!-- SMS display area -->
                                <div class="col-sm-12 js-sms-pagination" style="margin: 30px 0 0;"></div>
                                <?php if($page_view === 'view'){ ?>
                                <div class="col-sm-12">
                                	<div class="table-responsive">
                                		<div class="cs-table-loader js-table-loader"><i class="fa fa-spinner fa-spin"></i></div>
                                		<table class="table table-hover" id="js-sms-table">
                                			<thead>
                                				<tr>
                                					<th>Receiver Phone Number</th>
                                					<!-- <th>Last Message</th> -->
                                					<th>Module</th>
                                					<th>Sent at</th>
                                					<th>Action</th>
                                				</tr>
                                			</thead>
                                			<tbody>
                                				<tr>
                                					<td colspan="5">
                                						<p class="text-center js-error-msg">Please wait, while we are fetching sms</p>
                                					</td>
                                				</tr>
                                			</tbody>
                                		</table>
                                	</div>
                                </div>
                                <div class="col-sm-12 js-sms-pagination"></div>
                            	<?php } ?>
	                		</div>
	                	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loader -->
<div class="text-center cs-loader js-loader">
    <div id="file_loader" class="cs-loader-file"></div>
    <div class="cs-loader-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="cs-loader-text">Please wait...</div>
    </div>
</div>


<script>
	$(function(){
		
		<?php if($page_view === 'send'){ ?>
			var word_limit = 160;
			set_view();
			//
			$('.js-message').keyup(function(event) {
				var total_words = $(this).val().length;
				$('.js-words').text(total_words);
				$('.js-sms').text(total_words === 0 ? 0 : (total_words <= word_limit ? 1 : Math.ceil(total_words/word_limit)));
			});

			// Event Handlers
			// Toggle view for 'sandbox' and 'production'
			$('.js-message-mode').click(set_view);

			// Send sms
			$('#js-send-sms-form').submit(function(e){
				e.preventDefault();
				$('.js-message-error').remove();
				$('.js-receiver-phone-error').remove();
				var megaOBJ = {},
				is_error = false;
				megaOBJ.sms_type = $('#js-production').prop('checked') === true ? 'production' : 'sandbox';
				megaOBJ.message  = $('.js-message').val();
				megaOBJ.receiver_phone_number = megaOBJ.sms_type === 'sandbox' ? '+5571981265131' : $('.js-production-receiver-phone').val().trim();
				megaOBJ.sender_phone_number = megaOBJ.sms_type === 'sandbox' ? '' : $('#js-sender-phone-number').val();
				megaOBJ.sender_phone_number_type = megaOBJ.sms_type === 'sandbox' ? '' : $('#js-sender-phone-number').select2().find(':selected').data('type');

				// Validate
				if(megaOBJ.message.trim() === ''){
					$('.js-message').parent().append('<span class="js-message-error cs-error">Message is required</span>');
					is_error = true;
				}

				if(megaOBJ.receiver_phone_number.trim() === ''){
					$('.js-receiver-phone').parent().parent().append('<span class="js-receiver-phone-error cs-error">Receiver phone number is required</span>');
					is_error = true;
				}

				if(!fpn(megaOBJ.receiver_phone_number.trim(), '', true)){
					$('.js-receiver-phone').parent().parent().append('<span class="js-receiver-phone-error cs-error">Receiver phone number is invalid. (XXX) XXX-XXXX</span>');
					is_error = true;
				}

				if(is_error) return false;

				// Reset phone number
				if(megaOBJ.sms_type === 'production')
					megaOBJ.receiver_phone_number = '+1'+(megaOBJ.receiver_phone_number.replace(/[\D]/g, ''));

				//
				megaOBJ.action = 'send_sms';
				//
				loader('show');
				//
				$.post("<?=base_url('manage-admin/sms/handler');?>", megaOBJ, function(resp) {
					if(resp.Status === false){
						loader('hide');
						alertify.alert('Error!', resp.Response, function(){ return; });
						return;
					}
					//
					loader('hide');
					//
					alertify.alert('Success!', resp.Response, function(){ return; });
					$('.js-message').val('');
					$('.js-production-receiver-phone').val('(___) ___-____');
					// Refresh smd item list
					pOBJ['fetch_sms']['page'] = 1;
					pOBJ['fetch_sms']['totalPages'] = pOBJ['fetch_sms']['totalRecords'] = 0;
					fetch_sms();
				});
			});

			// Phone number formatter
			$('.js-production-receiver-phone').keyup(function(event) {
				var num = fpn($(this).val().trim());
				if(typeof(num) === 'object'){
					$(this).val(num.number);
					setCaretPosition(this, num.cur);
				}else $(this).val(num);
			});

			//
			$('.js-production-receiver-phone').trigger('keyup');
		
			//
			function set_view(){
				$('.js-sender-phone-row').hide(0);
				//
				$('.js-receiver-phone').hide(0).prop('required', false);
				if( $('#js-production').prop('checked') === true){
					$('.js-sender-phone-row').show(0);
					$('.js-production-receiver-phone').val('(___) ___-____');
					$('.js-production-receiver-phone').show().prop('required', true);
				} else $('.js-sandbox-receiver-phone').show();
			}

			$('#js-sender-phone-number').select2();
		<?php } ?>

		<?php if($page_view === 'view'){ ?>
			// Set pagination object
			var pOBJ = { 
				'fetch_sms' : {
					page: 1,
					totalPages: 0,
					totalRecords: 0,
					cb: fetch_sms
				},
				'fetch_detail' : {
					page: 1,
					totalPages: 0,
					totalRecords: 0,
					cb: fetch_detail
				}
			},
			current_page = 1;

			// Capture document links
			$(document).on('click', '.js-details', fetch_detail);
			$(document).on('hide.bs.modal', '#js-detail-modal', function(){
				$(this).remove();
				pOBJ['fetch_detail']['page'] = 1;	
				pOBJ['fetch_detail']['totalRecords'] = 0;
				pOBJ['fetch_detail']['totalPages'] = 0;
	    	});

		    fetch_sms();

			//
			function table_loader(do_show){
				if(do_show === undefined || do_show === true || do_show.toLowerCase() === 'show') $('.js-table-loader').show();
				else $('.js-table-loader').fadeOut(500);
			}

		    // Fetch SMS
		    function fetch_sms(){
		    	table_loader('show');
		    	$.post("<?=base_url('manage-admin/sms/handler');?>",{
		    		action: 'fetch_sms',
		    		type: 'admin',
		    		page: pOBJ['fetch_sms']['page'],
		    		total_records: pOBJ['fetch_sms']['totalRecords']
		    	}, function(resp) {
		    		if(resp.Status === false){
		    			$('.js-error-msg').html(resp.Response);
		    			table_loader('hide');
		    			return;
		    		}
		    		set_table(resp);
		    	});
		    }

		    // Load records onto table
		    function set_table(resp){
		    	var rows = '';
		    	$.each(resp.Data, function(i, v) {
		    		rows += '<tr>';
		    		rows += '	<td>'+(format_pn(v.receiver_phone_number))+'</td>';
		    		// rows += '	<td>'+(v.message_body)+'</td>';
		    		rows += '	<td>'+(v.module_slug.toUpperCase())+'</td>';
		    		rows += '	<td>'+(moment(v.created_at, 'YYYY-MM-DD hh:mm:ss').format('LLLL'))+'</td>';
		    		rows += '	<td><a href="javascript:void(0)" data-key="'+(v.receiver_phone_number)+'" class="btn btn-success js-details">Details</a>'+( v.is_read > 0 ? '<img src="<?=base_url('assets');?>/images/new_msg.gif" width="30"  style="margin-left: 10px"/>' : '' )+'</td>';
		    		rows += '</tr>';
		    	});

		    	//
		    	if(pOBJ['fetch_sms']['page'] == 1) {
		    		pOBJ['fetch_sms']['totalPages'] = resp.TotalPages;
		    		pOBJ['fetch_sms']['totalRecords'] = resp.TotalRecords;
		    	}
		    	//
		    	load_pagination(
					resp.Limit, 
					resp.ListSize,
					$('.js-sms-pagination'),
					'fetch_sms'
				);
				// 

		    	$('#js-sms-table').find('tbody').html(rows);
		    	table_loader('hide');
		    }

		    //
		    function fetch_detail(e){
		    	var _this = $(this);
		    	var megaOBJ = {};
		    	megaOBJ.page = pOBJ['fetch_detail']['page'];
		    	megaOBJ.action = 'fetch_detail_by_phone';
		    	megaOBJ.phone_number = $(this).data('key').trim();
		    	megaOBJ.total_records = pOBJ['fetch_detail']['totalRecords'];
		    	//
		    	if(megaOBJ.phone_number == ''){
		    		alertify.alert('Error!', 'Phone number can not be empty', function(){ return; });
		    		return;
		    	}
		    	table_loader('show');
		    	//
		    	$.post("<?=base_url('manage-admin/sms/handler')?>", megaOBJ, function(resp) {
		    		if(resp.Status === false){
		    			table_loader('hide');
		    			alertify.alert('Error!', resp.Response, function(){ return; });
		    			return;
		    		}
		    		_this.parent().find('img').remove();
		    		//
		    		load_detail_view(megaOBJ.phone_number, resp);
		    	});
		    }

		    //
		    function load_detail_view(pn, resp){

		    	var tableRows = '';
		    	$.each(resp.Data, function(i, v) {
		    		var cl = i%2 === 0 ? 'style="background-color:#f9f9f9;"' : '';
					tableRows += '<tr '+( cl )+'>';
					tableRows += '	<td>'+( format_pn(v.sender_phone_number) )+'</td>';
					tableRows += '	<td>'+( format_pn(v.receiver_phone_number) )+'</td>';
					tableRows += '	<td>'+( v.module_slug.toUpperCase() )+'</td>';
					tableRows += '	<td><label class="label label-'+( v.message_mode === 'sandbox' ? 'warning' : 'success')+'">'+(v.message_mode)+'</label></td>';
					tableRows += '	<td>'+( v.is_sent == 1 ? '<label class="label label-success">Sent</label>' : '<label class="label label-warning">Received</label>' )+'</td>';
					// tableRows += '	<td>'+( moment(v.created_at, 'YYYY-MM-DD hh:mm:ss').format('LLLL') )+'</td>';
					tableRows += '</tr>';
					tableRows += '<tr '+( cl )+'>';
					tableRows += '	<td colspan="6"><p><strong>Message:</strong>&nbsp;&nbsp;'+( v.message_body )+' </p><br /><p><strong>Sent on:</strong>&nbsp;'+( moment(v.created_at, 'YYYY-MM-DD hh:mm:ss').format('LLLL') )+'</p></td>';
					tableRows += '</tr>';
				});

		    	var modal = '';
		    	modal += '<div class="modal fade" id="js-detail-modal">';
		    	modal += '<div class="modal-dialog modal-lg">';
		    	modal += '		<div class="modal-content">';
		    	modal += '			<div class="modal-header" style="background-color: #81b431;">';
		    	modal += '				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
		    	modal += '				<h4 class="modal-title">SMS details of: '+(format_pn(pn))+'</h4>';
		    	modal += '			</div>';
		    	modal += '			<div class="modal-body" id="js-sms-detail">';
			    modal += '				<div class="col-sm-12 js-sms-detail-pagination"></div>';
			    modal += '				<div class="col-sm-12">';
			    modal += '				<div class="table-responsive">';
				modal += '		    		<table class="table  table-bordered" id="js-detail-table">';
				modal += '		    			<thead>';
				modal += '		    				<tr>';
				modal += '		    					<th>Sender Number</th>';
				modal += '		    					<th>Receiver Number</th>';
				// modal += '		    					<th>Message</th>';
				modal += '		    					<th>Module</th>';
				modal += '		    					<th>Mode</th>';
				modal += '		    					<th>Type</th>';
				// modal += '		    					<th>Created At</th>';
				modal += '		    				</tr>';
				modal += '		    			</thead>';
				modal += '		    			<tbody>';
				modal += '		    			</tbody>';
				modal += '		    		</table>';
				modal += '		    	</div>';
				modal += '		    	</div>';
			    modal += '				<div class="col-sm-12 js-sms-detail-pagination" style="margin: 30px 0 0;"></div>';
		    	modal += '			</div>';
		    	modal += '			<div class="modal-footer">';
		    	modal += '				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
		    	modal += '			</div>';
		    	modal += '		</div>';
		    	modal += '	</div>';
		    	modal += '</div>';

		    	if($('#js-detail-modal').length == 0){
			    	$('#js-detail-modal').remove();
			    	$('body').append(modal);
		    		$('#js-detail-modal').modal();
		    	}
		    	if(pOBJ['fetch_detail']['page'] == 1){
		    		pOBJ['fetch_detail']['totalRecords'] = resp.TotalRecords;
		    		pOBJ['fetch_detail']['totalPages'] = resp.TotalPages;
		    	}
			    
			    $('#js-detail-table tbody').html(tableRows);
		    	//
		    	load_pagination(
					resp.Limit, 
					resp.ListSize,
					$('.js-sms-detail-pagination'),
					'fetch_detail'
				);
		    	table_loader('hide');
		    }

		    // Pagination
		    // Get previous page
	        $(document).on('click', '.js-pagination-prev', pagination_event);
	        // Get first page
	        $(document).on('click', '.js-pagination-first', pagination_event);
	        // Get last page
	        $(document).on('click', '.js-pagination-last', pagination_event);
	        // Get next page
	        $(document).on('click', '.js-pagination-next', pagination_event);
	        // Get page
	        $(document).on('click', '.js-pagination-shift', pagination_event);

	        // TODO convert it into a plugin
	        function load_pagination(limit, list_size, target_ref, page_type){
	        	//
	        	var obj = pOBJ[page_type];
	            // parsing to int           
	            limit = parseInt(limit);
	            obj['page'] = parseInt(obj['page']);
	            // get paginate array
	            var page_array = paginate(obj['totalRecords'], obj['page'], limit, list_size);
	            // append the target ul
	            // to top and bottom of table
	            target_ref.html('<ul class="pagination cs-pagination js-pagination"></ul>');
	            // set rows append table
	            var target = target_ref.find('.js-pagination');
	            // get total items number
	            var total_records = page_array.total_pages;
	            // load pagination only there
	            // are more than one page
	            if(obj['totalRecords'] >= limit) {
	                // generate li for
	                // pagination
	                var rows = '';
	                // move to one step back
	                rows += '<li><a href="javascript:void(0)" data-page-type="'+(page_type)+'" class="'+(obj['page'] == 1 ? '' : 'js-pagination-first')+'">First</a></li>';
	                rows += '<li><a href="javascript:void(0)" data-page-type="'+(page_type)+'" class="'+(obj['page'] == 1 ? '' : 'js-pagination-prev')+'">&laquo;</a></li>';
	                // generate 5 li
	                $.each(page_array.pages, function(index, val) {
	                    rows += '<li '+(val == obj['page'] ?  'class="active"' : '')+'><a href="javascript:void(0)" data-page-type="'+(page_type)+'" data-page="'+(val)+'" class="'+(obj['page'] != val ? 'js-pagination-shift' : '')+'">'+(val)+'</a></li>';
	                });
	                // move to one step forward
	                rows += '<li><a href="javascript:void(0)" data-page-type="'+(page_type)+'" class="'+(obj['page'] == page_array.total_pages ? '' : 'js-pagination-next')+'">&raquo;</a></li>';
	                rows += '<li><a href="javascript:void(0)" data-page-type="'+(page_type)+'" class="'+(obj['page'] == page_array.total_pages ? '' : 'js-pagination-last')+'">Last</a></li>';
	                // append to ul
	                target.html(rows);
	            }
	            // remove showing
	            target.find('.js-show-record').remove();
	            // append showing of records
	            target.before('<span class="pull-left js-show-record" style="margin-top: 27px; padding-right: 10px;">Showing '+(page_array.start_index + 1)+' - '+(page_array.end_index != -1 ? (page_array.end_index + 1) : 1)+' of '+(obj['totalRecords'])+'</span>');
	        }
	        // Paginate logic
	        function paginate(total_items, current_page, page_size, max_pages) {
	            // calculate total pages
	            var total_pages = Math.ceil(total_items / page_size);

	            // ensure current page isn't out of range
	            if (current_page < 1) current_page = 1;
	            else if (current_page > total_pages) current_page = total_pages;

	            var start_page, end_page;
	            if (total_pages <= max_pages) {
	                // total pages less than max so show all pages
	                start_page = 1;
	                end_page = total_pages;
	            } else {
	                // total pages more than max so calculate start and end pages
	                var max_pagesBeforecurrent_page = Math.floor(max_pages / 2);
	                var max_pagesAftercurrent_page = Math.ceil(max_pages / 2) - 1;
	                if (current_page <= max_pagesBeforecurrent_page) {
	                    // current page near the start
	                    start_page = 1;
	                    end_page = max_pages;
	                } else if (current_page + max_pagesAftercurrent_page >= total_pages) {
	                    // current page near the end
	                    start_page = total_pages - max_pages + 1;
	                    end_page = total_pages;
	                } else {
	                    // current page somewhere in the middle
	                    start_page = current_page - max_pagesBeforecurrent_page;
	                    end_page = current_page + max_pagesAftercurrent_page;
	                }
	            }

	            // calculate start and end item indexes
	            var start_index = (current_page - 1) * page_size;
	            var end_index = Math.min(start_index + page_size - 1, total_items - 1);

	            // create an array of pages to ng-repeat in the pager control
	            var pages = Array.from(Array((end_page + 1) - start_page).keys()).map(i => start_page + i);

	            // return object with all pager properties required by the view
	            return {
	                total_items: total_items,
	                // current_page: current_page,
	                // page_size: page_size,
	                total_pages: total_pages,
	                start_page: start_page,
	                end_page: end_page,
	                start_index: start_index,
	                end_index: end_index,
	                pages: pages
	            };
	        }
	        //
	        function pagination_event(){
	        	//
	        	var i = $(this).data('page-type');
	        	// When next is press
				if($(this).hasClass('js-pagination-next') === true){
					pOBJ[i]['page'] = pOBJ[i]['page'] + 1;
					pOBJ[i]['cb']($(this));
				} else if($(this).hasClass('js-pagination-prev') === true){
					pOBJ[i]['page'] = pOBJ[i]['page'] - 1;
					pOBJ[i]['cb']($(this));
				} else if($(this).hasClass('js-pagination-first') === true){
					pOBJ[i]['page'] = 1;
					pOBJ[i]['cb']($(this));
				} else if($(this).hasClass('js-pagination-last') === true){
					pOBJ[i]['page'] = pOBJ[i]['totalPages'];
					pOBJ[i]['cb']($(this));
				} else if($(this).hasClass('js-pagination-shift') === true){
					pOBJ[i]['page'] = parseInt($(this).data('page'));
					pOBJ[i]['cb']($(this));
				}
	        }
    	<?php } ?>


		loader('hide');

		//
		function loader(do_show){
			if(do_show === undefined || do_show === true || do_show.toLowerCase() === 'show') $('.js-loader').show();
			else $('.js-loader').fadeOut(500);
		}

    	//
	    function format_pn(pn){
	    	var cpn = pn.replace(/\D/g, ''),
	    	preg = /(\d{1})(\d{3})(\d{3})(\d{4,6})/,
	    	match = cpn.match(preg);
	    	if(match.length === 0) return pn;
	    	return '+'+(match[1]+' ('+match[2]+') '+match[3]+'-'+match[4]);
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
	})
</script>


<style>
	.cs-error{ color: #cc0000; }

	/*Table loader*/
	.cs-table-loader{ position: absolute; top: 0; bottom: 0; right: 0; left: 0; background: rgba(255,255,255,.5); }
	.cs-table-loader i{ font-size: 30px; text-align: center; display: block; margin-top: 40px; }

	/*Pagination*/
	.cs-pagination{ float: right; }
	.cs-pagination li a{ background-color: #81b431; color: #ffffff; }
	/*.cs-pagination li{ display: inline-block; margin-right: 5px; background-color: #818228; }*/
</style>