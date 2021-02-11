<script src="<?=base_url("assets/js/moment.min.js")?>"></script>
<link rel="stylesheet" href="<?=base_url('assets');?>/css/timeoffstyle.css">
<style>
    /* #js-timeoff-modal{ z-index: 9999 !important; } */
</style>
<!-- Modal -->
<div class="modal fade" id="js-timeoff-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content model-content-custom">
            <!-- loader -->
            <div 
                class="loader js-ilcr"
                style="position: absolute; top: 0; right: 0; bottom: 0; left: 0; width: 100%; background: rgba(255,255,255,.8); z-index: 9999 !important; display: none;"
            >
                <i 
                    class="fa fa-spinner fa-spin"
                    style="text-align: center; top: 50%; left: 50%; font-size: 40px; position: relative;"
                ></i>
            </div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create a Time off Request</h4>
            </div>
            <div class="modal-body full-width modal-body-custom">
                <div class="tab-content">
                    <div id="" class="">
                        <br />
                        <!-- Section One -->
                        <div class="row">
                            <div class="col-sm-6 col-sm-12">
                                <div class="employee-info">
                                    <figure>
                                        <img src="<?php
                                            if($employerData['profile_picture'] != ''){
                                                echo AWS_S3_BUCKET_URL.$employerData['profile_picture'];
                                            } else{
                                                echo base_url('assets/images/img-applicant.jpg');
                                            }
                                        ?>" class="img-circle emp-image" />
                                    </figure>
                                    <div class="text cs-info-text">
                                        <h4><?=$employerData['first_name'].' '.$employerData['last_name'];?></h4>
                                        <p><a href="<?=base_url('employee_profile');?>/<?=$employerData['sid'];?>" target="_blank">Id: <?=$employerData['employee_number'] ? $employerData['employee_number'] : $employerData['sid'];?></a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Types <span class="cs-required">*</span> </label>
                                    <select id="js-types"></select>
                                </div>
                                <div class="form-group js-policy-box">
                                    <br />
                                    <label>Policies <span class="cs-required">*</span> </label>
                                    <select id="js-policies"></select>
                                </div>
                            </div>
                        </div>
                        <!-- Section Two -->
                        <div class="row">
                            <hr />
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="">Start Date <span class="cs-required">*</span></label>
                                            <input readonly="true" type="text" id="js-timeoff-start-date" class="form-control js-request-start-date" />
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="">End Date <span class="cs-required">*</span></label>
                                            <input readonly="true" type="text" id="js-timeoff-end-date" class="form-control js-request-end-date" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="js-timeoff-date-box" style="display: none;">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Duration Type</th>
                                                        <th>Duration</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Section Three -->
                        <div class="row js-fmla-wrap" style="display: none;">
                            <hr />
                            <div class="col-sm-12 col-sm-12">
                                <label for="">Is this time off under FMLA? <span class="cs-required">*</span></label>
                                <br />
                                <label class="control control--radio font-normal">
                                    No
                                    <input class="js-fmla-check" name="fmla" value="no" type="radio" checked="true" />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;
                                <label class="control control--radio font-normal">
                                    Yes
                                    <input class="js-fmla-check" name="fmla" value="yes" type="radio" />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;

                                <div class="js-fmla-box" style="display: none;">
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Designation Notice
                                        <input class="js-fmla-type-check" name="fmla_check" value="designation" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    &nbsp;<i title="Designation Notice" data-content="<?=FMLA_DESIGNATION;?>" class="fa fa-question-circle js-popover"></i>
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Certification of Health Care Provider for Employee’s Serious Health Condition 
                                        <input class="js-fmla-type-check" name="fmla_check" value="health" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    &nbsp;<i title="Certification of Health Care" data-content="<?=FMLA_CERTIFICATION_OF_HEALTH;?>" class="fa fa-question-circle js-popover"></i>
                                    <br />
                                    <label class="control control--radio font-normal">
                                        Notice of Eligibility and Rights & Responsibilities 
                                        <input class="js-fmla-type-check" name="fmla_check" value="medical" type="radio" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    &nbsp;<i title="Notice of Eligibility and Rights & Responsibilities" data-content="<?=FMLA_RIGHTS;?>" class="fa fa-question-circle js-popover"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Section Four -->
                        <div class="row js-vacation-row" style="display: none;">
                            <hr />
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Emergency Contact Number</label>
                                    <input type="text" class="form-control" id="js-vacation-contact-number" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Return Date</label>
                                    <input type="text" class="form-control" id="js-vacation-return-date" readonly="true" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>Alternate Temporary Address</label>
                                    <input type="text" class="form-control" id="js-vacation-address" />
                                </div>
                            </div>
                        </div>

                        <!-- Section Four -->
                        <div class="row js-bereavement-row" style="display: none;">
                            <hr />
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Relationship</label>
                                    <input type="text" class="form-control" id="js-bereavement-relationship" />
                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Return Date</label>
                                    <input type="text" class="form-control" id="js-bereavement-return-date" readonly="true" />
                                </div>
                            </div>
                        </div>

                        <!-- Section Four -->
                        <div class="row js-compensatory-row" style="display: none;">
                            <hr />
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Compensation Date</label>
                                    <input type="text" class="form-control" id="js-compensatory-return-date" readonly="true" />
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>Compensation Start Time</label>
                                    <input type="text" class="form-control" id="js-compensatory-start-time" readonly="true" />
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label>Compensation End Time</label>
                                    <input type="text" class="form-control" id="js-compensatory-end-time" readonly="true" />
                                </div>
                            </div>
                        </div>
                        <!-- Section Four -->
                        <div class="row">
                            <hr />
                            <div class="col-sm-12 col-sm-12">
                                <div class="form-group">
                                    <label>Reason</label>
                                    <textarea id="js-reason"></textarea>
                                </div>
                            </div>
                        </div>
                         <!--  -->
                        <div class="row m15">
                            <hr />
                            <div class="col-sm-12">
                                <h4>
                                    Supporting documents
                                    <span class="pull-right">
                                        <button class="btn btn-success js-timeoff-attachment" style="background-color: #3554dc"><i class="fa fa-plus"></i>&nbsp; Add Document</button>
                                    </span>
                                </h4>
                            </div>
                            <div class="col-sm-12">
                                <br />
                                <div class="reponsive">
                                    <table class="table table-striped table-bordered" id="js-attachment-listing">
                                        <thead>
                                            <tr>
                                                <th>Document Title</th>
                                                <th class="col-sm-3">Document Type</th>
                                                <th class="col-sm-2">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="js-no-records">
                                                <td colspan="3">
                                                    <p class="alert alert-info text-center">You haven't attached any documents yet.</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-5" id="js-save-btn">Create Request</button>
                <button type="button" class="btn btn-info btn-5" data-dismiss="modal" id="js-cancel-btn">Cancel</button>
            </div>
        </div>
  </div>
</div>

<!-- FMLA model -->
<div class="modal fade" id="js-fmla-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
               <!-- FMLA forms -->
                <div class="js-page" id="js-fmla">
                    <span class="pull-right" style="margin: 5px 10px 20px;"><button class="btn btn-info btn-5 js-shift-page">Back</button></span>
                    
                    <div class="js-form-area" data-type="health" style="display: none;">
                        <?php $this->load->view('timeoff/fmla/employee/health'); ?>
                    </div>
                    <div class="js-form-area" data-type="medical" style="display: none;">
                        <?php $this->load->view('timeoff/fmla/employee/medical'); ?>
                    </div>
                   
                </div> 
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var baseURI = "<?=base_url();?>timeoff/",
    handlerURI = "<?=base_url();?>timeoff/handler";
    $(function(){
        var
        policies = [],
        format = 'H',
        holidayDates = <?=json_encode($holidayDates);?>,
        timeOffDays = <?=json_encode($timeOffDays);?>,
        defaultHours = null,
        defaultMinutes = null,
        defaultTimeslot = null,
        xhr = null,
        fmla = {};
        //
        $('.select2').select2();
        //
        $('#js-to-box').click(function(e){
            e.stopPropagation();
            window.location = "<?=base_url('timeoff/lms/')?>";
        });

        var offdates = [];

        //
        if(holidayDates.length != 0){
            for(obj in holidayDates){
                if(holidayDates[obj]['work_on_holiday'] == 0) offdates.push(holidayDates[obj]['from_date']);
            }
        }

        var inObject = function(val, searchIn){
            for(obj in searchIn){
                if( searchIn[obj].diff >= 2 ){
                    if(searchIn[obj]['from_date'] <= val  && searchIn[obj]['to_date'] >= val) return searchIn[obj];
                }else{
                    if(searchIn[obj]['from_date'] == val) return searchIn[obj];
                }
            }
            return -1;
        };
        

        function unavailable(date) {
            var dmy = moment(date).format('MM-DD-YYYY');
            var t = inObject(dmy, holidayDates);
            if (t == -1) {
                return [true, ""];
            } else {
                if (t.work_on_holiday == 1) {
                    return [true, "set_disable_date_color", t.holiday_title];
                } else {
                    return [false, "", t.holiday_title];
                }
            }
        }
        //
        CKEDITOR.replace('js-reason');
        //
        $('#js-create-pto').click(function(e){
            e.preventDefault();
            e.stopPropagation();
            $('#js-timeoff-modal').modal({
                keyboard: false,
                backdrop: 'static'
            });
        })

        $('.js-partial-check').click(function(){
            if($(this).val() == 'yes')
            $('.js-note-box').show();
            else
            $('.js-note-box').hide();
        })

        $('#js-timeoff-date').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1
        }).val(moment().add('1', 'day').format('MM-DD-YYYY'));

        fetchEmployeePolicies();
        fetchMatricsList();

         // 
    function getPendingTimeOff( o ){
        return new Promise((res, rej) => {
            // Lets create a AJAX request
            $.post(
                "<?=base_url('timeoff/handler')?>",
                o,
                function( resp ){
                    //
                    if(resp.Status === false){
                        alertify.alert('ERROR!', resp.Response);
                        rej();
                        return;
                    }
                    //
                    res(resp.Data);
                }
            );
        }) 
    }


    async function getAdditionalTime(
        megaOBJ, 
        t
    ){
        //
        var r = {
            additionTime: 0,
            isError: false
        };
        // Handle monthly time offs
        if( 
            t.accrual.accrual_frequency != 'none' &&
            t.is_unlimited == '0'
        ){
            //
            var tt = parseInt(t.actual_allowed_timeoff) + parseInt(getNegativeBalance(t));
            //
            var consumedTimeOffArray = await getPendingTimeOff({
                action: 'check_available_time',
                companySid: megaOBJ.companySid,
                employeeSid: megaOBJ.employeeSid,
                policyId: megaOBJ.policyId,
                startDate: megaOBJ.startDate,
                endDate: megaOBJ.endDate,
                employeeTimeSlot: t.employee_timeslot
            });


            // Time off was consumed at some point
            if( consumedTimeOffArray.length !== 0 ){
                //
                var 
                    i = 0,
                    j = 0,
                    il = consumedTimeOffArray.length,
                    jl = megaOBJ.requestedDays.days.length,
                    md,
                    sd,
                    ed,
                    cd,
                    c = {},
                    rs;
                //
                for( i; i < il; i++ ){
                    //
                    sd = moment( consumedTimeOffArray[i]['request_from_date'] ).format('MM');
                    ed = moment( consumedTimeOffArray[i]['request_to_date'] ).format('MM');
                    ct = consumedTimeOffArray[i]['requested_time'];
                    //
                    if(c[sd] === undefined) c[sd] = { month: sd, time: parseFloat(ct) };
                    else c[sd]['time'] = c[sd]['time'] + parseFloat(ct);
                }
                //
                for( j; j < jl; j++ ){
                    //
                    md = moment( megaOBJ.requestedDays.days[j].date ).format('MM');
                    //
                    if(c[md] !== undefined){
                        rs = getArrayFromMinutes(
                            tt - c[md]['time'],
                            t.employee_timeslot,
                            t.format
                        );

                        //
                        if(tt - c[md]['time'] <= 0){
                            alertify.alert('WARNING!', 'You have already consumed the allowed time on <b>'+( megaOBJ.requestedDays.days[j].date )+'</b>.');
                            r.isError = true;
                        } else if( tt - c[md]['time'] < megaOBJ.requestedDays.days[j].time ){
                            alertify.alert('WARNING!', 'You have only <b>'+( rs.text )+'</b> left on <b>'+( megaOBJ.requestedDays.days[j].date )+'</b>.');
                            r.isError = true;
                        } else{
                            r.additionTime += megaOBJ.requestedDays.days[j].time;
                        }
                    }  
                }
            }
        }

        //
        return r;
    }

        //
        function setTimeView(target, prefix, position){
            var row = '';
            if(policies[0]['format'] == 'D:H:M'){
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Days </label>';
                row += '        <input type="text" data-ids="'+( position )+'" data-type="day" class="form-control js-number" id="js-request-days'+( prefix === undefined ? '' : prefix)+'" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" data-ids="'+( position )+'" data-type="hour" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+( getTime(position, 'hour') )+'" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" data-ids="'+( position )+'" data-type="minute" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" value="'+( getTime(position, 'minute') )+'" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(policies[0]['format'] == 'D:H'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Days </label>';
                row += '        <input type="text" data-ids="'+( position )+'" data-type="day" class="form-control js-number" id="js-request-days'+( prefix === undefined ? '' : prefix)+'"  />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" data-ids="'+( position )+'" data-type="hour" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+( getTime(position, 'hour') )+'" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(policies[0]['format'] == 'H:M'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" data-ids="'+( position )+'" data-type="hour" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+( getTime(position, 'hour') )+'" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" data-ids="'+( position )+'" data-type="minute" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" value="'+( getTime(position, 'minute') )+'" />';
                row += '    </div>';
                row += '</div>';
            }else if(policies[0]['format'] == 'H'){
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" data-ids="'+( position )+'" data-type="hour" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+( getTime(position, 'hour') )+'" />';
                row += '    </div>';
                row += '</div>';
            }else{
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" data-ids="'+( position )+'" data-type="minute" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" value="'+( getTime(position, 'minute') )+'" />';
                row += '    </div>';
                row += '</div>';
            }
            //
            if(prefix !== undefined) return row;
            $(target).html(row);
        }

        //
        function fetchEmployeePolicies(){
            if(xhr != null) return;
            xhr = $.post(handlerURI, {
                action: 'get_employee_policies',
                employeeSid: <?=$employerData['sid'];?>,
                companySid: <?=$companyData['sid'];?>
            }, function(resp){
                if(resp.Status === false){
                    console.log('No policies found.');
                    return;
                }

                policies = resp.Data;
                defaultHours = policies[0]['user_shift_hours'],
                defaultMinutes = policies[0]['user_shift_minutes'];
                defaultTimeslot = policies[0]['employee_timeslot'];
                //
                var 
                typeRows = '',
                types = [];
                //
                setTimeView('#js-time');
                typeRows += '<option value="0">[Select a type]</option>';
                policies.map(function(policy){
                    if($.inArray(policy.Category, types) === -1){
                        types.push(policy.Category);
                        typeRows += '<option value="'+( policy.Category )+'">'+( policy.Category )+'</option>';
                    }
                })
                $('.js-policy-box').hide(0);
                $('#js-types').html(typeRows);
                $('#js-types').select2();
            });
        }

        $(document).on('change', '#js-types', function(){
            var rows = '';
            rows += '<option value="0">[Select a policy]</option>';
            var v = $(this).val();
            //
            if(v == 0){
                $('.js-fmla-wrap').hide(0);
                fmla = {};
                $('#js-policies').html('');
                $('#js-policies').select2('destroy');
                $('.js-policy-box').hide(0);
                return;
            }

            if(v.toLowerCase().match(/(fmla)/g) !== null ){
                $('.js-fmla-wrap').show(0);
                fmla = {};
            } else{
                $('.js-fmla-wrap').hide(0);
                $('.js-fmla-check[value="no"]').trigger('click');
                fmla = {};
            }
            // For Vacation
            if(v.toLowerCase().match(/(vacation)/g) !== null ){
                $('.js-vacation-row').show(0);
                fmla = {};
            } else{
                $('.js-vacation-row').hide(0);
                $('.js-vacation-row input').val('');
            }
            // For Bereavement
            if(v.toLowerCase().match(/(bereavement)/g) !== null ){
                $('.js-bereavement-row').show(0);
                fmla = {};
            } else{
                $('.js-bereavement-row').hide(0);
                $('.js-bereavement-row input').val('');
            }
            // For Compensatory
            if(v.toLowerCase().match(/(compensatory)/g) !== null ){
                $('.js-compensatory-row').show(0);
                fmla = {};
            } else{
                $('.js-compensatory-row').hide(0);
                $('.js-compensatory-row input').val('');
            }
            policies.map(function(policy){
                if(policy.Category == v){
                    rows += '<option value="'+( policy.sid )+'">'+( policy.title )+' ('+( policy.timeoff_breakdown !== undefined ? policy.timeoff_breakdown.text : 'Unlimited' )+')</option>';
                }
            });
            $('#js-policies').html(rows);
            $('#js-policies').select2();
            $('.js-policy-box').show();
        })

        //
        function getFormatedPolicyTime(policy){
            if( policy.is_unlimited == 1 ) return ' (Unlimited)';
            var rows = ' (';
            if(policy.format == 'D:H:M'){
                rows += policy.timeoff_breakdown.days+' Days, ';
                rows += policy.timeoff_breakdown.hours+' Hours, ';
                rows += policy.timeoff_breakdown.minute+' Minutes';
            }
            else if(policy.format == 'D:H'){
                rows += policy.timeoff_breakdown.days+' Days, ';
                rows += policy.timeoff_breakdown.hours+' Hours';
            }
            else if(policy.format == 'H:M'){
                rows += policy.timeoff_breakdown.hours+' Hours, ';
                rows += policy.timeoff_breakdown.minute+' Minutes';
            }
            else if(policy.format == 'D'){
                rows += policy.timeoff_breakdown.days+' Days';
            }
            else if(policy.format == 'H'){
                rows += policy.timeoff_breakdown.hours+' Hours';
            }
            else{
                rows += policy.timeoff_breakdown.minutes+' Minutes';
            }

            return rows+')';
        }

        //
        $(document).on('keyup', '.js-number', function(){
            $(this).val(
                $(this).val().replace(/[^0-9]/, '')
            );
        });

        // Add process
        //
        $(document).on('change', '#js-policies', function(e){
            //
            if( $(this).val() == 0) return;
            // Get single policy
            var policy = getPolicy( $(this).val() );
            if(policy['is_unlimited'] == 1) setPolicyTime(0, defaultHours, defaultMinutes);
            else{
                if(format == 'D:H:M') setPolicyTime(
                    policy['timeoff_breakdown']['active']['days'],
                    policy['timeoff_breakdown']['active']['hours'],
                    policy['timeoff_breakdown']['active']['minutes']
                );
                else if(format == 'D:H') setPolicyTime(
                    policy['timeoff_breakdown']['active']['days'],
                    0,
                    policy['timeoff_breakdown']['active']['minutes']
                );
                else if(format == 'H:M') setPolicyTime(
                    0,
                    policy['timeoff_breakdown']['active']['hours'],
                    policy['timeoff_breakdown']['active']['minutes']
                );
                else if(format == 'D') setPolicyTime(
                    policy['timeoff_breakdown']['active']['days'],
                    0,
                    0
                );
                else if(format == 'H') setPolicyTime(
                    0,
                    policy['timeoff_breakdown']['active']['hours'],
                    0
                );
                else setPolicyTime(
                    0,
                    0,
                    policy['timeoff_breakdown']['active']['minutes']
                );
            }
        });

        // Cancel TO from modal
        $(document).on('click', '#js-cancel-btn', function(){
            window.location.reload();
            $('.js-number').val(0);
            $('#js-policies').select2('val', 0);
            $('#js-note').val('');
            CKEDITOR.instances['js-reason'].setData('');
            $('.js-partial-check[value="no"]').trigger('click');
            $('#js-timeoff-date').val(moment().add('1', 'day').format('MM-DD-YYYY'));

        });

        // Save TO from modal
        $(document).on('click', '#js-save-btn', async function(){
            var megaOBJ = {};
            megaOBJ.action = 'create_employee_timeoff';
            megaOBJ.reason = CKEDITOR.instances['js-reason'].getData().trim();
            megaOBJ.policyId = $('#js-policies').val();
            megaOBJ.policyName = $('#js-policies').find('option[value="'+(megaOBJ.policyId)+'"]').text();
            //
            megaOBJ.startDate = $('#js-timeoff-start-date').val();
            megaOBJ.endDate = $('#js-timeoff-end-date').val();
            megaOBJ.doSendTheEmail = true;
            //
            megaOBJ.companySid = <?=$companyData['sid'];?>;
            megaOBJ.employeeSid = <?=$employerData['sid'];?>;
            megaOBJ.employeeFullName = "<?=$employerData['first_name'].' '.$employerData['last_name'];?>";
            megaOBJ.employeeEmail = "<?=$employerData['email'];?>";
            //
            if(megaOBJ.policyId == 0 || megaOBJ.policyId == null){
                alertify.alert('WARNING!', 'Please select a Policy to continue.');
                return;
            }
            //
            if(megaOBJ.startDate == ''){
                alertify.alert('WARNING!', 'Please select the start date.');
                return;
            }
            if(megaOBJ.endDate == ''){
                alertify.alert('WARNING!', 'Please select the end date.');
                return;
            }
            // Get days off
            var requestedDays = getRequestedDays();
            //
            if(requestedDays.error) return;
            //
            megaOBJ.requestedDays = requestedDays;
            //
            var t = getPolicy( megaOBJ.policyId );
            var r = await getAdditionalTime(megaOBJ, t);
            //
            if(r.isError) return;
            var checkBalance = (parseInt(t.actual_allowed_timeoff) + parseInt(getNegativeBalance(t)) + r.additionalTime) - parseInt(t.consumed_timeoff);
            //
            if(t.is_unlimited == '0' && requestedDays.totalTime > checkBalance){
                alertify.alert('WARNING!', 'Requested time off can not be greater than allowed time.');
                return;
            }
            
            megaOBJ.slug = t['format'];
            megaOBJ.timeslot = defaultTimeslot;
            // FMLA 
            megaOBJ.isFMLA = $('.js-fmla-check:checked').val();
            if(megaOBJ.isFMLA == 'yes' && Object.keys(fmla).length == 0){
                alertify.alert('WARNING!', 'Please fill the selected FMLA form.');
                return;   
            }
            megaOBJ.isFMLA = fmla.type;
            megaOBJ.fmla = fmla;
            //
            megaOBJ.allowedTimeOff = getAllowedTimeInMinutes( t );
           
             // Phase 3 code 
            megaOBJ.returnDate = '';
            megaOBJ.relationship = '';
            megaOBJ.temporaryAddress = '';
            megaOBJ.compensationEndTime = '';
            megaOBJ.compensationStartTime = '';
            megaOBJ.emergencyContactNumber = '';
            //
            switch ($('#js-types').val().toLowerCase()) {
                case 'vacation':
                    megaOBJ.returnDate = $('#js-vacation-return-date').val().trim();
                    megaOBJ.temporaryAddress = $('#js-vacation-address').val().trim();
                    megaOBJ.emergencyContactNumber = $('#js-vacation-contact-number').val().trim();
                break;

                case 'bereavement':
                    megaOBJ.returnDate = $('#js-bereavement-return-date').val().trim();
                    megaOBJ.relationship = $('#js-bereavement-relationship').val().trim();
                break;

                case 'compensatory/ in lieu time':
                    megaOBJ.returnDate = $('#js-compensatory-return-date').val().trim();
                    megaOBJ.compensationStartTime = $('#js-compensatory-start-time').val().trim();
                    megaOBJ.compensationEndTime = $('#js-compensatory-end-time').val().trim();
                break;
            }
            //
            //
            // Disable all fields in modal
            $('#js-timeoff-modal').find('input, textarea').prop('disabled', true);
            $('#js-save-btn').val('Saving....');
            // Let's save the TO
            $.post(
                handlerURI,
                megaOBJ,
                function(resp){
                    //
                    $('#js-timeoff-modal').find('input, textarea').prop('disabled', false);
                    $('#js-save-btn').val('SAVE');
                    if(resp.Status === undefined){
                        window.location.reload();
                        return;
                    }
                    // When an error occured
                    if(resp.Status === false){
                        alertify.alert('ERROR!', resp.Response.replace(/{{TIMEOFFDDATE}}/, moment(megaOBJ.requestDate, 'YYYY-MM-DD').format('MM-DD-YYYY')));
                        return;
                    }
                    successMSG = resp.Response;
                    startDocumentUploadProcess(resp.InsertId);
                    // On success
                    // alertify.alert('SUCCESS!', resp.Response);
                    // $('#js-cancel-btn').trigger('click');
                }
            );
        });

 //
        function getRequestedDays(){
            //
            var 
            totalTime = 0,
            err = false,
            arr = [];
            //
            $('#js-timeoff-date-box tbody tr').map(function(i, v){
                if(err) return;
                var time = getTimeInMinutes('el'+( $(this).data('id') ));
                //
                if( time.requestedMinutes <= 0 ){
                    err = true;
                    alertify.alert('WARNING!', 'Please, add request time for date <b>'+( $(this).data('date') )+'</b>.', function(){ return; });
                } else if( time.requestedMinutes > time.defaultTimeslotMinutes ){
                    err = true;
                    alertify.alert('WARNING!', 'Requested time off can not be greater than shift time.', function(){ return; });
                }
                //
                arr.push({
                    date: $(this).data('date'),
                    partial: $(this).find('input[name="'+( i )+'_day_type"]:checked').val(),
                    time: time.requestedMinutes,
                });
                //
                totalTime = parseInt(totalTime) + parseInt(time.requestedMinutes);
            });

            return {
                totalTime: totalTime,
                days: arr,
                error: err
            }
        }

        //
        function getAllowedTimeInMinutes(policy){
            if(policy.is_unlimited) return 0;
            var
            days = 0,
            hours = 0,
            minutes = 0;
            if(policy.format.match(/D/)) days = policy.timeoff_breakdown.active.days;
            if(policy.format.match(/H/)) days = policy.timeoff_breakdown.active.hours;
            if(policy.format.match(/M/)) days = policy.timeoff_breakdown.active.minutes;
            //
            return (days * defaultTimeslot * 60) + (hours * 60) + minutes;
        }

         //
    function getTimeInMinutes(typo){
        typo = typo === undefined ? '' : '-'+typo;
        var
        days = 0,
        hours = 0,
        minutes = 0,
        format = '',
        inText = '';
        //
        if($('#js-request-days'+( typo )+'').length !== 0) { format +='D,'; days = isNaN(parseInt($('#js-request-days'+( typo )+'').val().trim())) ? 0 : parseInt($('#js-request-days'+( typo )+'').val().trim()); }
        if($('#js-request-hours'+( typo )+'').length !== 0) { format += 'H,'; hours = isNaN(parseInt($('#js-request-hours'+( typo )+'').val().trim())) ? 0 : parseInt($('#js-request-hours'+( typo )+'').val().trim()); }
        if($('#js-request-minutes'+( typo )+'').length !== 0) { format += 'M,'; minutes = isNaN(parseInt($('#js-request-minutes'+( typo )+'').val().trim())) ? 0 : parseInt($('#js-request-minutes'+( typo )+'').val().trim()); }
        //
        if(format == 'D:H:M') inText = days+' day'+( days > 1 ? 's' : '' )+', '+hours+' hour'+( hours > 1 ? 's' : '' )+', and '+minutes+' minute'+( minutes > 1 ? 's' : '' );
        else if(format == 'D') inText = days+' day'+( days > 1 ? 's' : '' );
        else if(format == 'H') inText = hours+' day'+( hours > 1 ? 's' : '' );
        else if(format == 'M') inText = minutes+' minute'+( minutes > 1 ? 's' : '' );
        else inText = hours+' hour'+( hours > 1 ? 's' : '' )+', and '+minutes+' minute'+( minutes > 1 ? 's' : '' );
        return  {
            days: days,
            hours: hours,
            minutes: minutes,
            defaultTimeslot: defaultTimeslot,
            defaultTimeslotMinutes: (defaultTimeslot * 60),
            format: format.replace(/,$/, ''),
            formated: inText,
            requestedMinutes: (days * defaultTimeslot * 60) + (hours * 60) + minutes
        };
    }
    function getNegativeBalance(o){
        if(o.accrual.allow_negative_balance == 0) return 0;
        //
        var r = o.accrual.negative_balance;
        //
        if(o.accrual.accrual_method == 'hours_per_month'){
            return r * 60;
        }
        //
        return r * (o.employee_timeslot * 60);
    }

        //
        function setPolicyTime(days, hours, minutes){
            // if(format.match(/D/)) $('#js-request-days').val(days);
            // if(format.match(/H/)) $('#js-request-hours').val(hours);
            // if(format.match(/M/)) $('#js-request-minutes').val(minutes);
        }

        //
        function getPolicy( sid ){
            var i = 0,
            l = policies.length;
            //
            for(i; i < l; i++) if(policies[i]['sid'] == sid) return policies[i];
            return null;
        }

        //
        function fetchMatricsList(){
            $.post(handlerURI, {
                action: 'get_employee_policies_status',
                employeeSid: <?=$employerData['sid'];?>,
                companySid: <?=$companyData['sid'];?>
            }, function(resp){

                $('#js-allowed-time').text(resp.Data.Total.active.hours);
                $('#js-consumed-time').text(resp.Data.Consumed.active.hours);
                $('#js-pending-time').text(resp.Data.Pending.active.hours);
            });
        }

        //
        function startDocumentUploadProcess(
            requestId,
            attachmentId
        ){
            //
            if(attachedDocuments.length === 0 && attachmentId === undefined) {
                alertify.alert('SUCCESS!', successMSG, function(){
                    window.location.reload();
                });
            }
            //
            var currentIndex = attachmentId === undefined ? 0 : attachmentId;
            //
            attachmentId = Object.keys(attachedDocuments)[currentIndex];
            //
            if(attachmentId === undefined){
                alertify.alert('SUCCESS!', successMSG, function(){
                    window.location.reload();
                });
                return;
            }
            //
            currentIndex++;
            //
            var formData = new FormData();
            //
            formData.append('action', 'add_attachment_to_request');
            formData.append('requestSid', requestId);
            formData.append('companySid', <?=$companyData['sid'];?>);
            formData.append('title', attachedDocuments[attachmentId]['title']);
            //
            formData.append(
                attachmentId,
                attachedDocuments[attachmentId]['file']
            );

            $.ajax({
                url: baseURI+'handler',
                data: formData,
                method: 'POST',
                processData: false,
                contentType: false
            }).done(function(resp){
                if(resp.Status === false){
                    alertify.alert('SUCCESS!', successMSG, function(){
                        window.location.reload();
                    });
                    $('#js-cancel-btn').trigger('click');
                    return;
                }
                //
                setTimeout(function(){
                    startDocumentUploadProcess(requestId, currentIndex);
                }, 1000);
            });
        }

        // FMLAs
        $('.js-fmla-check').click(function(e){
            if($(this).val() == 'yes') $('.js-fmla-box').show();
            else{ $('.js-fmla-type-check').prop('checked', false);  fmla = {}; $('.js-fmla-box').hide(); }
        });
        //
        $('.js-fmla-type-check').click(function(e){
            //
            fmla = {};
            if($(this).val() !== 'designation'){
                //
                $('#js-timeoff-modal').modal('toggle');
                $('body').css('overflow', 'hidden');
                $('.js-fmla-employee-firstname').val("<?=$employerData['first_name'];?>");
                $('.js-fmla-employee-lastname').val("<?=$employerData['last_name'];?>");
                $('#js-fmla-modal').find('.modal-title').text('FMLA - '+( $(this).closest('label').text() )+'');
                $('#js-fmla-modal').find('div[data-type="'+( $(this).val() )+'"]').show();
                $('#js-fmla-modal').modal('toggle');
            }else{
                fmla = {
                    type: 'designation'
                };
            }
            //
        });
        //
        $('#js-fmla-modal').on('hide.bs.modal', function(){ $('#js-timeoff-modal').modal('show'); });
        $('#js-timeoff-modal').on('hide.bs.modal', function(){ 
            $('body').css('overflow-y', 'auto'); 
            $('#js-start-partial-time').val('');
            $('#js-end-partial-time').val('');
            $('.js-fmla-check[value="no"]').trigger('click');
            $('.js-attachments').remove();
            $('.js-no-records').show();
            fmla = {};
            localDocument = {};
            attachedDocuments = {};
            timeRowsOBJ = {};
        });
        //
        $('.js-shift-page').click(function(){
            $('#js-fmla-modal').modal('hide');
            $('#js-timeoff-modal').modal('show');
        });
        //
        $('.js-fmla-save-button').click(function(){
            var type = $(this).data('type').toLowerCase().trim();

            if(type == 'health'){
                if($('#js-fmla-health-employee-firstname').val().trim() == ''){
                    alertify.alert('ERROR!', 'First name is required.');
                    return false;
                }
                if($('#js-fmla-health-employee-lastname').val().trim() == ''){
                    alertify.alert('ERROR!', 'Last name is required.');
                    return false;
                }
                //
                fmla = {
                    type: 'health',
                    employee:{
                        firstname: $('#js-fmla-health-employee-firstname').val().trim(),
                        lastname: $('#js-fmla-health-employee-lastname').val().trim()
                    }
                };
                //
                $('#js-fmla-modal').modal('hide');
            } else if(type == 'medical'){
                if(
                    $('#js-fmla-medical-childcare').prop('checked') === false &&
                    $('#js-fmla-medical-healthcondition').prop('checked') === false &&
                    $('#js-fmla-medical-care').prop('checked') === false &&
                    $('#js-fmla-medical-care-spouse').prop('checked') === false &&
                    $('#js-fmla-medical-care-child').prop('checked') === false &&
                    $('#js-fmla-medical-care-parent').prop('checked') === false &&
                    $('#js-fmla-medical-qualify').prop('checked') === false &&
                    $('#js-fmla-medical-qualify-spouse').prop('checked') === false &&
                    $('#js-fmla-medical-qualify-child').prop('checked') === false &&
                    $('#js-fmla-medical-qualify-parent').prop('checked') === false &&
                    $('#js-fmla-medical-other').prop('checked') === false &&
                    $('#js-fmla-medical-other-spouse').prop('checked') === false &&
                    $('#js-fmla-medical-other-child').prop('checked') === false &&
                    $('#js-fmla-medical-other-parent').prop('checked') === false &&
                    $('#js-fmla-medical-other-kin').prop('checked') === false
                ){
                    alertify.alert('ERROR!', 'Please select atleast one reason.');
                    return false;
                }
                //
                fmla = {
                    type: 'medical',
                    employee:{
                        childcare: $('#js-fmla-medical-childcare').prop('checked') == true ? 1 : 0,
                        healthcondition: $('#js-fmla-medical-healthcondition').prop('checked') == true ? 1 : 0,
                        care: $('#js-fmla-medical-care').prop('checked') == true ? 1 : 0,
                        carespouse: $('#js-fmla-medical-care-spouse').prop('checked') == true ? 1 : 0,
                        carechild: $('#js-fmla-medical-care-child').prop('checked') == true ? 1 : 0,
                        careparent: $('#js-fmla-medical-care-parent').prop('checked') == true ? 1 : 0,
                        qualify: $('#js-fmla-medical-qualify').prop('checked') == true ? 1 : 0,
                        qualifyspouse: $('#js-fmla-medical-qualify-spouse').prop('checked') == true ? 1 : 0,
                        qualifychild: $('#js-fmla-medical-qualify-child').prop('checked') == true ? 1 : 0,
                        qualifyparent: $('#js-fmla-medical-qualify-parent').prop('checked') == true ? 1 : 0,
                        other: $('#js-fmla-medical-other').prop('checked') == true ? 1 : 0,
                        otherspouse: $('#js-fmla-medical-other-spouse').prop('checked') == true ? 1 : 0,
                        otherchild: $('#js-fmla-medical-other-child').prop('checked') == true ? 1 : 0,
                        otherparent: $('#js-fmla-medical-other-parent').prop('checked') == true ? 1 : 0,
                        otherkin: $('#js-fmla-medical-other-kin').prop('checked') == true ? 1 : 0
                    }
                };
                //
                $('#js-fmla-modal').modal('hide');
            }
        });
        //
        $('.js-popover').popover({
            html: true,
            trigger: 'hover',
            placement: 'right'
        });

        $('#js-start-partial-time').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15
        });

        $('#js-end-partial-time').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15,
            onShow: function(d){
                this.setOptions({
                    minTime: $('#js-start-partial-time').val() ? $('#js-start-partial-time').val() : false
                });
            }
        });

        $('#js-vacation-return-date').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1
        }).val(moment().add('1', 'day').format('MM-DD-YYYY'));
        //
        $('#js-bereavement-return-date').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1
        }).val(moment().add('1', 'day').format('MM-DD-YYYY'));
        //
        $('#js-compensatory-return-date').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1
        }).val(moment().add('1', 'day').format('MM-DD-YYYY'));

        //
        $('#js-compensatory-start-time').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15
        });

        $('#js-compensatory-end-time').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15,
            onShow: function(d){
                this.setOptions({
                    minTime: $('#js-compensatory-start-time').val() ? $('#js-compensatory-start-time').val() : false
                });
            }
        });

        $('#js-timeoff-start-date').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1,
            onSelect: function(d){
                $('#js-timeoff-end-date').datepicker('option', 'minDate', d);
                $('#js-timeoff-end-date').val(d);

                remakeRangeRows();
            }
        });

        $('#js-timeoff-end-date').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1,
            onSelect: remakeRangeRows
        })

        var timeRowsOBJ = {};

        $(document).on('keyup','.js-number', function() {
            timeRowsOBJ[$(this).data('ids')][$(this).data('type')] = $(this).val(); 
        });

        //
        function getTime(ind, indd){
            if(ind === undefined) return '';
            if(!timeRowsOBJ.hasOwnProperty(ind)){
                timeRowsOBJ[ind] = {};
                timeRowsOBJ[ind]['hour'] = policies[0]['user_shift_hours'];
                timeRowsOBJ[ind]['minute'] = policies[0]['user_shift_minutes'];
            }
            return timeRowsOBJ[ind][indd];
        }

        //
        function remakeRangeRows(){
            var startDate = $('#js-timeoff-start-date').val(),
            endDate = $('#js-timeoff-end-date').val();
            //
            $('#js-timeoff-date-box').hide();
            $('#js-timeoff-date-box tbody tr').remove();
            //
            if(startDate == '' || endDate == '') { return; }
            //
            startDate =  moment(startDate);
            endDate = moment(endDate);
            var diff = endDate.diff(startDate, 'days');
            //
            var rows = '';
            var i = 0,
            il = diff;
            for(i;i<=il;i++){
                var sd = moment(startDate).add(i, 'days');
                if($.inArray(sd.format('MM-DD-YYYY'), offdates) === -1 && $.inArray( sd.format('dddd').toLowerCase(), timeOffDays) === -1){
                    rows += '<tr data-id="'+( i )+'" data-date="'+( sd.format('MM-DD-YYYY') )+'">';
                    rows += '    <th style="vertical-align: middle">'+( sd.format('MMMM Do, YYYY') )+'</th>';
                    rows += '    <th style="vertical-align: middle">';
                    rows += '        <div>';
                    rows += '            <label class="control control--radio">';
                    rows += '                Full Day';
                    rows += '                <input type="radio" name="'+( i )+'_day_type" checked="true" value="fullday" />';
                    rows += '                <span class="control__indicator"></span>';
                    rows += '            </label>';
                    rows += '            <label class="control control--radio">';
                    rows += '                Partial Day';
                    rows += '                <input type="radio" name="'+( i )+'_day_type" value="partialday" />';
                    rows += '                <span class="control__indicator"></span>';
                    rows += '            </label>';
                    rows += '        </div>';
                    rows += '    </th>';
                    rows += '    <th>';
                    rows += '        <div class="rowd" id="row_'+( i )+'">';
                    rows +=          setTimeView('#row_'+( i )+'', '-el'+i, sd.format('MM-DD-YYYY'));
                    rows += '        </div>';
                    rows += '    </th>';
                    rows += '</tr>';
                    //
                }
            }
            //
            if(rows == '') return;
            //
            $('#js-timeoff-date-box tbody').html(rows);
            $('#js-timeoff-date-box').show();
        }

    })

</script>
<?php $this->load->view('timeoff/lms/scripts/attachments'); ?>

<style>
.employee-info .cs-info-text {
    padding-left: 30px;
}
.employee-info figure {
    width: 60px;
    height: 60px;
}
.employee-info .text h4{
    font-weight: 600;
    font-size: 20px;
    margin: 0;
}
.employee-info .text a{ color: #aaa;}
.employee-info .text p{
    color: #818181;
    font-weight: normal;
    font-size: 18px;
    margin: 0;
}
.cs-required{ font-weight: bold; color: #cc0000; }
.invoice-fields {
    float: left;
    width: 100%;
    height: 40px;
    border: 1px solid #ccc;
    border-radius: 5px;
    color: #000;
    padding: 0 5px;
    background-color: #eee;
}
.js-attachment-edit,
.js-attachment-view,
.js-attachment-delete,
.js-timeoff-attachment,
.btn-5{ border-radius: 5px !important; }
.modal{ overflow-y: auto !important; }
.ui-datepicker-unselectable .ui-state-default{ background-color: #555555; border-color: #555555; }
/**/
input[readonly]{ background-color: #fff !important; }
/*#js-timeoff-modal{ z-index: 9999 !important; }*/
</style>
