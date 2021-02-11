<script src="<?=base_url("assets/js/moment.min.js")?>"></script>
<link rel="stylesheet" href="<?=base_url('assets');?>/css/timeoffstyle.css">
<!-- Modal -->
<div class="modal fade" id="js-timeoff-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content model-content-custom">
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
                                        <img src="<?=AWS_S3_BUCKET_URL;?><?=$employerData['profile_picture'];?>" class="img-circle emp-image" />
                                    </figure>
                                    <div class="text cs-info-text">
                                        <h4><?=$employerData['first_name'].' '.$employerData['last_name'];?></h4>
                                        <p><a href="<?=base_url('employee_profile');?>/<?=$employerData['sid'];?>" target="_blank">Id: <?=$employerData['employee_number'] ? $employerData['employee_number'] : $employerData['sid'];?></a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Policies <span class="cs-required">*</span> </label>
                                    <select id="js-policies"></select>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <!-- Section Two -->
                        <div class="row">
                            <div class="col-sm-6 col-sm-12">
                                <div class="form-group">
                                    <label>Date <span class="cs-required">*</span></label>
                                    <input readonly="true" type="text" id="js-timeoff-date" class="form-control js-request-date" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="row" id="js-time"></div>
                            </div>
                        </div>
                        <hr />
                        <!-- Section Three -->
                        <div class="row">
                            <div class="col-sm-12 col-sm-12">
                                <label for="">Is this for a partial day? <span class="cs-required">*</span></label>
                                <br />
                                <label class="control control--radio font-normal">
                                    Yes
                                    <input class="js-partial-check" name="partial" value="yes" type="radio" />
                                    <div class="control__indicator"></div>
                                </label> &nbsp;
                                <label class="control control--radio font-normal">
                                    No
                                    <input class="js-partial-check" name="partial" value="no" type="radio" checked="true" />
                                    <div class="control__indicator"></div>
                                </label>

                                <div class="js-note-box" style="display: none;">
                                    <br />
                                    <label>Note</label></label>
                                    <input type="text" class="form-control" id="js-note" />
                                </div>
                            </div>
                        </div>
                        <hr />
                        <!-- Section Four -->
                        <div class="row">
                            <div class="col-sm-12 col-sm-12">
                                <div class="form-group">
                                    <label>Reason</label>
                                    <textarea id="js-reason"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-rounded" id="js-save-btn">SAVE</button>
                <button type="button" class="btn btn-info btn-rounded" data-dismiss="modal" id="js-cancel-btn">Cancel</button>
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
        defaultHours = null,
        defaultMinutes = null;
        defaultTimeslot = null;
        xhr = null;
        //
        $('.select2').select2();
        //
        $('#js-to-box').click(function(e){
            e.stopPropagation();
            window.location = "<?=base_url('timeoff/lms/')?>";
        });
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
            minDate: 1
        }).val(moment().add('1', 'day').format('MM-DD-YYYY'));

        fetchEmployeePolicies();
        fetchMatricsList();

        //
        function setTimeView(target){
            var row = '';
            if(policies[0]['format'] == 'D:H:M'){
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Days <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-days" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Hours <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(policies[0]['format'] == 'D:H'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Days <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-days" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(policies[0]['format'] == 'H:M'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }else if(policies[0]['format'] == 'H'){
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Hours <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
            }else{
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }
            //
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
                setTimeView('#js-time');
                var rows = '';
                rows += '<option value="0">[Select a policy]</option>';
                policies.map(function(policy){
                    rows += '<option value="'+( policy.sid )+'">'+( policy.title )+' ('+( policy.timeoff_breakdown !== undefined ? policy.timeoff_breakdown.text : 'Unlimited' )+')</option>';
                })
                $('#js-policies').html(rows);
                $('#js-policies').select2();
            });
        }

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
            $('.js-number').val(0);
            $('#js-policies').select2('val', 0);
            $('#js-note').val('');
            CKEDITOR.instances['js-reason'].setData('');
            $('.js-partial-check[value="no"]').trigger('click');
            $('#js-timeoff-date').val(moment().add('1', 'day').format('MM-DD-YYYY'));

        });

        // Save TO from modal
        $(document).on('click', '#js-save-btn', function(){
            var megaOBJ = {};
            megaOBJ.note = $('#js-note').val().trim();
            megaOBJ.action = 'create_employee_timeoff';
            megaOBJ.reason = CKEDITOR.instances['js-reason'].getData().trim();
            megaOBJ.policyId = $('#js-policies').val();
            megaOBJ.policyName = $('#js-policies').find('option[value="'+(megaOBJ.policyId)+'"]').text();
            //
            if(megaOBJ.policyId == 0){
                alertify.alert('WARNING!', 'Please select a Policy to continue.');
                return;
            }
            megaOBJ.isPartial = $('.js-partial-check:checked').val() == 'no' ? 0 : 1;
            megaOBJ.companySid = <?=$companyData['sid'];?>;
            megaOBJ.employeeSid = <?=$employerData['sid'];?>;
            megaOBJ.employeeFullName = "<?=$employerData['first_name'].' '.$employerData['last_name'];?>";
            megaOBJ.employeeEmail = "<?=$employerData['email'];?>";
            megaOBJ.requestDate = moment($('#js-timeoff-date').val(), 'MM-DD-YYYY').format('YYYY-MM-DD');
            // Set hours minutes array
            megaOBJ.requestedTimeDetails = getTimeInMinutes();
            //
            if(megaOBJ.requestedTimeDetails.requestedMinutes <= 0){
                alertify.alert('WARNING!', 'Requested time off can not be less or equal to zero.');
                return;
            }
            //
            megaOBJ.allowedTimeOff = getAllowedTimeInMinutes( getPolicy( megaOBJ.policyId ) );
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
                    // When an error occured
                    if(resp.Status === false){
                        alertify.alert('ERROR!', resp.Response.replace(/{{TIMEOFFDDATE}}/, moment(megaOBJ.requestDate, 'YYYY-MM-DD').format('MM-DD-YYYY')));
                        return;
                    }
                    // On success
                    alertify.alert('SUCCESS!', resp.Response);
                    $('#js-cancel-btn').trigger('click');
                }
            );
            console.log( megaOBJ );
        });

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
        function getTimeInMinutes(){
            var
            days = 0,
            hours = 0,
            minutes = 0,
            format = '',
            inText = '';
            //
             if($('#js-request-days').length !== 0) { format +='D,'; days = isNaN(parseInt($('#js-request-days').val().trim())) ? 0 : parseInt($('#js-request-days').val().trim()); }
            if($('#js-request-hours').length !== 0) { format += 'H,'; hours = isNaN(parseInt($('#js-request-hours').val().trim())) ? 0 : parseInt($('#js-request-hours').val().trim()); }
            if($('#js-request-minutes').length !== 0) { format += 'M,'; minutes = isNaN(parseInt($('#js-request-minutes').val().trim())) ? 0 : parseInt($('#js-request-minutes').val().trim()); }
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
                format: format.replace(/,$/, ''),
                formated: inText,
                requestedMinutes: (days * defaultTimeslot * 60) + (hours * 60) + minutes
            };
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
    })

</script>

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
</style>
