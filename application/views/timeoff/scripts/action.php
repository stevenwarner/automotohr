<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/pagination.min.css"/>
<script src="<?=base_url('assets/js/pagination.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/moment.min.js');?>"></script>
<script type="text/javascript">
    var baseURI = "<?=base_url();?>timeoff/",
    intervalCatcher = null,
    doReferesh = true;
    $(function(){
        var
        employeePolicies = [],
        fmla = {},
        emt = {
            modal: $('#e')
        },
        overwrite = {
            'teamlead' : 'Team Lead',
            'supervisor' : 'Supervisor',
            'approver' : 'Approver'
        },
        currentRequest = request = <?=json_encode($Request);?>;



        loadHistoryData(request.History);

        var inObject = function(val, searchIn){
            for(obj in searchIn){
                if(searchIn[obj]['from_date'] == val){
                    return searchIn[obj];
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
        $('#js-status').select2();
        //
        if(request['Info']['is_partial_leave'] == 0){
            $('#js-partial-box').hide(0);
        }
        //
        $(document).on('click', '.js-partial-leave', function(){
            if($(this).val() == 'yes') $('#js-partial-box').show();
            else $('#js-partial-box').hide();
        });
        //
        $('#js-timeoff-start-date-edit').datepicker({
            format: 'mm-dd-yy',
            beforeShowDay: unavailable,
            minDate: 1
        }).val(moment(request.Info.request_from_date, 'YYYY-MM-DD').format('MM/DD/YYYY'));
        //
        $('#js-timeoff-end-date-edit').datepicker({
            format: 'mm-dd-yy',
            beforeShowDay: unavailable,
            minDate: 1
        }).val(moment(request.Info.request_to_date, 'YYYY-MM-DD').format('MM/DD/YYYY'));
        //
        loader('hide');


        // Save TO from modal
        $(document).on('click', '#js-save-btn', function(){
            var megaOBJ = {};
            megaOBJ.action = 'update_employee_timeoff';
            megaOBJ.status = $('#js-status').val();
            megaOBJ.comment = CKEDITOR.instances['message'].getData().trim();
            megaOBJ.policyId = $('#js-policies').val();
            megaOBJ.policyName = $('#js-policies').find('option[value="'+(megaOBJ.policyId)+'"]').text();
            //
            megaOBJ.requestId = <?=$Params['id'];?>;
            megaOBJ.request = request.Info;
            megaOBJ.tls = request.Assigned;
            //
             // FMLA 
            megaOBJ.isFMLA = $('.js-fmla-check:checked').val();
            if(megaOBJ.isFMLA == 'yes' && Object.keys(fmla).length == 0){
                alertify.alert('WARNING!', 'Please fill the selected FMLA form.');
                return;   
            }
            if(megaOBJ.isFMLA == 'yes'){
                megaOBJ.fmla = fmla;
                megaOBJ.isFMLA = fmla.type;
            }else{
                megaOBJ.fmla = null;
                megaOBJ.isFMLA = null;
            }
            //
            megaOBJ.fromPublic = 1;
            megaOBJ.companySid = <?=$Params['companyId'];?>;
            megaOBJ.employerSid = <?=$Params['employeeId'];?>;
            megaOBJ.employeeSid = <?=$Request['Info']['employee_sid'];?>;

            //
            megaOBJ.startDate = $('#js-timeoff-start-date-edit').val();
            megaOBJ.endDate = $('#js-timeoff-end-date-edit').val();
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
            var requestedDays = getRequestedDaysEdit();
            //
            if(requestedDays.error) return;
            //
            megaOBJ.requestedDays = requestedDays;
            //
            var t = getPolicy( megaOBJ.policyId );
            var checkBalance = (parseInt(t.actual_allowed_timeoff) + parseInt(getNegativeBalance(t))) - parseInt(t.consumed_timeoff);
            //
            if(t.is_unlimited == '0' && requestedDays.totalTime > checkBalance){
                alertify.alert('WARNING!', 'Requested time off can not be greater than allowed time.');
                return;
            }
            
            megaOBJ.slug = t['format'];
            megaOBJ.timeslot = currentRequest.Info.timeoff_breakdown.timeFrame;
            megaOBJ.allowedTimeOff = getAllowedTimeInMinutes( t );
            //
            megaOBJ.allowedTimeOff = getAllowedTimeInMinutes( t );
            megaOBJ.requestId = currentRequest.Info.requestId;
            megaOBJ.request = currentRequest.Info;
            megaOBJ.tls = currentRequest.Assigned;
            // Phase 3 code 
            megaOBJ.returnDate = '';
            megaOBJ.relationship = '';
            megaOBJ.temporaryAddress = '';
            megaOBJ.compensationEndTime = '';
            megaOBJ.compensationStartTime = '';
            megaOBJ.emergencyContactNumber = '';
            console.log($('#js-types').val());
            //
            switch ($('#js-types').val().toLowerCase()) {
                case 'vacation':
                    megaOBJ.returnDate = $('#js-vacation-return-date-edit').val().trim();
                    megaOBJ.temporaryAddress = $('#js-vacation-address-edit').val().trim();
                    megaOBJ.emergencyContactNumber = $('#js-vacation-contact-number-edit').val().trim();
                break;

                case 'bereavement':
                    megaOBJ.returnDate = $('#js-bereavement-return-date-edit').val().trim();
                    megaOBJ.relationship = $('#js-bereavement-relationship-edit').val().trim();
                break;

                case 'compensatory/ in lieu time':
                    megaOBJ.returnDate = $('#js-compensatory-return-date-edit').val().trim();
                    megaOBJ.compensationStartTime = $('#js-compensatory-start-time-edit').val().trim();
                    megaOBJ.compensationEndTime = $('#js-compensatory-end-time-edit').val().trim();
                break;
            }

            // Disable all fields in modal
            $('#js-save-btn').val('Saving....');
            // Let's save the TO
            $.post(
                baseURI+'handler',
                megaOBJ,
                function(resp){
                    //
                    $('#js-save-btn').val('SAVE');
                    // When an error occured
                    if(resp.Status === false){
                        loader('hide');
                        alertify.alert('ERROR!', resp.Response.replace(/{{TIMEOFFDDATE}}/, moment(megaOBJ.requestDate, 'YYYY-MM-DD').format('MM-DD-YYYY')));
                        return;
                    }
                    // On success
                    alertify.alert('SUCCESS!', resp.Response, function(){
                        window.location.reload();
                    });
                }
            );
        });

        // CKEDITOR.replace(emt.comment);

        /* FILTER START */
        fetchEmployeeAllPolicies(
            <?=$Request['Info']['employee_sid'];?>,
            function(resp){
                // Set policies
                var rows = '';
                var typeRows = '';
                var types = [];
                policies = resp.Data;
                resp.Data.map(function(policy){
                    if($.inArray(policy.Category, types) === -1){
                        types.push(policy.Category);
                        typeRows += '<option value="'+( policy.Category )+'" '+( policy.Category == request.Info.Category ? 'selected=true' : '' )+'>'+( policy.Category )+'</option>';
                    }
                });
                //
                $('#js-types').html(typeRows);
                $('#js-types').select2();
                $('#js-types').trigger('change');

                // Multiple days off
                var ld = {};
                ld.startDate = moment(currentRequest.Info.request_from_date, 'YYYY-MM-DD').format('MM-DD-YYYY');
                ld.endDate = moment(currentRequest.Info.request_to_date, 'YYYY-MM-DD').format('MM-DD-YYYY');
                ld.days = currentRequest.Info.timeoff_days;
                //
                $('#js-timeoff-start-date-edit').val(ld.startDate.replace(/-/g,'/'));
                $('#js-timeoff-end-date-edit').val(ld.endDate.replace(/-/g,'/'));
                remakeRangeRowsEdit(ld);
        });

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
                $('.js-vacation-row-edit').show(0);
                fmla = {};
            } else{
                $('.js-vacation-row-edit').hide(0);
                $('.js-vacation-row-edit input').val('');
            }
            // For Bereavement
            if(v.toLowerCase().match(/(bereavement)/g) !== null ){
                $('.js-bereavement-row-edit').show(0);
                fmla = {};
            } else{
                $('.js-bereavement-row-edit').hide(0);
                $('.js-bereavement-row-edit input').val('');
            }
            // For Compensatory
            if(v.toLowerCase().match(/(compensatory)/g) !== null ){
                $('.js-compensatory-row-edit').show(0);
                fmla = {};
            } else{
                $('.js-compensatory-row-edit').hide(0);
                $('.js-compensatory-row-edit input').val('');
            }
            policies.map(function(policy){
                if(policy.Category == v){
                    rows += '<option value="'+( policy.sid )+'">'+( policy.title )+' ('+( policy.timeoff_breakdown !== undefined ? policy.timeoff_breakdown.text : 'Unlimited' )+')</option>';
                }
            });
            $('#js-policies').html(rows);
            $('#js-policies').select2();
            $('.js-policy-box').show();
            $('#js-policies').select2('val', [request.Info.policyId]);
        })

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
                defaultTimeslot: currentRequest.Info.timeoff_breakdown.timeFrame,
                defaultTimeslotMinutes: (currentRequest.Info.timeoff_breakdown.timeFrame * 60),
                format: format.replace(/,$/, ''),
                formated: inText,
                requestedMinutes: (days * currentRequest.Info.timeoff_breakdown.timeFrame * 60) + (hours * 60) + minutes
            };
        }
        //
        function getAllowedTimeInMinutes(policy){
            if(policy.is_unlimited == 1) return 0;
            var
            days = 0,
            hours = 0,
            minutes = 0;
            if(policy.format.match(/D/)) days = policy.timeoff_breakdown.active.days;
            if(policy.format.match(/H/)) days = policy.timeoff_breakdown.active.hours;
            if(policy.format.match(/M/)) days = policy.timeoff_breakdown.active.minutes;
            //
            return (days * policy.timeoff_breakdown.timeFrame * 60) + (hours * 60) + minutes;
        }
        
        // Fetch employee all policies
        function fetchEmployeeAllPolicies(employeeId, cb){
            $.post(baseURI+'handler', {
                action: 'get_employee_all_policies',
                fromPublic: 1,
                companySid: <?=$Params['companyId'];?>,
                employerSid: <?=$Params['employeeId'];?>,
                employeeSid: <?=$Request['Info']['employee_sid'];?>
            }, function(resp){
                if(resp.Status === false){
                    console.log('Failed to load policies.');
                    return;
                }
                //
                employeePolicies = resp.Data;
                cb(resp);
            });
        }
        //
        function setTimeView(target, data){
            var row = '';
            if(policies[0]['format'] == 'D:H:M'){
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Days </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-days" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(policies[0]['format'] == 'D:H'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Days </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-days" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(policies[0]['format'] == 'H:M'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }else if(policies[0]['format'] == 'H'){
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
            }else{
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }
            //
            $(target).html(row);
        }
        //
        function loadHistoryData(data){
            if(data.length == 0){
                loader('hide');
                return;
            }
            $('#js-history-data-area').pagination({
                dataSource: data,
                pageSize: 5,
                showPrevious: true,
                showNext: true,
                callback: function(hs, pagination) {
                    var rows = '';
                    hs.map(function(h, i){
                        var 
                        employeeData = getEmployeeRecord(h.timeoff_request_assignment_sid),
                        policyData = getPolicyRecord(h.timeoff_request_assignment_sid);
                        rows += '<div class="row cs-timeoff-history-row">';
                        rows += '   <div class="col-sm-12" '+( i%2 == 0 ? 'style="background-color: #f1f1f1; padding: 10px;"' : '' )+'>';
                        rows += '   <div class="col-sm-3 cs-row">';
                        rows += '       <h5><b>Employee</b></h5>';
                        rows += '       <span>'+( employeeData['full_name'] )+' ('+(overwrite[employeeData['role']])+')</span>';
                        rows += '   </div>';
                        rows += '   <div class="col-sm-3 cs-row">';
                        rows += '       <h5><b>Date</b></h5>';
                        rows += '       <span>'+( h.requested_date != '0000-00-00' && h.requested_date != '' && h.requested_date != null ? moment(h.requested_date, 'YYYY-MM-DD').format('MM-DD-YYYY') : '-' )+'</span>';
                        rows += '   </div>';
                        rows += '   <div class="col-sm-3 cs-row">';
                        rows += '       <h5><b>Time</b></h5>';
                        rows += '       <span>'+( h.timeoff_breakdown.text == '0 hour, 0 minute' ? '-' : h.timeoff_breakdown.text )+'</span>';
                        rows += '   </div>';
                        rows += '   <div class="col-sm-3 cs-row">';
                        rows += '       <h5><b>Status</b></h5>';
                        rows += '       <span>'+( h.status )+'</span>';
                        rows += '   </div>';
                        rows += '   <div class="col-sm-12 cs-row">';
                        if(policyData != 'N/A'){
                            rows += '       <p>';
                            rows += '           <strong>Policy Name:</strong> '+( policyData['title'] )+'';
                            rows += '       </p>';
                        }
                        if(h.start_time != null && h.start_time != ''){
                            rows += '       <p>';
                            rows += '           <strong>Partial Time:</strong> '+( h.start_time )+' - '+( h.end_time )+'';
                            rows += '       </p>';
                        }
                        if(h.reason.trim() != ''){
                            rows += '       <p>';
                            rows += '           <strong>Comment:</strong> '+( h.reason == '' ? '-' : '' )+'';
                            rows += '       </p>';
                        }
                        rows += '<hr />';
                        rows += '    </div>';
                        rows += '    </div>';
                        rows += '</div>';
                    });
                    $('#js-history-data-append').html(rows);
                }
            })
        }
        //
        function getEmployeeRecord(timeoff_request_assignment_sid){
            var 
            i = 0,
            l = request.Assigned.length;
            //
            for(i; i < l; i++){
                if(request.Assigned[i]['sid'] == timeoff_request_assignment_sid) return request.Assigned[i];
            }
            return 'N/A';
        }
        //
        function getPolicyRecord(policyId){
            var 
            i = 0,
            l = employeePolicies.length;
            //
            for(i; i < l; i++){
                if(employeePolicies[i]['sid'] == policyId) return employeePolicies[i];
            }
            return 'N/A';
        }
        //
        function getPolicy( sid ){
            var i = 0,
            l = employeePolicies.length;
            //
            for(i; i < l; i++) if(employeePolicies[i]['sid'] == sid) return employeePolicies[i];
            return null;
        }

        // Loader
        function loader(show_it){
            show_it = show_it === undefined || show_it == true || show_it == 'show' ? 'show' : 'hide';
            if(show_it === 'show') $('.js-ad-loader').show();
            else $('.js-ad-loader').hide();
        }

        String.prototype.ucwords = function() {
            return this.toLowerCase().replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
                function(s){
                    return s.toUpperCase();
                }
            );
        };

        // FMLA
        //
        $('.js-fmla-type-check').click(function(e){
            //
            $('.js-form-area').hide();
            //
            fmla = {};
            if($(this).val() !== 'designation'){
                //
                $('#js-timeoff-modal').modal('toggle');
                $('body').css('overflow', 'hidden');
                $('.js-fmla-employee-firstname').val("<?=$Request['Info']['first_name'];?>");
                $('.js-fmla-employee-lastname').val("<?=$Request['Info']['last_name'];?>");
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
        $('#js-fmla-modal').on('hide.bs.modal', function(){ $('body').css('overflow-y', 'auto'); });
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

        <?php $this->load->view('timeoff/scripts/attachment'); ?>

        setAttachments();

        $(document).on('hidden.bs.modal', '#js-timeoff-attachment-modal, #js-attachment-view-modal, #js-attachment-view-modal, #js-timeoff-attachment-es-modal, #js-timeoff-attachment-upload-modal', function(){
            console.log('here');
            $('body').css('overflow-y', 'auto');
        });

        timePickers();
        function timePickers(){
            $('#js-start-partial-time').datetimepicker({
                datepicker: false,
                format: 'g:i A',
                formatTime: 'g:i A',
                step: 1,
                onClose: function(d){
                    // $('#js-end-partial-time').setOptions({
                    //     minTime: d
                    // });
                }
            });

            $('#js-end-partial-time').datetimepicker({
                datepicker: false,
                format: 'g:i A',
                formatTime: 'g:i A',
                step: 1,
                onClose: function(d){
                    // this.setOptions({
                    //     minTime: $('#js-start-partial-time').val() ? $('#js-start-partial-time').val() : false
                    // });
                }
            });
        }
        $('#js-timeoff-start-date-edit').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1,
            onSelect: function(d){
                $('#js-timeoff-end-date-edit').datepicker('option', 'minDate', d);
                $('#js-timeoff-end-date-edit').val(d);

                remakeRangeRowsEdit();
            }
        });

        $('#js-timeoff-end-date-edit').datepicker({
            format: 'mm-dd-yyyy',
            beforeShowDay: unavailable,
            minDate: 1,
            onShow: function(){
                $('#js-timeoff-end-date-edit').datepicker('option', 'minDate', d);
                $('#js-timeoff-end-date-edit').val(d);
            },
            onSelect: remakeRangeRowsEdit
        });

         //
    function remakeRangeRowsEdit(data){
        var startDate = $('#js-timeoff-start-date-edit').val(),
        endDate = $('#js-timeoff-end-date-edit').val();
        //
        var d = {};
        //
        if(typeof(data) === 'object'){
            startDate = data.startDate;
            endDate = data.endDate;
            data.days.map(function(v,i){ d[v.date] = v; });
        }
        //
        $('#js-timeoff-date-box-edit').hide();
        $('#js-timeoff-date-box-edit tbody tr').remove();
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
            var ld = d[sd.format('MM-DD-YYYY')];
            rows += '<tr data-id="'+( i )+'" data-date="'+( sd.format('MM-DD-YYYY') )+'">';
            rows += '    <th style="vertical-align: middle">'+( sd.format('MMMM Do, YYYY') )+'</th>';
            rows += '    <th style="vertical-align: middle">';
            rows += '        <div>';
            rows += '            <label class="control control--radio">';
            rows += '                Full Day';
            rows += '                <input type="radio" name="'+( i )+'_day_type_edit" value="fullday" '+( ld !== undefined && ld.partial == 'fullday' ? 'checked="true"' : ld == undefined ? 'checked="true"' : '' )+' />';
            rows += '                <span class="control__indicator"></span>';
            rows += '            </label>';
            rows += '            <label class="control control--radio">';
            rows += '                Partial Day';
            rows += '                <input type="radio" name="'+( i )+'_day_type_edit" value="partialday" '+( ld !== undefined && ld.partial != 'fullday' ? 'checked="true"' : '' )+' />';
            rows += '                <span class="control__indicator"></span>';
            rows += '            </label>';
            rows += '        </div>';
            rows += '    </th>';
            rows += '    <th>';
            rows += '        <div class="rowd" id="row_'+( i )+'_edit">';
            rows +=          setTimeViewEdit('#row_'+( i )+'', '-el-edit'+i, ld);
            rows += '        </div>';
            rows += '    </th>';
            rows += '</tr>';
            //
        }
        //
        $('#js-timeoff-date-box-edit tbody').html(rows);
        $('#js-timeoff-date-box-edit').show();
    }

    //
    function setTimeViewEdit(target, prefix, data){
        var row = '';
        if(policies[0]['format'] == 'D:H:M'){
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Days </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-days'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.days : '' )+'" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.hours : '' )+'" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.minutes : '' )+'" />';
            row += '    </div>';
            row += '</div>';
        }
        else if(policies[0]['format'] == 'D:H'){
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Days </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-days'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.days : '' )+'" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.hours : '' )+'" />';
            row += '    </div>';
            row += '</div>';
        }
        else if(policies[0]['format'] == 'H:M'){
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.hours : '' )+'" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.minutes : '' )+'" />';
            row += '    </div>';
            row += '</div>';
        }else if(policies[0]['format'] == 'H'){
            row += '<div class="col-sm-12">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.hours : '' )+'" />';
            row += '    </div>';
            row += '</div>';
        }else{
            row += '<div class="col-sm-12">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes'+( prefix === undefined ? '' : prefix)+'" value="'+( data !== undefined ? data.breakdown.active.minutes : '' )+'" />';
            row += '    </div>';
            row += '</div>';
        }
        //
        if(prefix !== undefined) return row;
        $(target).html(row);
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

    String.prototype.ucwords = function() {
        return this.toLowerCase().replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
            function(s){
                return s.toUpperCase();
            }
        );
    };

     //
    function getRequestedDaysEdit(){
        //
        var 
        totalTime = 0,
        err = false,
        arr = [];
        //
        $('#js-timeoff-date-box-edit tbody tr').map(function(i, v){
            if(err) return;
            var time = getTimeInMinutes('el-edit'+( $(this).data('id') ));
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
                partial: $(this).find('input[name="'+( i )+'_day_type_edit"]:checked').val(),
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
    })
 //

</script>

<!-- Loader -->
<div class="text-center cs-loader js-ad-loader">
    <div class="cs-loader-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="cs-loader-text">Please wait, while we process your request.</div>
    </div>
</div><!-- Loader -->


<style>
    .ui-datepicker-unselectable .ui-state-default{ background-color: #555555; border-color: #555555; }
</style>

