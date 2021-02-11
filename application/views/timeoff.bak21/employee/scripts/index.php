<script>
var intervalHolder = null;
$(function() {
    $('.js-to-heading').text('Time Off');
    var
        requests = {},
        filterActive = false,
        dataTarget = $('.js-data-load-area'),
        currentRequest = {},
        employeePolicies = [],
        holidayDates = <?=json_encode($holidayDates);?>,
        timeOffDays = <?=json_encode($timeOffDays);?>,
        overwrite = {
            'teamlead': 'Team Lead',
            'supervisor': 'Supervisor',
            'approver': 'Approver'
        },
        FMLACategories = {
            health: 'Certification of Health Care Provider for Employee’s Serious Health Condition',
            medical: 'Notice of Eligibility and Rights & Responsibilities',
            designation: 'Designation Notice'
        },
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
        },
        fetchRequestOBJ = {
            action: 'fetch_employee_requests',
            fromDate: 'all',
            noDraft: true,
            toDate: 'all',
            status: 'all',
            employeeSid: <?= $sid; ?> ,
            companySid : <?= $companyData['sid']; ?>
        };

        var offdates = [];
    //
    if(holidayDates.length != 0){
        for(obj in holidayDates){
            if(holidayDates[obj]['work_on_holiday'] == 0) offdates.push(holidayDates[obj]['from_date']);
        }
    }

    // Filter
    $('#js-filter-from-date').datepicker({
        format: 'mm/dd/yyyy',
        onSelect: function(dt) {
            $('#js-filter-to-date').datepicker("option", "minDate", dt);
        }
    });
    $('#js-filter-to-date').datepicker({
        format: 'mm/dd/yyyy',
        onSelect: function(dt) {
            $('#js-filter-from-date').datepicker("option", "maxDate", dt);
        }
    });
    //
    $('#js-filter-status').select2();
    //
    $('#js-filter-apply-btn').click(function(e) {
        e.preventDefault();
        if ($('#js-filter-from-date').val() != '') fetchRequestOBJ.fromDate = moment($(
            '#js-filter-from-date').val(), 'MM/DD/YYYY').format('YYYY-MM-DD');
        if ($('#js-filter-to-date').val() != '') fetchRequestOBJ.toDate = moment($('#js-filter-to-date')
            .val(), 'MM/DD/YYYY').format('YYYY-MM-DD');
        if ($('#js-filter-status').val() != null) fetchRequestOBJ.status = $('#js-filter-status').val();
        //
        fetchRequests();
    });
    $('#js-filter-reset-btn').click(function(e) {
        e.preventDefault();
        fetchRequestOBJ.fromDate = 'all';
        fetchRequestOBJ.toDate = 'all';
        fetchRequestOBJ.status = 'all';
        //
        $('#js-filter-from-date').val('');
        $('#js-filter-to-date').val('');
        $('#js-filter-status').select2('val', 'all');
        //
        fetchRequests();
    });
    //
    setPoliciesList();
    //
    setHolidayList();
    //
    fetchRequests();

    function setHolidayList() {
        $.post(baseURI + 'handler', {
            action: 'get_company_all_holidays',
            companySid: <?= $company_sid; ?>
        }, function(resp) {
            if (resp.Status === false) {
                console.log(resp.Response);
                return;
            }
            if (resp.Data.length === 0) return;
            //
            var target = $('.js-holiday-list-ul'),
                holidays = resp.Data,
                rows = '',
                trs = '';
            //
            holidays.map(function(holiday) {
                rows += '<li><p><strong>' + (getHolidayText(holiday)) + '</strong><br />' + (
                    holiday.holiday_title) + '<p></li>';
                trs += '<tr><td><strong>' + (holiday.holiday_title) + '</strong></td><td>' + (
                    getHolidayText(holiday)) + '</td></tr>';
            });
            target.css({
                'height': '200px',
                'overflow-y': 'hidden'
            });

            target.html(rows);
            rows =
                '<hr /><p class="text-center text-info js-expand-holiday" style="cursor: pointer; color: #81b431;"><strong>See More</strong></p>';
            target.parent().append(rows);
            $('#js-timeoff-holiday-list-modal tbody').html(trs);
        });
    }

    $('#js-company-holidays-btn').click(function() {
        $('#js-timeoff-holiday-list-modal').modal();
    })
    //
    $(document).on('click', '.js-expand-holiday', function() {
        $(this).removeClass('js-expand-holiday').addClass('js-less-holiday').text('See Less');
        $('.js-holiday-list-ul').css({
            'height': 'auto',
            'overflow-y': 'auto'
        });
    });
    $(document).on('click', '.js-less-holiday', function() {
        $(this).removeClass('js-less-holiday').addClass('js-expand-holiday').text('See More');
        $('.js-holiday-list-ul').css({
            'height': '200px',
            'overflow-y': 'hidden'
        });
    });
    //
    $(document).on('click', '.js-request-title-li', function(e) {
        //
        e.preventDefault();
        //
        $('.js-request-title-li').parent().removeClass('active');
        $(this).parent().addClass('active');
        //
        $('#js-data-load-area tr').hide();
        $('#js-data-load-area tr[data-type="' + ($(this).data('key')) + '"]').show();
    });
    //
    $(document).on('click', '.js-cancel-request', function(e) {
        //
        e.preventDefault();
        //
        var megaOBJ = {};
        megaOBJ.action = 'cancel_employee_request';
        megaOBJ.requestId = $(this).closest('tr').data('id');
        megaOBJ.employeeSid = <?= $sid; ?> ;
        megaOBJ.companySid = <?= $companyData['sid'] ?> ;
        //
        alertify.confirm('Do you really want to cancel this Time Off request?', function() {
            cancelEmployeeRequest(megaOBJ);
        }).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });
    });
    //
    $('#js-apply-filter-btn').click(function(e) {
        e.preventDefault();
        $('#js-filter').toggle();
    });
    //
    function cancelEmployeeRequest(obj) {
        $.post(handlerURI, obj, function(resp) {
            alertify.alert(resp.Response, function() {
                window.location.reload();
            });
        });
    }
    //
    function fetchRequests() {
        loader('show', 'Please wait, while we are fetching your requests.');
        $.post(handlerURI, fetchRequestOBJ, function(resp) {
            //
            if (resp.Status === false) {
                loader('hide');
                $('#js-data-load-area').html('<tr><td colspan="' + ($('.js-data-head tr th').length) +
                    '"><p class="alert alert-info text-center">No Time Off Requests Found!</p></td></tr>'
                    );
                return;
            }
            //
            setEmployeeRequestTable(
                resp.Data
            );
        });
    }
   
    //
    function setEmployeeRequestTable(requests) {
        //
        $('#js-data-load-area').html('');
        $('.js-request-title-li').remove();
        //
        let ob = {
            1: 0,
            2: 0,
            3: 0,
            4: 0,
            5: 0,
            6: 0,
            7: 0,
            8: 0,
            9: 0,
            10: 0,
            11: 0,
            12: 0
        }
        var
            rows = '',
            trows = '';
        if (requests.length == 0) rows += '<tr><td colspan="' + ($('thead tr th').length) +
            '"><p class="alert alert-info text-center">No requests found</p></td></tr>';
        else {
            var i = 0,
                l = requests.Requests.length;
            //
            for (i; i < l; i++) {
                //
                if(requests['Requests'][i]['status'] == 'approved'){
                    ob[
                        moment(requests['Requests'][i]['requested_date']).format('M')
                    ]++;
                }
                rows += '<tr data-type="' + (requests['Requests'][i]['Category']['category_name'].toLowerCase()
                        .replace(/\s+/g, '_')) + '" data-id="' + (requests['Requests'][i]['requestId']) +
                    '" style="display: none;">';
                rows += '   <td>';
                rows += '       <div class="upcoming-time-info">';
                rows += '            <div class="icon-image">';
                rows += '                   <img src="<?=base_url('assets');?>/images/upcoming-time-off-icon.png" class="emp-image" alt="emp-1">';
                rows += '             </div>';
                rows += '             <div class="text">';
                rows += '                  <h4>' + (moment(requests['Requests'][i]['requested_date'],'YYYY-MM-DD').format(timeoffDateFormat)) + '</h4>';
                rows += '                  <p>' + (requests['Requests'][i]['requested_time'] == 0 ?
                    'Unlimited' : requests['Requests'][i]['timeoff_breakdown']['text']) + ' of <span>' + (
                    requests['Requests'][i]['policy_title']) + '</span></p>';
                rows += '             </div>';
                rows += '       </div>';
                rows += '   </td>';
                rows += '   <td style="vertical-align: middle;">';
                rows += '      <div>';
                rows += '           <p class="cs-status text-center ' + (requests['Requests'][i]['status']
                    .toLowerCase() == 'approved' ? 'text-success' : 'text-danger') + '"><span class="' + (
                    requests['Requests'][i]['status'].toLowerCase()) + '-stat">' + (requests['Requests'][i][
                    'status'
                ].toUpperCase()) + '</span></p>';
                rows += '      </div>';
                rows += '    </td>';
                rows += '   <td style="vertical-align: middle;">';
                rows +=
                    '           <p class="text-center js-popovers"  data-trigger="hover" data-placement="left" title="Comment" data-content="' +
                    (requests['Requests'][i]['status'].toLowerCase() == 'approved' && requests['Requests'][i][
                        'History'
                    ] != null ? requests['Requests'][i]['History']['reason'] : requests['Requests'][i][
                        'reason'
                    ]) + '"><i class="fa fa-list-alt ' + (requests['Requests'][i]['History'] != null ||
                        requests['Requests'][i]['reason'] != '' ? 'text-success' : '') + '"></i></p>';
                rows += '    </td>';
                rows += '    <td>';
                rows += '        <div class="progress-bar-custom">';
                rows += '            <div class="progress-bar-tooltip">';
                rows += '                <div class="progress">';
                rows +=
                    '                    <div class="progress-bar progress-bar-success progress-bar-success-blue" role="progressbar" aria-valuenow="' +
                    (requests.Requests[i]['Progress']['CompletedPercentage']) +
                    '" aria-valuemin="0" aria-valuemax="' + (requests.Requests[i]['Progress']['Total']) +
                    '" style="width: ' + (requests.Requests[i]['Progress']['CompletedPercentage']) + '%">';
                rows += '                        <div class="sr-only"></div>';
                rows += '                    </div>';
                rows += '                </div>';
                rows += '            </div>';
                rows += '            <div class="progress-percent progress-percent-custom">' + (requests
                    .Requests[i]['Progress']['CompletedPercentage']) + '% Completed</div>';
                rows += '        </div>';
                rows += '    </td>';
                rows += '    <td colspan="2">';
                rows += '        <a href="javascript:void()" class=" js-edit-request">';
                rows += '            <div class="text-action text-success">';
                rows +=
                    '                <p class="text-center text-success" title="Edit Time off"><i class="fa fa-pencil"></i></p>';
                rows += '            </div>';
                rows += '        </a>';
                rows += '    </td>';
                // rows += '    <td>';
                // rows += '        <a href="javascript:void()" class="'+( requests.Requests[i]['status'] != 'cancelled' && requests.Requests[i]['expired'] != true ? 'js-cancel-request' : '' )+'" '+( requests.Requests[i]['status'] != 'cancelled' && requests.Requests[i]['expired'] != true ? '' : 'style="cursor: context-menu;"' )+'>';
                // rows += '            <div class="text-action text-danger">';
                // rows += '                <p class="text-center">'+( requests.Requests[i]['status'] != 'cancelled' && requests.Requests[i]['expired'] != true ? 'Cancel' : '-' )+'</p>';
                // rows += '            </div>';
                // rows += '        </a>';
                // rows += '    </td>';
                rows += '</tr>';
            }

            // set title
            i = 0;
            l = requests.Titles.length;

            for (i; i < l; i++) {
                trows += '<li class="' + (i == 0 ? 'active active-tab' : '') +
                    '"><a href="javascript:void(0)" data-key="' + (requests.Titles[i].toLowerCase().replace(
                        /\s+/g, '_')) + '" class="js-request-title-li">' + (requests.Titles[i]) + '</a></li>';
            }
        }
        //
        $('#js-data-load-area').html(rows);
        $('.js-request-titles').prepend(trows);
        $('#js-data-load-area tr[data-type="' + (requests.Titles[0].toLowerCase().replace(/\s+/g, '_')) + '"]')
            .show();
        $('.js-popovers').popover({
            html: true
        });
        loader('hide');
        //
        loadTimeLineGraph(ob)
    }
    //
    function setPoliciesList() {
        if (policies.length == 0) {
            intervalHolder = setInterval(function() {
                setPoliciesList();
            }, 1000);
            return;
        }
        //
        clearInterval(intervalHolder);
        //
        let 
            pTotal = 0,
            pConsumed = 0,
            pPending = 0;
        //
        policies.map(function(policy) {
            pTotal += parseFloat(policy.TotalBreakDown.active.hours);
            pConsumed += parseFloat(policy.ConsumedBreakDown.active.hours);
            pPending += parseFloat(policy.PendingBreakDown.active.hours);
        });
        //
        loadHourGraph(pTotal, pConsumed, pPending);
    }

    //
    function loadHourGraph(t, c, p){
        var myDoughnutChart = new Chart(document.getElementById('jsHourGrpah'), {
                type: 'doughnut',
                data: {
                    datasets: [{
                        label: '# of hours',
                        data: [
                            p, 
                            c
                        ],
                        backgroundColor: [
                            '#0000ff',
                            '#81b431',
                            'rgba(255, 206, 86, 1)'
                        ],
                        borderColor: [
                            '#0000ff',
                            '#81b431',
                            'rgba(255, 206, 86, 1)',
                        ],
                        borderWidth: 1
                    }],
                    // These labels appear in the legend and in the tooltips when hovering different arcs
                    labels: [
                        `Pending Hours ${p}`,
                        `Consumed Hours ${c}`
                    ],
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        showAllTooltips: true
                    },
                },
            });
    }

    //
    function loadTimeLineGraph(ob){
        var myDoughnutChart2 = new Chart(document.getElementById('jsBarGrpah'), {
            type: 'bar',
            data: {
                datasets: [{
                    label: `Time-offs for ${moment().format('YYYY')}`,
                    barPercentage: 1,
                    barThickness: 6,
                    minBarThickness: 80,
                    minBarLength: 0,
                    data: [
                        ob[1],
                        ob[2],
                        ob[3],
                        ob[4],
                        ob[5],
                        ob[6],
                        ob[7],
                        ob[8],
                        ob[9],
                        ob[10],
                        ob[11],
                        ob[12]
                    ],
                    backgroundColor: [
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                    ],
                    borderColor: [
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                        '#81b431',
                    ],
                    borderWidth: 1
                }],
                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: [
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December',
                ],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            barPercentage: 1,
                            stacked: true
                        }],
                        yAxes: [{
                            barPercentage: 1,
                            stacked: true
                        }]
                    }
                },
            },
        });

    }


    // View mode
    $(document).on('click', '.js-edit-request', loadEditPage);
    //
    function loadEditPage(e) {
        e.preventDefault();
        resetEditModal();
        //
        loader('show', 'Please wait, while we are fetching Time Off request details.');
        var megaOBJ = {};
        megaOBJ.action = 'get_single_request';
        megaOBJ.requestSid = $(this).closest('tr').data('id');
        megaOBJ.companySid = <?= $company_sid; ?> ;
        megaOBJ.employeeSid = <?= $employerData['sid']; ?> ;
        //
        $.post(baseURI + 'handler', megaOBJ, function(resp) {
            //
            if (resp.Status === false) {
                alertify.alert('ERROR!', resp.Response);
                return;
            }
            //
            startEditProcess(resp);
        });
    }

    isManager = true;
    //
    function startEditProcess(resp) {
        currentRequest = resp.Data;
        //
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
         // Set progress bar
        emt.progress.find('.progress-bar').css('width', `${resp.Data.Info.Progress.CompletedPercentage}%`);
        emt.progress.find('.progress-bar').attr('aria-valuemin', '0');
        emt.progress.find('.progress-bar').attr('aria-valuemax', '100');
        emt.progress.find('.progress-bar').attr('aria-valuenow', `${resp.Data.Info.Progress.CompletedPercentage}%`);
        emt.progress.find('.progress-percent span').text(resp.Data.Info.Progress.CompletedPercentage);
        //
        if (CKEDITOR.instances['js-edit-modal-reason'] === undefined) CKEDITOR.replace('js-edit-modal-reason');
        if (CKEDITOR.instances['js-edit-modal-comment'] === undefined) CKEDITOR.replace('js-edit-modal-comment');
        //
        CKEDITOR.instances['js-edit-modal-reason'].setData(resp.Data.Info.reason);
        //
        //
        if(resp.Data.Info.level_status == 'pending' && resp.Data.Info.status == 'pending'){
            resp.Data.Info.level_status = 'approved';
            resp.Data.Info.status = 'approved';
        }
        if(resp.Data.Info.status != 'pending' ){
            resp.Data.Info.level_status = resp.Data.Info.status;
        }
        //
        //
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
        //
        $('#js-timeoff-start-date-view').val(resp.Data.Info.request_from_date);
        $('#js-timeoff-end-date-view').val(resp.Data.Info.request_to_date);
        //
        // Let's load the basic template and modal
        fetchEmployeeAllPolicies(resp.Data.Info.employee_sid, function(res) {
            // Set Policies
            policies = res;
            // Set policy on DOM
            let 
            types = [],
            typeRows = '';
            res.map(function(policy) {
                if ($.inArray(policy.Category, types) === -1) {
                    types.push(policy.Category);
                    typeRows += '<option value="' + (policy.Category) + '" ' + (policy
                            .Category === resp.Data.Info.Category ? 'selected="true"' : '') +
                        '>' + (policy.Category) + '</option>';
                }
            });
            // Set select2
            $('#js-edit-modal-types').html(typeRows).select2();
            $('#js-edit-modal-types').trigger('change');
            $('#js-edit-modal-policies').html('<option value="0">[Select Policy]</option>').select2();
            $('#js-edit-modal-status').select2();
            // Select values
            $('#js-edit-modal-policies').select2('val', resp.Data.Info.policyId);
            $('#js-edit-modal-status').select2('val', resp.Data.Info.level_status);
            $('#js-eme-img').prop('src', getImageURL(resp.Data.Info.img));
            $('#js-eme-name').text(resp.Data.Info.full_name);
            $('#js-eme-tag').text( remakeEmployeeName( resp.Data.Info, false ) );
            $('#js-eme-id').text(getEmployeeId( resp.Data.Info.employee_sid,  resp.Data.Info.employee_number ));
            $('#js-eme-id').prop('href', "<?=base_url('employee_profile');?>/"+( resp.Data.Info.employee_sid )+"");
            // Add datetime pickers
            $('#js-timeoff-start-date-view').datepicker({
                format: 'mm/dd/yyyy',
                changeMonth: true,
                beforeShowDay: unavailable,
                onSelect: function(d){
                    $('#js-timeoff-end-date-edit').datepicker('option', 'minDate', d);
                    $('#js-timeoff-end-date-edit').val(d);

                    remakeRangeRowsEdit();
                }
            });
            //
            $('#js-timeoff-end-date-view').datepicker({
                format: 'mm/dd/yyyy',
                changeMonth: true,
                onShow: function(){
                    $('#js-timeoff-end-date-edit').datepicker('option', 'minDate', d);
                    $('#js-timeoff-end-date-edit').val(d);
                },
                onSelect: remakeRangeRowsEdit
            });
            //
            $('#js-edit-modal-types').html(typeRows).select2();
            $('#js-edit-modal-types').trigger('change');
            emt.policies.select2('val', resp.Data.Info.policyId);
            $('#js-eme-img').prop('src', getImageURL(resp.Data.Info.img));
            $('#js-eme-name').text(resp.Data.Info.full_name);
            $('#js-eme-tag').text( remakeEmployeeName( resp.Data.Info, false ) );
            $('#js-eme-id').text(getEmployeeId( resp.Data.Info.employee_sid,  resp.Data.Info.employee_number ));
            $('#js-eme-id').prop('href', "<?=base_url('employee_profile');?>/"+( resp.Data.Info.employee_sid )+"");
            
            // Multiple days off
            let ld = {};
            ld.startDate = moment(currentRequest.Info.request_from_date, 'YYYY-MM-DD').format('MM-DD-YYYY');
            ld.endDate = moment(currentRequest.Info.request_to_date, 'YYYY-MM-DD').format('MM-DD-YYYY');
            ld.days = currentRequest.Info.timeoff_days;
            //
            $('#js-timeoff-start-date-edit').val(ld.startDate.replace(/-/g,'/'));
            $('#js-timeoff-end-date-edit').val(ld.endDate.replace(/-/g,'/'));
            remakeRangeRowsEdit(ld);

            // Show modal
            emt.modal.modal();
            loader('hide');
            //
            if(resp.Data.Info.archive == '0'){
                $('#js-edit-modal-archive-btn').show();
                $('#js-edit-modal-active-btn').hide();
            } else{
                $('#js-edit-modal-active-btn').show();
                $('#js-edit-modal-archive-btn').hide();
            }
            //
            timePickers();
        });
    }

    $(document).on('select2:open',  emt.policies, function(){
        //
        $('.jsPopover').popover({
            html: true,
            trigger: 'hover'
        });
        //
        $('.csSelect2Error')
        .parent()
        .removeClass('bg-danger')
        .removeClass('csSelect2ErrorLi');
        //
        $.each($('.csSelect2Error'), function(){
            if($(this).hasClass('bg-danger')) {
                $(this).removeClass('bg-danger');
                $(this).parent()
                .addClass('bg-danger')
                .addClass('csSelect2ErrorLi');
            }
        })
    })


    //
    $(document).on('click', '#js-edit-modal-archive-btn', function(e){
            //
            var _this = $(this);
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
                                // window.location.reload();
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


    // Save TO from modal
    $(document).on('click', '#js-edit-modal-save-btn', function(){
            // if(isManager) return;
            var megaOBJ = {};
            megaOBJ.action = 'update_employee_timeoff';
            megaOBJ.approver = 1;
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
            var checkBalance = (parseInt(t.actual_allowed_timeoff) + parseInt(getNegativeBalance(t))) - parseInt(t.consumed_timeoff) ;

            if(megaOBJ.status != 'pending'){
                checkBalance = checkBalance + parseInt( currentRequest.Info.timeoff_breakdown.M.minutes );
            }
            //
            if (megaOBJ.status == 'approved') {
                if(t.is_unlimited == '0' && (requestedDays.totalTime > checkBalance || checkBalance < 0)){
                    alertify.alert('WARNING!', 'Requested time off can not be greater than allowed time.');
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
            megaOBJ.allowedTimeOff = getAllowedTimeInMinutes( getPolicy( megaOBJ.policyId ) );
            megaOBJ.requestId = currentRequest.Info.requestId;
            megaOBJ.request = currentRequest.Info;
            megaOBJ.tls = currentRequest.Assigned;
             //
            if(megaOBJ.isPartial === 1){
                if(megaOBJ.startTime == ''){
                    alertify.alert('WARNING!', 'Start time of partial is required.', function(){ return; });
                    return;       
                }
                if(megaOBJ.endTime == ''){
                    alertify.alert('WARNING!', 'End time of partial is required.', function(){ return; });
                    return;       
                }
            }
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
    //
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
                rows += `<option ${ policy.PolicyStatus.Reason != '' ? `data-reason="${policy.PolicyStatus.Reason}"` : '' } value="${policy.sid}">${policy.title} (${policy.timeoff_breakdown !== undefined ? policy.timeoff_breakdown.text : 'Unlimited'})</option>`;
            }
        });
        emt.policies.html(rows);
        emt.policies.select2({
            templateResult: (item) => {
                if (!item.id) {
                    return item.text;
                }
                return `<div  ${ $(item.element).data('reason') !== undefined ? 'class="csSelect2Error bg-danger"' : ''} >${item.text} ${ $(item.element).data('reason') !== undefined ? ` <i class="fa fa-question-circle jsPopover" data-title="Why?" data-content="${$(item.element).data('reason')}"></i>` : ''}</div>`;
            },
            escapeMarkup: (i) => {
                return i;
            }
        });
        $('.js-policy-box').show();
    });
    //
    function setHistory(level, employee) {
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
    function loadHistoryData(data) {
        $('#js-history-data-area').pagination({
            dataSource: data,
            pageSize: 5,
            showPrevious: true,
            showNext: true,
            callback: function(hs, pagination) {
                var rows = '';
                hs.map(function(h, i) {
                    var
                        employeeData = getEmployeeRecord(h.timeoff_request_assignment_sid),
                        policyData = getPolicyRecord(h.timeoff_request_assignment_sid);

                    rows += '<div class="rows cs-timeoff-history-row">';
                    rows += '   <div class="col-sm-12" ' + (i % 2 == 0 ?
                        'style="background-color: #f1f1f1; padding: 10px;"' : '') + '>';
                    rows += '   <div class="col-sm-3 cs-row">';
                    rows += '       <h5><b>Employee</b></h5>';
                    rows += '       <span>' + (employeeData['full_name']) + ' (' + (
                        overwrite[employeeData['role']]) + ')</span>';
                    rows += '   </div>';
                    rows += '   <div class="col-sm-3 cs-row">';
                    rows += '       <h5><b>Date</b></h5>';
                    rows += '       <span>' + (h.requested_date != '0000-00-00' && h
                            .requested_date != '' && h.requested_date != null ? moment(h
                                .requested_date, 'YYYY-MM-DD').format('MM-DD-YYYY') : '-') +
                        '</span>';
                    rows += '   </div>';
                    rows += '   <div class="col-sm-3 cs-row">';
                    rows += '       <h5><b>Time</b></h5>';
                    rows += '       <span>' + (h.timeoff_breakdown.text ==
                        '0 hour, 0 minute' ? '-' : h.timeoff_breakdown.text) + '</span>';
                    rows += '   </div>';
                    rows += '   <div class="col-sm-3 cs-row">';
                    rows += '       <h5><b>Status</b></h5>';
                    rows += '       <span>' + (h.status.ucwords()) + '</span>';
                    rows += '   </div>';
                    rows += '   <div class="col-sm-12 cs-row">';
                    if (policyData != 'N/A') {
                        rows += '       <p>';
                        rows += '           <strong>Policy Name:</strong> ' + (policyData[
                            'title']) + '';
                        rows += '       </p>';
                    }
                    if (h.start_time != null && h.start_time != '') {
                        rows += '       <p>';
                        rows += '           <strong>Partial Time:</strong> ' + (h
                            .start_time) + ' - ' + (h.end_time) + '';
                        rows += '       </p>';
                    }
                    if (h.reason.trim() != '') {
                        rows += '       <p>';
                        rows += '           <strong>Comment:</strong> ' + (h.reason == '' ?
                            '-' : h.reason) + '';
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
    function resetEditModal() {
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
        $(emt.comment).val('');
    }
    // Fetch employee all policies
    function fetchEmployeeAllPolicies(employeeId, cb) {
        $.post(baseURI + 'handler', {
            action: 'get_employee_all_policies_wr',
            companySid: <?= $company_sid; ?> ,
            employeeSid : employeeId
        }, function(resp) {
            if (resp.Status === false) {
                loader('hide');
                console.log('Failed to load policies.');
                return;
            }
            //
            employeePolicies = resp.Data;
            cb(resp.Data);
        });
    }
    // Set attachment views
    function setAttachments() {
        //
        var
            attachments = currentRequest.Attachments;

        var rows = '';
        rows += '<div class="pto-foot-print-listing">';
        rows += '    <div class="roww">';
        rows += '        <div class="col-sm-12">';
        rows += '           <h3>Documents </h3>';
        rows += '        </div>';
        rows += '    </div>';
        rows += '    <div class="roww">';
        rows += '        <div class="col-sm-12">';
        rows += '            <div class="responsive">';
        rows += '                   <table class="table table-striped">';
        rows += '                         <thead>';
        rows += '                           <tr>';
        rows += '                              <th>Document Title</th>';
        rows += '                              <th>Document Type</th>';
        rows += '                              <th>Created At</th>';
        rows += '                              <th>Action</th>';
        rows += '                           </tr>';
        rows += '                         </thead>';
        rows += '                         <tbody id="js-attachment-tbody">';
        rows += '                         </tbody>';
        rows += '                   </table>';
        rows += '            </div>';
        rows += '        </div>';
        rows += '    </div>';
        rows += '</div>';

        $('#js-attachment-data-area').html(rows);
        //
        rows = '';
        rows =
            '<tr class="js-no-records"><td colspan="4"><p class="alert alert-info text-center">No attachments found.</p></td></tr>';
        if (attachments.length !== 0) {
            rows = '';
            attachments.map(function(attachment) {
                let sd = attachment.serialized_data == null ? {} : JSON.parse(attachment
                    .serialized_data);

                attachment.showEmployerSection = sd.hasOwnProperty('type') && attachment.document_title
                    .toLowerCase() == 'health' ? true : false;

                attachedDocuments[attachment.sid] = {
                    id: attachment.sid,
                    created_at: moment(attachment.created_at).format('MM-DD-YYYY'),
                    type: attachment.document_type,
                    serialized_data: attachment.serialized_data,
                    file: {
                        name: sd.hasOwnProperty('type') ? sd.type : attachment.s3_filename,
                        s3_filename: attachment.s3_filename
                    },
                    showEmployerSection: attachment.showEmployerSection,
                    slug: attachment.document_title.replace(/[^0-9a-zA-Z.]/g, '_').toLowerCase(),
                    title: attachment.document_type == 'generated' ? FMLACategories[attachment
                        .document_title.toLowerCase()] : attachment.document_title
                };
                //
                rows += '<tr class="js-attachments" data-id="' + (attachment.sid) + '">';
                rows += '   <td>' + (attachment.document_type == 'generated' ? FMLACategories[attachment
                    .document_title.toLowerCase()] : attachment.document_title) + '</td>';
                rows += '   <td>' + (attachment.document_type.ucwords()) + '</td>';
                rows += '   <td>' + (moment(attachment.created_at).format('MM-DD-YYYY')) + '</td>';
                rows += '   <td>';
                if (attachment.document_type == 'uploaded') {
                    rows += '       <button class="btn btn-success js-attachment-view">View</button>';
                } else if (attachment.s3_filename != null) {
                    rows += '       <button class="btn btn-success js-attachment-view">View</button>';
                } else {
                    rows += '       <button class="btn btn-success js-attachment-view">View</button>';
                }
                rows += '   </td>';
                rows += '</tr>';
            });
        }
        $('#js-attachment-tbody').html(rows);
    }
    // Page Shifter
    $(document).on('click', '.js-tab-btn', function(e) {
        //
        $('.js-tab-btn').removeClass('active');
        $(this).addClass('active');

        $('.js-em-page').fadeOut();
        $(`#${$(this).find('a').data('target')}`).fadeIn();
    });
    $(document).on('click', '.js-attachment-view', viewDocument);

    function viewDocument() {
        var file = attachedDocuments[$(this).closest('tr').data('id')];
        if (file.type === 'uploaded') {
            // Generate modal content
            var URL = '';
            var iframe = '';
            if (file.file.name.match(/(.doc|.docx|.ppt|.pptx)$/) !== null) {
                URL = "https://view.officeapps.live.com/op/embed.aspx?src=<?=AWS_S3_BUCKET_URL;?>" + (file.file
                    .name) + "&embedded=true";
                iframe = '<iframe src="' + (URL) +
                    '" style="width: 100%; height: 500px;" frameborder="0"></iframe>'
            } else if (file.file.name.match(/(.png|.jpg|.jpeg)$/) !== null) {
                URL = "<?=AWS_S3_BUCKET_URL;?>" + (file.file.name) + "";
                iframe = '<img src="' + (URL) + '" style="width: 100%;">'
            } else {
                URL = "https://docs.google.com/gview?url=<?=AWS_S3_BUCKET_URL;?>" + (file.file.name) +
                    "&embedded=true";
                iframe = '<iframe src="' + (URL) +
                    '" style="width: 100%; height: 500px;" frameborder="0"></iframe>'
            }

            loadModal(file, iframe, URL);
        } else if (file.file.s3_filename != null) {
            // Generate modal content
            var URL = '';
            var iframe = '';
            if (file.file.s3_filename.match(/(.doc|.docx|.ppt|.pptx)$/) !== null) {
                URL = "https://view.officeapps.live.com/op/embed.aspx?src=<?=AWS_S3_BUCKET_URL;?>" + (file.file
                    .s3_filename) + "&embedded=true";
                iframe = '<iframe src="' + (URL) +
                    '" style="width: 100%; height: 500px;" frameborder="0"></iframe>'
            } else if (file.file.s3_filename.match(/(.png|.jpg|.jpeg)$/) !== null) {
                URL = "<?=AWS_S3_BUCKET_URL;?>" + (file.file.s3_filename) + "";
                iframe = '<img src="' + (URL) + '" style="width: 100%;">'
            } else {
                URL = "https://docs.google.com/gview?url=<?=AWS_S3_BUCKET_URL;?>" + (file.file.s3_filename) +
                    "&embedded=true";
                iframe = '<iframe src="' + (URL) +
                    '" style="width: 100%; height: 500px;" frameborder="0"></iframe>'
            }

            loadModal(file, iframe, URL);
        } else {
            // for generated
            $.post(baseURI + 'handler', {
                action: 'get_generated_fmla_view',
                companySid: <?= $companyData['sid']; ?> ,
                fmla : file
            }, function(resp) {
                //
                if (resp.Status === false) {
                    console.log('Failed to load view');
                    return;
                }
                //
                loadModal(file, resp.View);
            });
        }
    }
    //
    function loadModal(file, iframe, URL) {
        //
        var
            modal = '<div class="modal fade" id="js-attachment-view-modal">';
        modal += '    <div class="modal-dialog modal-lg">';
        modal += '            <div class="modal-content">';
        modal += '                <div class="modal-header modal-header-bg">';
        modal +=
            '                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
        modal += '                    <h4 class="modal-title">' + (file.title) + '</h4>';
        modal += '                </div>';
        modal += '                <div class="modal-body">';
        modal += iframe
        modal += '                </div>';
        modal += '                <div class="modal-footer">';
        if (file.type === 'uploaded') {
            modal += '         <a href="<?=base_url('hr_documents_management / download_upload_document ');?>/' + (file.file.name) +'" class="btn btn-success">Download</a>';
            modal += '         <a href="' + (URL) + '" target="_blank" class="btn btn-success">Print</a>';
        } else if (file.file.s3_filename != null) {
            modal += '         <a href="<?=base_url('hr_documents_management / download_upload_document ');?>/' + (file.file.s3_filename) +'" class="btn btn-success">Download</a>';
            modal += '         <a href="' + (URL) + '" target="_blank" class="btn btn-success">Print</a>';
        } else {
            modal += '         <a href="<?=base_url('timeoff / download / document ');?>/' + (file.id) +'" target="_blank" class="btn btn-success">Download</a>';
            modal += '         <a href="<?=base_url('timeoff / print / document ');?>/' + (file.id) +'" target="_blank" class="btn btn-success">Print</a>';
        }
        modal += '                </div>';
        modal += '            </div>';
        modal += '     </div>';
        modal += '</div>';
        //
        emt.modal.modal('hide');
        // Show modal content
        $('#js-attachment-view-modal').remove();
        $('body').append(modal);
        $('#js-attachment-view-modal').modal();
    }
    $(document).on('hidden.bs.modal', '#js-attachment-view-modal', function(e) {
        emt.modal.modal('show');
    });

    function setTimeView2(target, data) {
        var row = '';
        if (employeePolicies[0]['format'] == 'D:H:M') {
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Days </label>';
            row += '        <input type="text" class="form-control js-number" value="' + (data.days !=
                undefined ? data.days : '') + '" id="js-request-days" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" value="' + (data.hours !=
                undefined ? data.hours : '') + '" id="js-request-hours" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" value="' + (data.minutes !=
                undefined ? data.minutes : '') + '" id="js-request-minutes" />';
            row += '    </div>';
            row += '</div>';
        } else if (employeePolicies[0]['format'] == 'D:H') {
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Days </label>';
            row += '        <input type="text" class="form-control js-number" value="' + (data.days !=
                undefined ? data.days : '') + '" id="js-request-days" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" value="' + (data.hours !=
                undefined ? data.hours : '') + '" id="js-request-hours" />';
            row += '    </div>';
            row += '</div>';
        } else if (employeePolicies[0]['format'] == 'H:M') {
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" value="' + (data.hours !=
                undefined ? data.hours : '') + '" id="js-request-hours" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" value="' + (data.minutes !=
                undefined ? data.minutes : '') + '" id="js-request-minutes" />';
            row += '    </div>';
            row += '</div>';
        } else if (employeePolicies[0]['format'] == 'H') {
            row += '<div class="col-sm-12">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" value="' + (data.hours !=
                undefined ? data.hours : '') + '" id="js-request-hours" />';
            row += '    </div>';
            row += '</div>';
        } else {
            row += '<div class="col-sm-12">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" value="' + (data.minutes !=
                undefined ? data.minutes : '') + '" id="js-request-minutes" />';
            row += '    </div>';
            row += '</div>';
        }
        //
        $(target).html(row);


    }
    //
    function getEmployeeRecord(timeoff_request_assignment_sid) {
        var
            i = 0,
            l = currentRequest.Assigned.length;
        //
        for (i; i < l; i++) {
            if (currentRequest.Assigned[i]['sid'] == timeoff_request_assignment_sid) return currentRequest
                .Assigned[i];
        }
        return 'N/A';
    }
    //
    function getPolicyRecord(policyId) {
        var
            i = 0,
            l = employeePolicies.length;
        //
        for (i; i < l; i++) {
            if (employeePolicies[i]['sid'] == policyId) return employeePolicies[i];
        }
        return 'N/A';
    }

    function timePickers() {
        $('#js-start-partial-time').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 1,
            onClose: function(d) {
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
            onClose: function(d) {
                // this.setOptions({
                //     minTime: $('#js-start-partial-time').val() ? $('#js-start-partial-time').val() : false
                // });
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
            onShow: function(d) {
                this.setOptions({
                    minTime: $('#js-compensatory-start-time-edit').val() ? $(
                        '#js-compensatory-start-time-edit').val() : false
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
        onSelect: function(d){
            $('#js-timeoff-end-date-edit').datepicker('option', 'minDate', d);
            $('#js-timeoff-end-date-edit').val(d);

            remakeRangeRowsEdit();
        }
    });

    $('#js-timeoff-end-date-edit').datepicker({
        format: 'mm-dd-yyyy',
        beforeShowDay: unavailable,
        onShow: function(){
            $('#js-timeoff-end-date-edit').datepicker('option', 'minDate', d);
            $('#js-timeoff-end-date-edit').val(d);
        },
        onSelect: remakeRangeRowsEdit
    });

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
    function remakeRangeRowsEdit(data) {
        var startDate = $('#js-timeoff-start-date-edit').val(),
            endDate = $('#js-timeoff-end-date-edit').val();
        //
        var d = {};
        //
        if (typeof(data) === 'object') {
            startDate = data.startDate;
            endDate = data.endDate;
            data.days.map(function(v, i) {
                d[v.date] = v;
            });
        }
        //
        $('#js-timeoff-date-box-edit').hide();
        $('#js-timeoff-date-box-edit tbody tr').remove();
        //
        if (startDate == '' || endDate == '') {
            return;
        }
        //
        startDate = moment(startDate);
        endDate = moment(endDate);
        var diff = endDate.diff(startDate, 'days');
        
        //
        var rows = '';
        var i = 0,
            il = diff;
        for (i; i <= il; i++) {
            var sd = moment(startDate).add(i, 'days');
            var ld = d[sd.format('MM-DD-YYYY')];
            if ($.inArray(sd.format('MM-DD-YYYY'), offdates) === -1 && $.inArray(sd.format('dddd')
                .toLowerCase(), timeOffDays) === -1) {
                rows += '<tr data-id="' + (i) + '" data-date="' + (sd.format('MM-DD-YYYY')) + '">';
                rows += '    <th style="vertical-align: middle">' + (sd.format('MMMM Do, YYYY')) + '</th>';
                rows += '    <th style="vertical-align: middle">';
                rows += '        <div>';
                rows += '            <label class="control control--radio">';
                rows += '                Full Day';
                rows += '                <input type="radio" name="' + (i) +
                    '_day_type_edit" value="fullday" ' + (ld !== undefined && ld.partial == 'fullday' ?
                        'checked="true"' : ld == undefined ? 'checked="true"' : '') + ' />';
                rows += '                <span class="control__indicator"></span>';
                rows += '            </label>';
                rows += '            <label class="control control--radio">';
                rows += '                Partial Day';
                rows += '                <input type="radio" name="' + (i) +
                    '_day_type_edit" value="partialday" ' + (ld !== undefined && ld.partial != 'fullday' ?
                        'checked="true"' : '') + ' />';
                rows += '                <span class="control__indicator"></span>';
                rows += '            </label>';
                rows += '        </div>';
                rows += '    </th>';
                rows += '    <th>';
                rows += '        <div class="rowd" id="row_' + (i) + '_edit">';
                rows += setTimeViewEdit('#row_' + (i) + '', '-el-edit' + i, ld);
                rows += '        </div>';
                rows += '    </th>';
                rows += '</tr>';
                //
            }
        }
        console.log(rows);
        //
        if (rows == '') return;
        //
        $('#js-timeoff-date-box-edit tbody').html(rows);
        $('#js-timeoff-date-box-edit').show();
    }
    //
    function setTimeViewEdit(target, prefix, data) {
        var row = '';
        if (employeePolicies[0]['format'] == 'D:H:M') {
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Days </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-days' + (prefix ===
                undefined ? '' : prefix) + '" value="' + (data !== undefined ? data.breakdown.active.days :
                '') + '" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours' + (
                prefix === undefined ? '' : prefix) + '" value="' + (data !== undefined ? data.breakdown
                .active.hours : '') + '" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-4">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes' + (
                prefix === undefined ? '' : prefix) + '" value="' + (data !== undefined ? data.breakdown
                .active.minutes : '') + '" />';
            row += '    </div>';
            row += '</div>';
        } else if (employeePolicies[0]['format'] == 'D:H') {
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Days </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-days' + (prefix ===
                undefined ? '' : prefix) + '" value="' + (data !== undefined ? data.breakdown.active.days :
                '') + '" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours' + (
                prefix === undefined ? '' : prefix) + '" value="' + (data !== undefined ? data.breakdown
                .active.hours : '') + '" />';
            row += '    </div>';
            row += '</div>';
        } else if (employeePolicies[0]['format'] == 'H:M') {
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours' + (
                prefix === undefined ? '' : prefix) + '" value="' + (data !== undefined ? data.breakdown
                .active.hours : '') + '" />';
            row += '    </div>';
            row += '</div>';
            row += '<div class="col-sm-6">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes' + (
                prefix === undefined ? '' : prefix) + '" value="' + (data !== undefined ? data.breakdown
                .active.minutes : '') + '" />';
            row += '    </div>';
            row += '</div>';
        } else if (employeePolicies[0]['format'] == 'H') {
            row += '<div class="col-sm-12">';
            row += '    <div class="form-group">';
            row += '        <label>Hours </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-hours' + (
                prefix === undefined ? '' : prefix) + '" value="' + (data !== undefined ? data.breakdown
                .active.hours : '') + '" />';
            row += '    </div>';
            row += '</div>';
        } else {
            row += '<div class="col-sm-12">';
            row += '    <div class="form-group">';
            row += '        <label>Minutes </label>';
            row += '        <input type="text" class="form-control js-number" id="js-request-minutes' + (
                prefix === undefined ? '' : prefix) + '" value="' + (data !== undefined ? data.breakdown
                .active.minutes : '') + '" />';
            row += '    </div>';
            row += '</div>';
        }
        //
        if (prefix !== undefined) return row;
        $(target).html(row);
    }
    //
    function getRequestedDaysEdit() {
        //
        var
            totalTime = 0,
            err = false,
            arr = [];
        //
        $('#js-timeoff-date-box-edit tbody tr').map(function(i, v) {
            if (err) return;
            var time = getTimeInMinutes('el-edit' + $(this).data('id'));
            //
            if (time.requestedMinutes <= 0) {
                err = true;
                alertify.alert('WARNING!', 'Please, add request time for date <b>' + ($(this).data(
                    'date')) + '</b>.', function() {
                    return;
                });
            } else if (time.requestedMinutes > time.defaultTimeslotMinutes) {
                err = true;
                alertify.alert('WARNING!', 'Requested time off can not be greater than shift time.',
                    function() {
                        return;
                    });
            }
            //
            arr.push({
                date: $(this).data('date'),
                partial: $(this).find('input[name="' + (i) + '_day_type_edit"]:checked').val(),
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
</script>


<style>
.js-request-titles {
    min-height: 51px !important;
}

.bg-cyan {
    width: 250px !important;
    height: 145px !important;
}

.current-date {
    position: absolute;
    bottom: 0;
}

.js-holiday-list-ul li,
.js-holiday-list-ul li p {
    color: #000000 !important;
}

.js-holiday-list-ul {
    margin-top: 0;
}

.js-holiday-list-ul li p {
    margin-top: 0;
}

.js-policy-list-ul li,
.js-policy-list-ul li p,
.js-policy-list-ul li span {
    color: #000000 !important;
}

.js-policy-list-ul {
    margin-top: 0;
}

.js-policy-list-ul li p {
    margin-top: 0;
}

.paginationjs-pages {
    margin-top: 10px;
    margin-left: 10px;
}
</style>