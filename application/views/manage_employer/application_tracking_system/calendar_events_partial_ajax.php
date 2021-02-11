<?php //error_reporting(0); ?>
<!-- lodash -->
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.11/lodash.min.js"></script>
<script src="<?=base_url();?>assets/calendar/moment.min.js"></script>

<?php 
    //
    $calendar_opt = $this->config->item('calendar_opt');
    $event_color_array = get_calendar_event_color();
    $event_obj = $calendar_opt['event_types'];
    $event_status_array = $calendar_opt['event_status'];
    $recur_months = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
    $user_type = isset($applicant_sid) ? 'applicant' : 'employee'; 
    $user_id   = isset($applicant_sid) ? $applicant_sid : $employer_id; 
    $users_type= isset($applicant_sid) ? 'Applicant' : 'Employee'; 
    
    $pass_array = array(
        'calendar_opt' => $calendar_opt,
        'event_color_array' => $event_color_array,
        'event_obj' => $event_obj,
        'event_status_array' => $event_status_array,
        'recur_months' => $recur_months,
        'user_id' => $user_id,
        'users_type' => $users_type,
        'show_online_videos' => $calendar_opt['show_online_videos']
    );

    // Add borderes for event status
    echo '<style>';
    foreach ($event_color_array as $k0 => $v0) {
        echo '.cs-event-btn-'.$k0.' {';
        echo ' background-color: '.$v0.' !important;';
        echo ' color: #ffffff !important;';
        echo '}';
    }
    echo '</style>';


?>


<script>
    var  event_status_obj = <?=@json_encode($event_status_array);?>,
    // Set color object
    // for categories
    event_obj = <?=@json_encode($event_obj);?>,
    event_color_obj = <?=@json_encode($event_color_array);?>,
    event_type = <?=@json_encode($calendar_opt['event_type_info']);?>,
    default_start_time = "<?=$calendar_opt['default_start_time'];?>",
    default_end_time = "<?=$calendar_opt['default_end_time'];?>",
    // Set recur days
    recurr_days = <?=@json_encode($calendar_opt['recur_days']);?>,
    // Set months
    recurr_months = <?=json_encode($recur_months);?>,
    //
    default_recur = <?=$calendar_opt['recur_active'];?>,
    // Set recurr defaults
    default_days = {1: true, 2: true, 3: true, 4: true, 5: true},
    default_weeks = 2,
    default_end_date = moment().utc().add('+20', 'weeks').format('MM-DD-YYYY'),
    default_months = {is_all: true},
    // Phone validation regex
    // @accepts
    // Number, Hyphens, Underscrores, Brackets
    phone_regex = new RegExp(/^[+]?[\s./0-9]*[(]?[0-9]{1,4}[)]?[_-\s./0-9]*$/g),
    // phone_regex = new RegExp(/(\(\d{3}\))\s(\d{3})-(\d{4})$/),
    email_regex = new RegExp(/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/),
    digit_regex = new RegExp(/^[0-9]+$/g),
    url_regex   = new RegExp(/(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/),
    //
    edit_mode = '',
    user_type = "<?= $this->uri->segment(1) == 'employee_profile' ? 'employee' : 'applicant';?>",
    show_recur_btn = <?=$calendar_opt['recur_active'];?>,
    show_clone_btn = 0,
    show_request_status_history_btn = 1,
    show_sent_reminder_history_btn = 1,
    event_title_text = 'Event Management'
    ;

    
    function func_toggle_visibility(checkbox_id, container_id) {

        var checked = $('#' + checkbox_id).prop('checked');

        if (checked) {

            $('#' + container_id).show();

        } else {

            $('#' + container_id).hide();

        }
    }

    function func_make_time_pickers() {

        $('.start_time').datetimepicker({

            datepicker: false,

            format: 'g:iA',

            formatTime: 'g:iA',

            step: 15,

            onChangeDateTime: function (dp, $input) {

                $('.end_time').datetimepicker({

                    minTime: $input.val()

                });

            }

        });



        $('.end_time').datetimepicker({

            datepicker: false,

            format: 'g:iA',

            formatTime: 'g:iA',

            step: 15,

            onChangeDateTime: function (dp, $input) {

                $('.start_time').datetimepicker({

                    maxTime: $input.val()

                });

            }

        });
    }

    function func_make_date_picker() {

        $('.eventdate').datepicker({

            dateFormat: 'mm-dd-yy'

        });
    }

    function func_get_past_events(user_type, user_sid) {

        var my_request;



        $('#tab_content').hide();

        $('#tab_loader').show();



        my_request = $.ajax({

            url: '<?php echo base_url('calendar/ajax_responder'); ?>',

            type: 'POST',

            data: {

                'perform_action': 'get_past_events',

                'user_type': user_type,

                'user_sid': user_sid

            }

        });



        my_request.done(function (response) {

            $('#tab_content').show();

            $('#tab_loader').hide();



            $('#tab_content').html(response);

            $('.loader').hide();

        });



        my_request.always(function () {

            $('#tab_content').show();

            $('#tab_loader').hide();



        });
    }

</script>


<div class="form-wrp">

    <form action="<?php echo base_url('calendar/tasks'); ?>" name="event_form" id="add_event_form" method="POST" enctype="multipart/form-data">

        <div class="form-title-section">

            <h2>Calendar & Scheduling</h2>

            <div class="form-btns event_detail">

                <input type="button" id="add_event" value="Add Event">

            </div>

            <div class="form-btns event_create" style="display: none">

                <input type="submit" value="Save">

                <input onclick="cancel_event()" type="button" value="Cancel">

            </div>

        </div>


        <input type="hidden" name="applicant_sid" value="<?=isset($applicant_sid) ? $applicant_sid : $employer_id;?>">

        <input type="hidden" name="employee_sid" value="0">

        <input type="hidden" name="employers_sid" value="<?php echo $user_sid ?>">

        <input type="hidden" name="users_type" value="applicant">

        <input type="hidden" name="applicant_email" value="<?php echo $email ?>">

        <input type="hidden" name="action" value="save_event">

        <input type="hidden" name="redirect_to" value="<?=isset($applicant_sid) ? 'applicant_profile' : 'employee_profile';?>">

        <input type="hidden" name="redirect_to_user_sid" value="<?=isset($applicant_sid) ? $applicant_sid : $employer_id;?>">

        <input type="hidden" name="redirect_to_job_list_sid" value="<?=isset($applicant_sid) ? $job_list_sid : '';?>">

        <?php $this->load->view('calendar/add_event_form_partial_ajax', $pass_array); ?>


        <div class="form-btns event_create" style="display: none">

            <input type="submit" value="Save">

            <input onclick="cancel_event()" type="button" value="Cancel">

        </div>

    </form>

</div>



<div class="event_detail">

    <div class="row">

        <div class="col-xs-12">

            <ul class="nav nav-tabs nav-justified">

                <li class="active"><a data-toggle="tab" href="#upcoming_events">Upcoming Events</a></li>

                <li onclick='func_get_past_events("<?=$user_type;?>", <?=$user_id;?>)'><a data-toggle="tab" href="#past_events">Past Events</a></li>

            </ul>

            <div class="tab-content">

                <div id="upcoming_events" class="tab-pane fade in active hr-innerpadding">

                    <div class="row">

                        <div class="col-xs-12">

                            <br />

                <?php       $view_data = array();

                            $view_data['events'] = $upcoming_events;

                            $view_data['employees'] = $company_accounts;

                            $view_data['employer_id'] = $employer_id;

                            $this->load->view('calendar/list_events_partial_ajax', $view_data); ?>

                        </div>

                    </div>

                </div>

                <div id="past_events" class="tab-pane fade">

                    <div class="row">

                        <div class="col-xs-12">

                            <div id="tab_loader" class="text-center" style="height: 400px; padding: 100px; color: rgb(77, 160, 0);">

                                <i class="fa fa-spin fa-cog" style="font-size: 200px; opacity: 0.25;"></i>

                            </div>

                            <div id="tab_content"></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



<?php 
    $this->load->view('calendar/edit_event_form_partial_ajax', $pass_array); 
?>

<script>
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
</script>