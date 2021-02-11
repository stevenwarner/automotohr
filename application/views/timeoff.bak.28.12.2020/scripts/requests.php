<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<style>
    .google-visualization-orgchart-table{
        /* max-width: 50%; */
        margin: auto;
    }
    .google-visualization-orgchart-connrow-medium{
        font-size: initial;
    }
    .google-visualization-orgchart-node{ border: none;}
</style>
<script type="text/javascript">
    <?php $this->load->view('timeoff/scripts/common'); ?>
    var baseURI = "<?=base_url();?>timeoff/",
    intervalCatcher = null;
    $(function(){
        var
        currentRequest = {},
        cr = {
            tls:[]
        },
        employees = [],
        employeePolicies = [],
        departments = [],
        is_filter = false,
        default_slot = 0,
        policies = [],
        plans = [],
        xhr = null,
        intVal = null,
        overwrite = {
            'teamlead' : 'Team Lead',
            'supervisor' : 'Supervisor',
            'approver' : 'Approver'
        },
        pOBJ = {
            'fetchRequests' : {
                page: 1,
                totalPages: 0,
                limit: 0,
                records: 0,
                totalRecords: 0,
                cb: fetchRequests
            }
        },
        filterOBJ = {
            page : pOBJ['fetchRequests']['page'],
            action : 'get_assigned_requests',
            companySid : <?=$company_sid;?>,
            employeeSid : <?=$employerData['sid'];?>,
            isSuper : <?=$employerData['access_level_plus'];?>,
            policySid : 'all',
            requesterSid : 'all',
            startDate : '',
            endDate : '',
            status : 'all'
        },
        record = [],
        intervalCatcher = null,
        holidayDates = <?=json_encode($holidayDates);?>,
        timeOffDays = <?=json_encode($timeOffDays);?>,
        emt = {
            modal: $('#js-edit-modal'),
            header: $('#js-edit-modal-header'),
            tabs: $('#js-edit-modal-tabs'),
            requestDate: $('#js-edit-modal-date'),
            requestTime: $('#js-edit-modal-time'),
            policies: $('#js-edit-modal-policies'),
            status: $('#js-edit-modal-status'),
            isPartialLeave: $('#js-edit-modal-partial-leave'),
            partialLeaveCheck: '.js-edit-modal-partial-check',
            noteBox: $('#js-edit-modal-note-box'),
            note: $('#js-edit-modal-note-input'),
            reason: $('#js-edit-modal-reason'),
            comment: 'js-edit-modal-comment',
            progress: $('#js-edit-modal-progress-bar'),
            saveBtn: $('#js-edit-modal-save-btn'),
            cancelBtn: $('#js-edit-modal-cancel-btn')
        };

        var offdates = [];

        //
        if(holidayDates.length != 0){
            for(obj in holidayDates){
                if(holidayDates[obj]['work_on_holiday'] == 0) offdates.push(holidayDates[obj]['from_date']);
            }
        }

        // $('#js-edit-modal-status').on("select2:select", function(e) { 
        //     var selected_element = $(e.currentTarget);
        //     var select_val = selected_element.val();
            
        //     if (select_val == 'archive') {
        //         $("#js-archive-note-section").show();
        //     } else {
        //         $("#js-archive-note-section").hide();
        //     }
        // });

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

        CKEDITOR.replace(emt.comment);

        /* FILTER START */
        fetchEmployees();
        fetchAllPolicies();
        fetchRequests();

        //
        $(document).on('click', '.js-edit-modal-partial-check', function(){
            if($(this).val() == 'yes') emt.noteBox.show();
            else emt.noteBox.hide();
        });

        // Select2
        $('#js-filter-status').select2();
        $('#js-status-add').select2();
        $('#js-status-edit').select2();
        $('#js-accural-type-add').select2();
        $('#js-accrue-start-add').select2();

        // Datepickers
        $('#js-filter-from-date').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function (v) { $('#js-filter-to-date').datepicker('option', 'minDate', v); }
        })

        $('#js-filter-to-date').datepicker({
            dateFormat: 'mm-dd-yy'
        }).datepicker('option', 'minDate', $('#js-filter-from-date').val());

        // Filter buttons
        $(document).on('click', '.js-apply-filter-btn', applyFilter);
        $(document).on('click', '.js-reset-filter-btn', resetFilter);
        /* FILTER END */
        //
        $(document).on('click', '.js-edit-request', loadEditPage); 
        //
        $(document).on('keyup', '.js-number', function(){
            $(this).val(
                $(this).val().replace(/[^0-9]/, '')
            );
        });
        // Add process
        $(document).on('change', emt.policies, function(e){
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
        // Save TO from modal
        $(document).on('click', '#js-edit-modal-save-btn', function(){
            var megaOBJ = {};
            megaOBJ.action = 'update_employee_timeoff';
            // megaOBJ.approver = 1;
            megaOBJ.status = $('#js-edit-modal-status').val();
            megaOBJ.reason = CKEDITOR.instances['js-edit-modal-reason'].getData().trim();
            megaOBJ.comment = CKEDITOR.instances['js-edit-modal-comment'].getData().trim();
            megaOBJ.policyId = $('#js-edit-modal-policies').val();
            megaOBJ.policyName = $('#js-edit-modal-policies').find('option[value="'+(megaOBJ.policyId)+'"]').text();
            //
            if(megaOBJ.status == 'pending'){
                alertify.alert('WARNING!', 'Time off request status can not be "<b>Pending</b>".');
                return;
            }
            megaOBJ.companySid = <?=$companyData['sid'];?>;
            megaOBJ.employeeSid = <?=$employerData['sid'];?>;
            megaOBJ.requesterSid = currentRequest.Info.employee_sid;
            megaOBJ.employeeFullName = "<?=$employerData['first_name'].' '.$employerData['last_name'];?>";
            megaOBJ.employeeEmail = "<?=$employerData['email'];?>";
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
           
            if (megaOBJ.status == 'approved') {
                if(t.is_unlimited == '0' && requestedDays.totalTime > checkBalance){
                    alertify.alert('WARNING!', 'Requested time off can not be greater than allowed times.');
                    return;
                }
            }
           
            megaOBJ.slug = t['format'];
            megaOBJ.timeslot = currentRequest.Info.timeoff_breakdown.timeFrame;
            megaOBJ.allowedTimeOff = getAllowedTimeInMinutes( t );
            //
            if($('.js-fmla-check:checked').val() === 'yes'){
                megaOBJ.isFMLA = $('.js-fmla-type-check:checked').val();
            }else{
                megaOBJ.isFMLA = null;
            }
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
            //
            switch ($('#js-edit-modal-types').val().toLowerCase()) {
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
            // Let's see if assigner is approver
            if(currentRequest.Assigned != null && currentRequest.Assigned.length != 0){
                var 
                    ii = 0,
                    iil = currentRequest.Assigned.length;
                //
                for(ii; ii < iil; ii++){

                    if(currentRequest.Assigned[ii].employee_sid == megaOBJ.employeeSid && currentRequest.Assigned[ii].role == 'approver') megaOBJ.approver = 1;
                }
            }
            //
            // Disable all fields in modal
            emt.modal.find('input, textarea').prop('disabled', true);
            $('#js-edit-modal-save-btn').val('Saving....');
            // Let's save the TO
            $.post(
                baseURI+'handler',
                megaOBJ,
                function(resp){
                    //
                    emt.modal.find('input, textarea').prop('disabled', false);
                    $('#js-edit-modal-save-btn').val('SAVE');
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
        //
        $(document).on('click', '.js-request-title-li', function(e){
            //
            e.preventDefault();
            //
            $('.js-request-title-li').parent().removeClass('active');
            $(this).parent().addClass('active');
            //
            $('#js-data-load-area tr').hide();
            $('#js-data-load-area tr[data-type="'+( $(this).data('key') )+'"]').show();
        });

        // Page Shifter
        $(document).on('click', '.js-tab-btn', function(e){
            //
            $('.js-tab-btn').removeClass('active');
            $(this).addClass('active');

            $('.js-em-page').fadeOut();
            $(`#${$(this).find('a').data('target')}`).fadeIn();
        });

        /* VIEW PAGE START */
        //
        function resetFilter(e){
            e.preventDefault();
            $('#js-filter-policies').select2('val', 'all');
            $('#js-filter-employee').select2('val', 'all');
            $('#js-filter-from-date').val('');
            $('#js-filter-to-date').val('');
            $('#js-filter-status').select2('val', 'all');

            pOBJ['fetchRequests']['records'] = [];
            pOBJ['fetchRequests']['totalPages'] =
            pOBJ['fetchRequests']['totalRecords'] =
            pOBJ['fetchRequests']['limit'] = 0;
            pOBJ['fetchRequests']['page'] = 1;
            //
            filterOBJ.page = pOBJ['fetchRequests']['page'];
            filterOBJ.policySid = 'all';
            filterOBJ.requesterSid = 'all';
            filterOBJ.startDate = '';
            filterOBJ.endDate = '';
            filterOBJ.status = 'all';

            fetchRequests();
        }
        //
        function applyFilter(e){
            loader();
            e.preventDefault();
            pOBJ['fetchRequests']['records'] = [];
            pOBJ['fetchRequests']['totalPages'] =
            pOBJ['fetchRequests']['totalRecords'] =
            pOBJ['fetchRequests']['limit'] = 0;
            pOBJ['fetchRequests']['page'] = 1;

            filterOBJ.page = pOBJ['fetchRequests']['page'];
            filterOBJ.policySid =  $('#js-filter-policies').val();
            filterOBJ.requesterSid = $('#js-filter-employee').val();
            filterOBJ.startDate = $('#js-filter-from-date').val() != '' ? moment($('#js-filter-from-date').val(), 'MM-DD-YYYY').format('YYYY-MM-DD') : 'all';
            filterOBJ.endDate = $('#js-filter-to-date').val() != '' ? moment($('#js-filter-to-date').val(), 'MM-DD-YYYY').format('YYYY-MM-DD') : 'all';
            filterOBJ.status = $('#js-filter-status').val();

            fetchRequests();
        }
        // Fetch plans
        function fetchRequests(){

            console.log('********************');
            console.log(employees.length);
            console.log(intVal);
            console.log('********************');
            clearInterval(intVal);
            //
            if(employees.length == 0){
                intVal = setInterval(function(){
                    fetchRequests();
                }, 1000);
                return;
            }
            //
            //
            var emp = [];
            if(employees.length != 0){
                employees.map(function(employee){
                    emp.push(employee.employee_id);
                });
            }
            filterOBJ.employeeList = emp;
            //
            if(xhr != null) return;
            //
            loader('show', 'Please wait, while we are fetching Time Off requests.');
            //
            xhr = $.post(baseURI+'handler', filterOBJ, function(resp) {
                xhr = null;
                //
                if(resp.Status === false){
                    loader('hide');
                    $('#js-data-load-area').html('<tr class="js-error-row"><td colspan="'+( $('.js-table-head').find('th').length )+'"><p class="alert alert-info text-center">'+( resp.Response )+'</p></td></tr>')
                    return;
                }
                //
                setTable(resp.Data);
            });
        }
        //
        function setTable(requests){
            //
            $('#js-data-load-area').html('');
            $('.js-request-title-li').remove();
            var 
            rows = '',
            trows = '';
            if(requests.Requests.length == 0)
                rows += '<tr><td colspan="'+( $('thead tr th').length )+'"><p class="alert alert-info text-center">No pending time off requests found.</p></td></tr>';
            else{
                var i = 0,
                l = requests.Requests.length;
                //
                for(i; i < l; i++){
                    var format_slug = requests['Requests'][i]['slug'];
                    var defaultTimeFrame = requests['Requests'][i]['defaultTimeFrame'];

                    //
                    var leave_duration = '';
                    var leave_schedule = '';
                    var leave_schedule_detail = '';
                    var format_slug = requests['Requests'][i]['slug'];
                    var defaultTimeFrame = requests['Requests'][i]['defaultTimeFrame'];

                    if (requests['Requests'][i]['requested_date'] == requests['Requests'][i]['request_to_date']) {
                        leave_duration = moment(requests['Requests'][i]['requested_date'], 'YYYY-MM-DD').format(timeoffDateFormat);
                    } else {
                        leave_duration = moment(requests['Requests'][i]['requested_date'], 'YYYY-MM-DD').format(timeoffDateFormat)+' - '+moment(requests['Requests'][i]['request_to_date'], 'YYYY-MM-DD').format(timeoffDateFormat);
                    }

                    leave_schedule_data = requests['Requests'][i]['timeoff_days'];

                    rows += '<tr data-type="'+( requests['Requests'][i]['Category'].toLowerCase().replace(/\s+/g, '_') )+'" data-id="'+( requests['Requests'][i]['requestId'] )+'" style="display: none;">';
                    rows += '   <td scope="row">';
                    rows += '       <div class="employee-info">';
                    rows += '           <figure>';
                    rows += '                <img src="'+(getImageURL(requests['Requests'][i]['img']))+'" class="emp-image" />';
                    rows += '           </figure>';
                    rows += '           <div class="text">';
                    rows += '               <h4>'+( requests['Requests'][i]['full_name'] )+'</h4>';
                    rows += '                <p>'+( remakeEmployeeName(requests['Requests'][i], false) )+'</p>';
                    rows += '               <p><a href="<?=base_url('employee_profile');?>/'+( requests['Requests'][i]['employee_sid'] )+'" target="_blank">Id: '+( getEmployeeId(requests['Requests'][i]['employee_sid'], requests['Requests'][i]['employee_number']) )+'</a></p>';
                    rows += '           </div>';
                    rows += '       </div>';
                    rows += '   </td>';
                    rows += '   <td>';
                    rows += '       <div class="upcoming-time-info">';
                    rows += '           <div class="icon-image">';
                    rows += '               <a href="javascript:void(0)" title="Request Detail" data-trigger="hover" data-toggle="popover" data-placement="left" data-content="'+leave_schedule_detail+'" class="action-activate custom-tooltip"><img src="<?=base_url('assets');?>/images/upcoming-time-off-icon.png" class="emp-image" alt="emp-1"></a>';
                    rows += '            </div>';
                    rows += '            <div class="text">';
                    rows += '               <h4>'+leave_duration+'</h4>';
                    rows += '               <p>'+( requests['Requests'][i]['requested_time'] == 0 ? 'Unlimited' : requests['Requests'][i]['timeoff_breakdown']['text'] )+' of <span>'+( requests['Requests'][i]['policy_title'] )+'</span></p>';
                    rows += '             </div>';
                    rows += '       </div>';
                    rows += '   </td>';
                    rows += '   <td>';
                    rows += '        <div class="progress-bar-custom">';
                    rows += '            <div class="progress-bar-tooltip">';
                    rows += '                <div class="progress">';
                    rows += '                    <div class="progress-bar progress-bar-success progress-bar-success-blue" role="progressbar" aria-valuenow="'+( requests.Requests[i]['Progress']['CompletedPercentage'] )+'" aria-valuemin="0" aria-valuemax="'+( requests.Requests[i]['Progress']['Total'] )+'" style="width: '+( requests.Requests[i]['Progress']['CompletedPercentage'] )+'%">';
                    rows += '                        <div class="sr-only"></div>';
                    rows += '                    </div>';
                    rows += '                </div>';
                    rows += '            </div>'; 
                    rows += '            <div class="progress-percent progress-percent-custom">'+( requests.Requests[i]['Progress']['CompletedPercentage'] )+'% Completed</div>';
                    rows += '        </div>';
                    rows += '    </td>';
                    rows += '   <td align="center">';
                    rows += '      <div class="notes-employee">';
                    rows +=         getReasonHTML( requests['Requests'][i]['reason'] != '' && requests['Requests'][i]['reason'] != null ? requests['Requests'][i]['reason'].replace(/("|')/g, '') : '' );
                    rows += '      </div>';
                    rows += '    </td>';
                    rows += '   <td align="center">';
                    rows += '      <div class="notes-employee">';
                    rows +=         getLevelHTML(requests['Requests'][i]['level_at']);
                    rows += '      </div>';
                    rows += '    </td>';
                    rows += '   <td>';
                    rows += '      <div class="denied-button">';
                    rows +=         getStatusHTML(requests['Requests'][i]['status']);
                    rows += '      </div>';
                    rows += '    </td>';
                    rows += '    <td align="center">';
                    rows += '       <div class="notes-employee">';
                    rows += '           <span class="js-edit-request" data-toggle="tooltip" title="Edit Request" ><i class="fa fa-edit icon_blue"></i></span>';
                    rows +=             getPrintDownloadButtons( requests['Requests'][i]['requestId'] );
                    rows += '       </div>';
                    rows += '    </td>';
                    rows += '</tr>';
                }

                // set title
                i = 0;
                l = requests.Titles.length;
                
                for(i; i < l; i++){
                    trows += '<li class="'+( i == 0 ? 'active active-tab' : '' )+'"><a href="javascript:void(0)" data-key="'+( requests.Titles[i].toLowerCase().replace(/\s+/g, '_') )+'" class="js-request-title-li">'+( requests.Titles[i] )+'</a></li>';
                }
            }
            //
            $('#js-data-load-area').html(rows);
            $('.js-request-titles').html(trows);
            if(requests.Titles.length != 0)
            $('#js-data-load-area tr[data-type="'+( requests.Titles[0].toLowerCase().replace(/\s+/g, '_') )+'"]').show();
            //
            $('[data-toggle="popover"]').popover({ html: true });
            loader('hide');
        }
        //
        function loadEditPage(e){
            e.preventDefault();
            resetEditModal();
            //
            loader('show', 'Please wait, while we are fetching Time Off request details.');
            var megaOBJ = {};
            megaOBJ.action = 'get_single_request';
            megaOBJ.requestSid = $(this).closest('tr').data('id');
            megaOBJ.companySid = <?=$company_sid;?>;
            megaOBJ.employeeSid = <?=$employerData['sid'];?>;
            //
            $.post(baseURI+'handler', megaOBJ, function(resp) {
                //
                if(resp.Status === false){
                    alertify.alert('ERROR!', resp.Response);
                    return;
                }
                //
                startEditProcess(resp);
            });
        }
        //
        function startEditProcess(resp){
            currentRequest = resp.Data;

            if (resp.Data.Info.archive == 0) {
                $('#js-edit-modal-active-btn').hide();
                $('#js-edit-modal-archive-btn').show();
            } else if (resp.Data.Info.archive == 1) {
                $('#js-edit-modal-archive-btn').hide();
                $('#js-edit-modal-active-btn').show();
            }
            // Set header
            emt.header.text('Time Off, '+( resp.Data.Info.full_name )+'');
            //
            emt.tabs.html(`<li class="js-tab-btn active btn-active tab-btn">
                <a data-target="js-detail-page">Details</a>
            </li>`);
            if(resp.Data.History.length != 0){
                emt.tabs.append(`<li class="js-tab-btn tab-btn">
                    <a data-target="js-history-page">History (${resp.Data.History.length})</a>
                </li>`);
                setHistory();
            }
            if(resp.Data.Conversation.length != 0){
                emt.tabs.append(`<li class="js-tab-btn tab-btn">
                    <a data-target="js-conversation-page">Conversation (${resp.Data.Conversation.length})</a>
                </li>`);
            }
            //
            emt.tabs.append(`<li class="js-tab-btn tab-btn">
            <a data-target="js-attachment-page">Documents (<span class="js-attachments-count">${resp.Data.Attachments.length}</span>)</a>
            </li>`);
            setAttachments();
            //
            if(currentRequest.Assigned.length != 0){
                emt.tabs.append(`
                    <li class="js-tab-btn tab-btn">
                        <a data-target="js-flow-page">Request Flow</a>
                    </li>`
                );
            }
            setRequestFlow();
           
            // Set Partial box
            if(resp.Data.Info.fmla != null && resp.Data.Info.fmla != '0'){
                $('.js-fmla-box').show();
                $('.js-fmla-type-check[value="'+(resp.Data.Info.fmla)+'"]').prop('checked', true);
                $('.js-fmla-check[value="yes"]').prop('checked', true);
            }else{
                $('.js-fmla-check[value="no"]').trigger('click');
            }

            // Phase 3
            //
            switch (currentRequest.Info.Category.toLowerCase()) {
                case 'vacation':
                    $('#js-vacation-return-date-edit').val(currentRequest.Info.return_date == null || currentRequest.Info.return_date == '0000-00-00' ? '' : moment(currentRequest.Info.return_date, 'YYYY-MM-DD').format('MM/DD/YYYY'));
                    $('#js-vacation-address-edit').val(currentRequest.Info.temporary_address);
                    $('#js-vacation-contact-number-edit').val(currentRequest.Info.emergency_contact_number);
                    $('.js-vacation-row-edit').show();
                break;

                case 'bereavement':
                    $('#js-bereavement-return-date-edit').val(currentRequest.Info.return_date == null || currentRequest.Info.return_date == '0000-00-00' ? '' : moment(currentRequest.Info.return_date, 'YYYY-MM-DD').format('MM/DD/YYYY'));
                    $('#js-bereavement-relationship-edit').val(currentRequest.Info.relationship);
                    $('.js-bereavement-row-edit').show();
                break;

                case 'compensatory/ in lieu time':
                    $('#js-compensatory-return-date-edit').val(currentRequest.Info.return_date == null || currentRequest.Info.return_date == '0000-00-00' ? '' : moment(currentRequest.Info.return_date, 'YYYY-MM-DD').format('MM/DD/YYYY'));
                    $('#js-compensatory-start-time-edit').val(currentRequest.Info.compensation_start_time);
                    $('#js-compensatory-end-time-edit').val(currentRequest.Info.compensation_end_time);
                    $('.js-compensatory-row-edit').show();
                break;
            }
            // Phase 3 ends
           
            CKEDITOR.instances['js-edit-modal-reason'].setData(resp.Data.Info.reason);
            //
            $('#js-start-partial-time').val(resp.Data.Info.start_time);
            $('#js-end-partial-time').val(resp.Data.Info.end_time);
            // Set progress bar
            emt.progress.find('.progress-bar').css('width', `${resp.Data.Info.Progress.CompletedPercentage}%`);
            emt.progress.find('.progress-bar').attr('aria-valuemin', '0');
            emt.progress.find('.progress-bar').attr('aria-valuemax', '100');
            emt.progress.find('.progress-bar').attr('aria-valuenow', `${resp.Data.Info.Progress.CompletedPercentage}%`);
            emt.progress.find('.progress-percent span').text(resp.Data.Info.Progress.CompletedPercentage);
            //
            emt.status.find('option[value="pending"]').remove();
            // emt.status.find('option[value="cancelled"]').remove();
            if(resp.Data.Info.level_status == 'pending') emt.status.prepend('<option value="pending">Pending</option>');
            emt.status.select2();
            fetchEmployeeAllPolicies(resp.Data.Info.employee_sid, function(res){
                // Set Policies
                var rows = '';
                var types = [];
                policies = res;
                var typeRows = '';
                res.map(function(policy){
                    if($.inArray(policy.Category, types) === -1){
                        types.push(policy.Category);
                        typeRows += '<option value="'+( policy.Category )+'" '+( policy.Category === resp.Data.Info.Category ? 'selected="true"' : '' )+'>'+( policy.Category )+'</option>';
                    }
                    // rows += `<option value="${policy.sid}">${policy.title.ucwords()} (${policy.timeoff_breakdown != undefined ? policy.timeoff_breakdown.text : 'Unlimited'})</option>`;
                });
                $('#js-edit-modal-types').html(typeRows).select2();
                $('#js-edit-modal-types').trigger('change');
                emt.policies.select2('val', resp.Data.Info.policyId);

                // emt.policies.html(rows).select2();
                // emt.policies.select2('val', resp.Data.Info.policyId);
                $('#js-eme-img').prop('src', getImageURL(resp.Data.Info.img));
                $('#js-eme-name').text(resp.Data.Info.full_name);
                $('#js-eme-tag').text( remakeEmployeeName( resp.Data.Info, false ) );
                $('#js-eme-id').text(getEmployeeId(resp.Data.Info.employee_sid, resp.Data.Info.employee_number));
                $('#js-eme-id').prop('href', "<?=base_url('employee_profile');?>/"+( resp.Data.Info.employee_sid )+"");
                //
                setTimeView(
                    emt.requestTime, 
                    resp.Data.Info.timeoff_breakdown.active
                );
                //
                if(resp.Data.Info.status == 'cancelled') { $('#js-edit-modal-save-btn').hide(0); emt.modal.find('input, textarea, select').prop('disabled', true); emt.status.prepend('<option value="cancelled">Cancelled</option>'); }
                else { $('#js-edit-modal-save-btn').show(0); emt.modal.find('input, textarea, select').prop('disabled', false); }
                emt.status.select2('val', resp.Data.Info.status != 'cancelled' ? resp.Data.Info.level_status : 'cancelled');
                // Show modal
                emt.modal.modal();   
                loader('hide');
                // Multiple days off
                var ld = {};
                ld.startDate = moment(currentRequest.Info.request_from_date, 'YYYY-MM-DD').format('MM-DD-YYYY');
                ld.endDate = moment(currentRequest.Info.request_to_date, 'YYYY-MM-DD').format('MM-DD-YYYY');
                ld.days = currentRequest.Info.timeoff_days;
                //
                $('#js-timeoff-start-date-edit').val(ld.startDate.replace(/-/g,'/'));
                $('#js-timeoff-end-date-edit').val(ld.endDate.replace(/-/g,'/'));
                remakeRangeRowsEdit(ld);
                //
                timePickers();          
            });
        }
        //
        $(document).on('click', '#js-edit-modal-archive-btn', function(e){
            //
            alertify.confirm(
                'Are you sure?',
                'Are you sure you want to archive this time off?',
                function () {
                    var megaOBJ = {};
                    megaOBJ.action = 'update_archive_status';
                    megaOBJ.archive = 1;
                    megaOBJ.companySid = <?=$companyData['sid'];?>;
                    megaOBJ.employeeSid = <?=$employerData['sid'];?>;
                    megaOBJ.requestId = currentRequest.Info.requestId;
                    $.post(
                        baseURI+'handler',
                        megaOBJ,
                        function(resp){
                            alertify.alert('SUCCESS!', resp.Response, function(){
                                window.location.reload();
                            });
                        }
                    );
                },
                function () {
                    alertify.error('Canceled!');
                }).set('labels', { ok: 'Yes', cancel: 'No' });
        });
        //
        $(document).on('click', '#js-edit-modal-active-btn', function(e){
            //
            alertify.confirm(
                'Are you sure?',
                'Are you sure you want to activate this timeoff?',
                function () {
                    var megaOBJ = {};
                    megaOBJ.action = 'update_archive_status';
                    megaOBJ.archive = 0;
                    megaOBJ.companySid = <?=$companyData['sid'];?>;
                    megaOBJ.employeeSid = <?=$employerData['sid'];?>;
                    megaOBJ.requestId = currentRequest.Info.requestId;
                    $.post(
                        baseURI+'handler',
                        megaOBJ,
                        function(resp){
                            alertify.alert('SUCCESS!', resp.Response, function(){
                                window.location.reload();
                            });
                        }
                    );
                },
                function () {
                    alertify.error('Canceled!');
                });
        });
        emt.modal.on('hidden.bs.modal', function(){ $('body').css('overflow-y', 'auto') });

        $(document).on('change', '#js-edit-modal-types', function(){
            var rows = '';
            rows += '<option value="0">[Select a policy]</option>';
            var v = $(this).val();
            //
            if(v == 0){
                $('.js-fmla-wrap').hide(0);
                fmla = {};
                emt.policies.html('');
                emt.policies.select2('destroy');
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
            emt.policies.html(rows);
            emt.policies.select2();
            $('.js-policy-box').show();
        })
        //
        function setHistory(level, employee){
            //
            level = level === undefined ? 'all' : level;
            employee = employee === undefined ? 'all' : employee;
            //
            var 
            rows = '',
            tls = ['Team Lead', 'Supervisor', 'Approvers'],
            data = currentRequest.History;

            loadHistoryData(data);
        }
        //
        function loadHistoryData(data){
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
                        rows += '       <span>'+( h.status.ucwords() )+'</span>';
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
            l = currentRequest.Assigned.length;
            //
            for(i; i < l; i++){
                if(currentRequest.Assigned[i]['sid'] == timeoff_request_assignment_sid) return currentRequest.Assigned[i];
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
        function setTimeView(target, data){
            var row = '';
            if(employeePolicies[0]['format'] == 'D:H:M'){
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Days </label>';
                row += '        <input type="text" class="form-control js-number" value="'+( data.days != undefined ? data.days : '' )+'" id="js-request-days" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" class="form-control js-number" value="'+( data.hours != undefined ? data.hours : '' )+'" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" class="form-control js-number" value="'+( data.minutes != undefined ? data.minutes : '' )+'" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(employeePolicies[0]['format'] == 'D:H'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Days </label>';
                row += '        <input type="text" class="form-control js-number" value="'+( data.days != undefined ? data.days : '' )+'" id="js-request-days" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" class="form-control js-number" value="'+( data.hours != undefined ? data.hours : '' )+'" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(employeePolicies[0]['format'] == 'H:M'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" class="form-control js-number" value="'+( data.hours != undefined ? data.hours : '' )+'" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" class="form-control js-number" value="'+( data.minutes != undefined ? data.minutes : '' )+'" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }else if(employeePolicies[0]['format'] == 'H'){
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Hours </label>';
                row += '        <input type="text" class="form-control js-number" value="'+( data.hours != undefined ? data.hours : '' )+'" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
            }else{
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes </label>';
                row += '        <input type="text" class="form-control js-number" value="'+( data.minutes != undefined ? data.minutes : '' )+'" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }
            //
            $(target).html(row);
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
        function setPolicyTime(days, hours, minutes){
            if(format.match(/D/)) $('#js-request-days').val(days);
            if(format.match(/H/)) $('#js-request-hours').val(hours);
            if(format.match(/M/)) $('#js-request-minutes').val(minutes);
        }
        //
        function getPolicy( sid ){
            var i = 0,
            l = employeePolicies.length;
            //
            for(i; i < l; i++) if(employeePolicies[i]['sid'] == sid) return employeePolicies[i];
            return null;
        }
        //
        function resetEditModal(){
            $('.js-em-page').hide(0);
            $('#js-detail-page').show(0);
            currentRequest = {};
            attachedDocuments = {};
            localDocument = {};
            emt.header.text('');
            emt.tabs.text('');
            emt.noteBox.hide();
            emt.note.val('');
            emt.progress.find('.progress-bar').attr('aria-valuemax', '0');
            emt.progress.find('.progress-bar').attr('aria-valuemin', '0');
            emt.progress.find('.progress-bar').attr('aria-valuenow', '0%');
            emt.progress.find('.progress-percent span').text('0');
            $('.js-edit-modal-partial-check[value="no"]').prop('checked', true);
            $('.js-edit-modal-partial-check[value="no"]').trigger('click');
            $('.js-fmla-check[value="no"]').prop('checked', true);
            $('.js-fmla-check[value="no"]').trigger('click');
            $('#js-attachment-data-area').html('');
            CKEDITOR.instances[emt.comment].setData('');
        }
        // Employees
        function fetchEmployees(){
            $.post(baseURI+'handler', {
                action: 'get_my_team_employees',
                companySid: <?=$company_sid;?>,
                isSuper : <?=$employerData['access_level_plus'];?>,
                employeeSid : <?=$employerData['sid'];?>
            }, function(resp){
                    if(resp.Status === false){
                        loader('hide');
                        console.log('Failed to load employees.');
                        return;
                    }
                    employees = resp.Data;
                    var rows = '<option value="all">All</option>';
                    employees.map(function(v){
                        rows += '<option value="'+( v.employee_sid )+'">'+( remakeEmployeeName(v) )+'</option>';
                    });
                    $('#js-filter-employee').html(rows);
                    $('#js-filter-employee').select2();
            });
        }
        // Policies
        function fetchAllPolicies(){
            $.post(baseURI+'handler', {
                action: 'get_all_company_policies',
                companySid: <?=$company_sid;?>
            }, function(resp){
                    if(resp.Status === false){
                        loader('hide');
                        console.log('Failed to load policies.');
                        return;
                    }
                    policies = resp.Data;
                    var rows = '';
                    rows += '<option value="all">All</option>';
                    if(policies.length != 0){
                        policies.map(function(v){
                            rows += '<option value="'+( v.policyId )+'">'+( v.policy_title.ucwords() )+'</option>';
                        });
                    }
                    $('#js-filter-policies').html(rows);
                    $('#js-filter-policies').select2();
            });
        }
        // Fetch employee all policies
        function fetchEmployeeAllPolicies(employeeId, cb){
            $.post(baseURI+'handler', {
                action: 'get_employee_all_policies',
                companySid: <?=$company_sid;?>,
                employeeSid: employeeId
            }, function(resp){
                if(resp.Status === false){
                    loader('hide');
                    console.log('Failed to load policies.');
                    return;
                }
                //
                employeePolicies = resp.Data;
                cb(resp.Data);
            });
        }

        
        //
        $('.js-popover').popover({
            html: true,
            trigger: 'hover',
            placement: 'right'
        });

        function timePickers(){
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

            $('#js-vacation-return-date-edit').datepicker({
                format: 'mm-dd-yyyy',
                beforeShowDay: unavailable,
                minDate: 1
            });
            //
            $('#js-bereavement-return-date-edit').datepicker({
                format: 'mm-dd-yyyy',
                beforeShowDay: unavailable,
                minDate: 1
            });
            //
            $('#js-compensatory-return-date-edit').datepicker({
                format: 'mm-dd-yyyy',
                beforeShowDay: unavailable,
                minDate: 1
            });

            //
            $('#js-compensatory-start-time-edit').datetimepicker({
                datepicker: false,
                format: 'g:i A',
                formatTime: 'g:i A',
                step: 15
            });

            $('#js-compensatory-end-time-edit').datetimepicker({
                datepicker: false,
                format: 'g:i A',
                formatTime: 'g:i A',
                step: 15,
                onShow: function(d){
                    this.setOptions({
                        minTime: $('#js-compensatory-start-time-edit').val() ? $('#js-compensatory-start-time-edit').val() : false
                    });
                }
            });
        }

        $('#js-edit-modal').on('hidden.bs.modal', function(){
            $('body').removeClass('modal-open').css('overflow-y', 'auto');
        });
         //
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
            if($.inArray(sd.format('MM-DD-YYYY'), offdates) === -1 && $.inArray( sd.format('dddd').toLowerCase(), timeOffDays) === -1){
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
        }
        //
        if(rows == '') return '';
        //
        $('#js-timeoff-date-box-edit tbody').html(rows);
        $('#js-timeoff-date-box-edit').show();
    }

    //
    function setTimeViewEdit(target, prefix, data){
        var row = '';
        if(employeePolicies[0]['format'] == 'D:H:M'){
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
        else if(employeePolicies[0]['format'] == 'D:H'){
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
        else if(employeePolicies[0]['format'] == 'H:M'){
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
        }else if(employeePolicies[0]['format'] == 'H'){
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

    function getPrintDownloadButtons(
        requestSid,
        panel
    ){
        var 
        urls = {
            print: '<?=base_url('timeoff/public/pd/print');?>/'+requestSid,
            download: '<?=base_url('timeoff/public/pd/download');?>/'+requestSid
        },
        rows = '';
        //
        panel = panel === undefined ? 'gp' : panel;
        //
        rows += '<a href="'+( urls.download )+'" class="pull-right" style="color: #0000ff;" target="_blank"><i class="fa fa-download" title="Download Time Off"></i></a>';
        rows += '<a href="'+( urls.print )+'" class="pull-right" style="color: #0000ff;" target="_blank"><i class="fa fa-print" title="Print Time Off"></i></a>';

        return rows;
    }

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
    //
    google.charts.load('current', {packages:["orgchart"]});
    //
    function setRequestFlow(){
        //
        let ar = [];
        ar.push(['Request', '', '']);
        //
        let tl = false;
        let sp = false;
        let ap = false;
        //
        let 
        hasTL = false,
        hasSP = false,
        hasAP = false;
        //
        currentRequest.Assigned.map((v) => {
            if(v.role == 'teamlead') hasTL = true;
            else if(v.role == 'supervisor') hasSP = true;
            else if(v.role == 'approver') hasAP = true;
        });

        //
        let 
        dTL = 'Request',
        dSP = 'Team Lead',
        dAP = 'Supervisor';
        //
        if( !hasTL ) {
            dTL = '';
            dSP = 'Request';
            dAP = 'Supervisor';
        } 
        if( !hasSP ) {
            dTL = '';
            dSP = '';
            dAP = 'Request';
        }
        
        //
        currentRequest.Assigned.map((v) => {
            //
            let bt = 'Request';
            //
            if(v.role == 'teamlead') {
                if(!tl){
                    ar.push(['Team Lead', 'Request', '']);
                    tl = true;
                }
                bt = 'Team Lead';
            }
            if(v.role == 'supervisor') {
                if(!sp){
                    ar.push(['Supervisor', dSP, '']);
                    tl = true;
                }
                bt = 'Supervisor';
            }
            if(v.role == 'approver') {
                if(!ap){
                    ar.push(['Approver', dAP, '']);
                    tl = true;
                }
                bt = 'Approver';
            }
            //
            ar.push([
                {
                    v: v.full_name,
                    f: `${v.full_name} <div>( ${v.role.toUpperCase() } )</div>`
                }, bt, ''
            ]);
        });
        //
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('string', 'Manager');
        data.addColumn('string', 'ToolTip');

        // For each orgchart box, provide the name, manager, and tooltip to show.
        data.addRows(ar);
        var selectedArray = new Array();
        
        // Create the chart.
        var chart = new google.visualization.OrgChart(document.getElementById('js-flow-data-append'));
        // Draw the chart, setting the allowHtml option to true for the tooltips.
        chart.draw(data, {'allowHtml':true});
    }
        //
        <?php $this->load->view('timeoff/scripts/attachment'); ?>
        
    })

</script>
