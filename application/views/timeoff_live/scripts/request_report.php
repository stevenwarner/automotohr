<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
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
            action : 'get_assigned_request_report',
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

      var 
        pa = [['Employee Name', 'Count']],
        aa = [['Employee Name', 'Count']],
        ca = [['Employee Name', 'Count']],
        ra = [['Employee Name', 'Count']],
        ea = {}
        ;

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
            megaOBJ.note = $('#js-edit-modal-note-input').val().trim();
            megaOBJ.action = 'update_employee_timeoff';
            megaOBJ.approver = 1;
            megaOBJ.status = $('#js-edit-modal-status').val();
            megaOBJ.comment = CKEDITOR.instances['js-edit-modal-comment'].getData().trim();
            megaOBJ.policyId = $('#js-edit-modal-policies').val();
            megaOBJ.policyName = $('#js-edit-modal-policies').find('option[value="'+(megaOBJ.policyId)+'"]').text();
            //
            if(megaOBJ.policyId == 0){
                alertify.alert('WARNING!', 'Please select a Policy to continue.');
                return;
            }
            megaOBJ.isPartial = $('.js-edit-modal-partial-check:checked').val() == 'no' ? 0 : 1;
            megaOBJ.companySid = <?=$companyData['sid'];?>;
            megaOBJ.employeeSid = <?=$employerData['sid'];?>;
            megaOBJ.employeeFullName = "<?=$employerData['first_name'].' '.$employerData['last_name'];?>";
            megaOBJ.employeeEmail = "<?=$employerData['email'];?>";
            megaOBJ.requestDate = moment($('#js-edit-modal-date').val(), 'MM-DD-YYYY').format('YYYY-MM-DD');
            // Set hours minutes array
            megaOBJ.requestedTimeDetails = getTimeInMinutes();
            //
            if(megaOBJ.requestedTimeDetails.requestedMinutes <= 0){
                alertify.alert('WARNING!', 'Requested time off can not be less or equal to zero.');
                return;
            }
            //
            megaOBJ.allowedTimeOff = getAllowedTimeInMinutes( getPolicy( megaOBJ.policyId ) );
            megaOBJ.requestId = currentRequest.Info.requestId;
            megaOBJ.request = currentRequest.Info;
            megaOBJ.tls = currentRequest.Assigned;
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
            //
            if(employees.length == 0){
                intVal = setInterval(function(){
                    fetchRequests();
                }, 1000);
                return;
            }
            //
            clearInterval(intVal);
            //
            var emp = [];
            if(employees.length != 0){
                employees.map(function(employee){
                    emp.push(employee.employee_id);
                });
            }
            filterOBJ.employeeList = emp;
            pa = [['Employee Name', 'Count']];
            aa = [['Employee Name', 'Count']];
            ca = [['Employee Name', 'Count']];
            ra = [['Employee Name', 'Count']];
            ea = {};
            //
            if(xhr != null) return;
            //
            loader('show');
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
                //
                drawPendingChart(pa);
                drawApprovedChart(aa);
                drawCancelledChart(ca);
                drawRejectedChart(ra);
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
                rows += '<tr><td colspan="'+( $('thead tr th').length )+'"><p class="alert alert-info text-center">No requests found</p></td></tr>';
            else{
                var i = 0,
                l = requests.Requests.length;
                //
                for(i; i < l; i++){
                    //
                    var it = requests['Requests'][i]['status'].toLowerCase()+requests['Requests'][i]['employee_sid'];
                    if(ea[it] !== undefined){
                        ea[it]['Count']++;
                    }else{
                        ea[it] = {};
                        ea[it]['FullName'] = requests['Requests'][i]['full_name'];
                        ea[it]['Count'] = 1;
                    }
                    //
                    rows += '<tr data-type="'+( requests['Requests'][i]['policy_title'].toLowerCase().replace(/\s+/g, '_') )+'" data-id="'+( requests['Requests'][i]['requestId'] )+'" style="display: none;">';
                    rows += '    <td scope="row">';
                    rows += '        <div class="employee-info">';
                    rows += '            <figure>';
                    rows += '                <img src="'+( getImageURL( requests['Requests'][i]['img'] ) )+'" class="emp-image" />';
                    rows += '            </figure>';
                    rows += '            <div class="text">';
                    rows += '                <h4>'+( requests['Requests'][i]['full_name'] )+'</h4>';
                    rows += '                <p><a href="<?=base_url('employee_profile');?>/'+( requests['Requests'][i]['employee_sid'] )+'" target="_blank">Id: '+( getEmployeeId(requests['Requests'][i]['employee_sid'], requests['Requests'][i]['employee_number']) )+'</a></p>';
                    rows += '            </div>';
                    rows += '        </div>';
                    rows += '    </td>';
                    rows += '   <td>';
                    rows += '       <div class="upcoming-time-info">';
                    rows += '            <div class="icon-image">';
                    rows += '                   <img src="<?=base_url('assets');?>/images/upcoming-time-off-icon.png" class="emp-image" alt="emp-1">';
                    rows += '             </div>';
                    rows += '             <div class="text">';
                    rows += '                  <h4>'+( moment(requests['Requests'][i]['requested_date'], 'YYYY-MM-DD').format(timeoffDateFormat) )+'</h4>';
                    rows += '                  <p>'+( requests['Requests'][i]['requested_time'] == 0 ? 'Unlimited' : requests['Requests'][i]['timeoff_breakdown']['text'] )+' of <span>'+( requests['Requests'][i]['policy_title'] )+'</span></p>';
                    rows += '             </div>';
                    rows += '       </div>';
                    rows += '   </td>';
                    rows += '    <td>';
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
                    rows +=             getReasonHTML(requests['Requests'][i]['reason']);
                    rows += '      </div>';
                    rows += '    </td>';
                    rows += '   <td>';
                    rows += '      <div class="denied-button">';
                    rows +=             getStatusHTML( requests['Requests'][i]['status'] );
                    rows += '      </div>';
                    rows += '    </td>';
                    rows += '    <td align="center">';
                    rows += '       <div class="notes-employee">';
                    rows += '           <p class="js-edit-request icon_blue"><i class="fa fa-edit"></i></p>';
                    rows += '       </div>';
                    rows += '    </td>';
                    rows += '</tr>';
                }

                if(ea.length !== 0){
                    $.each(ea, function(i, e){
                        if(i.match(/approved/ig)) aa.push([e.FullName, e.Count]);
                        else if(i.match(/pending/ig)) pa.push([e.FullName, e.Count]);
                        else if(i.match(/cancelled/ig)) ca.push([e.FullName, e.Count]);
                        else if(i.match(/rejected/ig)) ra.push([e.FullName, e.Count]);
                    });
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
            loader('show');
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
            // Set header
            emt.header.text('Time-off, '+( resp.Data.Info.full_name )+'');
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
            // Set Partial box
            if(resp.Data.Info.is_partial_leave == "1"){
                $(`${emt.partialLeaveCheck}[value="yes"]`).prop('checked', true);
                emt.noteBox.show('');
                emt.note.val(resp.Data.Info.partial_leave_note);
            }
            // Set requester date
            emt.requestDate.datepicker({
                format: 'mm-dd-yy',
                minDate: 1
            }).val(moment(resp.Data.Info.requested_date, 'YYYY-MM-DD').format('MM-DD-YYYY'));
            emt.reason.html(resp.Data.Info.reason);
            // Set progress bar
            emt.progress.find('.progress-bar').css('width', `${resp.Data.Info.Progress.CompletedPercentage}%`);
            emt.progress.find('.progress-bar').attr('aria-valuemin', '0');
            emt.progress.find('.progress-bar').attr('aria-valuemax', '100');
            emt.progress.find('.progress-bar').attr('aria-valuenow', `${resp.Data.Info.Progress.CompletedPercentage}%`);
            emt.progress.find('.progress-percent span').text(resp.Data.Info.Progress.CompletedPercentage);
            //
            emt.status.find('option[value="pending"]').remove();
            if(resp.Data.Info.level_status == 'pending' && resp.Data.Info.status == 'pending'){
            //     emt.status.append('<option value="pending">Pending</option>');
                resp.Data.Info.level_status = 'approved';
                resp.Data.Info.status = 'approved';
            }
            //
            emt.status.select2();
            emt.status.select2('val', resp.Data.Info.level_status);
            fetchEmployeeAllPolicies(resp.Data.Info.employee_sid, function(res){
                // Set Policies
                var rows = '';
                res.map(function(policy){
                    rows += `<option value="${policy.sid}">${policy.title.ucwords()} (${policy.timeoff_breakdown != undefined ? policy.timeoff_breakdown.text : 'Unlimited'})</option>`;
                });
                emt.policies.html(rows).select2();
                emt.policies.select2('val', resp.Data.Info.policyId);
                $('#js-eme-img').prop('src', getImageURL(resp.Data.Info.img));
                $('#js-eme-name').text(resp.Data.Info.full_name);
                $('#js-eme-id').text(getEmployeeId( resp.Data.Info.employee_sid,  resp.Data.Info.employee_number ));
                $('#js-eme-id').prop('href', "<?=base_url('employee_profile');?>/"+( resp.Data.Info.employee_sid )+"");
                //
                setTimeView(
                    emt.requestTime, 
                    resp.Data.Info.timeoff_breakdown.active
                );
                //
                if($('#js-request-days').length != 0) $('#js-request-days').val(resp.Data.Info.timeoff_breakdown.active.days);
                if($('#js-request-hours').length != 0) $('#js-request-hours').val(resp.Data.Info.timeoff_breakdown.active.hours);
                if($('#js-request-minutes').length != 0) $('#js-request-minutes').val(resp.Data.Info.timeoff_breakdown.active.minutes);
                //
                if(resp.Data.Info.status != 'pending'){
                    $('#js-edit-modal-save-btn').hide(0);
                }else{
                    $('#js-edit-modal-save-btn').show(0);
                }
                // Show modal
                emt.modal.modal();   
                loader('hide');             
            });
        }
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

            // if(level != 'all'){
            //     rows += '<option value="all">All</option>';
            //     rows += '<option value="teamlead">Team Lead</option>';
            //     rows += '<option value="supervisor">Supervisor</option>';
            //     rows += '<option value="approver">Approver</option>';

            //     $('#js-emh-tls-select').html(rows);
            //     $('#js-emh-tls-select').select2();
            // }

            loadHistoryData(data);

            // currentRequest.History.map(function(h){
            //     var 
            //     employeeData = getEmployeeRecord(h.timeoff_request_assignment_sid),
            //     policyData = getPolicyRecord(h.timeoff_request_assignment_sid);
            //     rows += '<div class="pto-foot-print-listing full-width">';
            //     rows += '    <ul class="user-detail-headers full-width">';
            //     rows += '        <li>';
            //     rows += '            <h5>Employee</h5>';
            //     rows += '            <span>'+( employeeData['full_name'] )+'</span>';
            //     rows += '        </li>';
            //     rows += '        <li>';
            //     rows += '            <h5>Requested Date</h5>';
            //     rows += '            <span>'+( h.requested_date != '0000-00-00' && h.requested_date != '' && h.requested_date != null ? moment(h.requested_date, 'YYYY-MM-DD').format('MM-DD-YYYY') : 'N/A' )+'</span>';
            //     rows += '        </li>';
            //     rows += '        <li>';
            //     rows += '            <h5>Hours</h5>';
            //     rows += '            <span>'+( h.timeoff_breakdown.text )+'</span>';
            //     rows += '        </li>';
            //     rows += '        <li>';
            //     rows += '            <h5>Requested Status</h5>';
            //     rows += '            <span>'+( h.status.ucwords() )+'</span>';
            //     rows += '        </li>';
            //     rows += '    </ul>';
            //     rows += '    <div class="foot-print-detail-box">';
            //     rows += '        <p>';
            //     rows += '            <label>Policy Name:</label>'+( policyData['title'] )+'';
            //     rows += '        </p>';
            //     rows += '        <p>';
            //     rows += '            <label>Reason:</label>'+( h.reason )+'';
            //     rows += '        </p>';
            //     rows += '    </div>';
            //     rows += '</div>';
            // });

            // $('#js-history-data-append').html(rows);
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
                    hs.map(function(h){
                        var 
                        employeeData = getEmployeeRecord(h.timeoff_request_assignment_sid),
                        policyData = getPolicyRecord(h.timeoff_request_assignment_sid);
                        rows += '<div class="row cs-timeoff-history-row">';
                        rows += '   <div class="col-sm-3 cs-row">';
                        rows += '       <h5><b>Employee</b></h5>';
                        rows += '       <span>'+( employeeData['full_name'] )+' ('+(overwrite[employeeData['role']])+')</span>';
                        rows += '   </div>';
                        rows += '   <div class="col-sm-3 cs-row">';
                        rows += '       <h5><b>Requested Date</b></h5>';
                        rows += '       <span>'+( h.requested_date != '0000-00-00' && h.requested_date != '' && h.requested_date != null ? moment(h.requested_date, 'YYYY-MM-DD').format('MM-DD-YYYY') : 'N/A' )+'</span>';
                        rows += '   </div>';
                        rows += '   <div class="col-sm-3 cs-row">';
                        rows += '       <h5><b>Hours</b></h5>';
                        rows += '       <span>'+( h.timeoff_breakdown.text )+'</span>';
                        rows += '   </div>';
                        rows += '   <div class="col-sm-3 cs-row">';
                        rows += '       <h5><b>Requested Status</b></h5>';
                        rows += '       <span>'+( h.status.ucwords() )+'</span>';
                        rows += '   </div>';
                        rows += '   <div class="col-sm-12 cs-row">';
                        rows += '       <hr />';
                        rows += '       <p>';
                        rows += '           <strong>Policy Name:</strong> '+( policyData == 'N/A' ? 'N/A' : policyData['title'] )+'';
                        rows += '       </p>';
                        rows += '       <p>';
                        rows += '           <strong>Reason:</strong> '+( h.reason )+'';
                        rows += '       </p>';
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
                row += '        <label>Days <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-days" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Hours <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-4">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(employeePolicies[0]['format'] == 'D:H'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Days <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-days" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
            }
            else if(employeePolicies[0]['format'] == 'H:M'){
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Hours <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
                row += '<div class="col-sm-6">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-minutes" />';
                row += '    </div>';
                row += '</div>';
            }else if(employeePolicies[0]['format'] == 'H'){
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Hours <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-hours" />';
                row += '    </div>';
                row += '</div>';
            }else{
                row += '<div class="col-sm-12">';
                row += '    <div class="form-group">';
                row += '        <label>Minutes <span class="cs-required">*</span></label>';
                row += '        <input type="text" class="form-control js-number" value="" id="js-request-minutes" />';
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
        function getTimeInMinutes(){
            var
            days = 0,
            hours = 0,
            minutes = 0,
            format = '',
            inText = '';
            //
            if($('#js-request-days').length !== 0) { format +='D,'; days = parseInt($('#js-request-days').val().trim()); }
            if($('#js-request-hours').length !== 0) { format += 'H,'; hours = parseInt($('#js-request-hours').val().trim()); }
            if($('#js-request-minutes').length !== 0) { format += 'M,'; minutes = parseInt($('#js-request-minutes').val().trim()); }
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
            emt.header.text('');
            emt.tabs.text('');
            emt.noteBox.hide();
            emt.note.val('');
            emt.progress.find('.progress-bar').attr('aria-valuemax', '0');
            emt.progress.find('.progress-bar').attr('aria-valuemin', '0');
            emt.progress.find('.progress-bar').attr('aria-valuenow', '0%');
            emt.progress.find('.progress-percent span').text('0');
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
                        console.log('Failed to load employees.');
                        return;
                    }
                    employees = resp.Data;
                    var rows = '<option value="all">All</option>';
                    employees.map(function(v){
                        rows += '<option value="'+( v.employee_id )+'">'+( v.full_name )+'</option>';
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
                    console.log('Failed to load policies.');
                    return;
                }
                //
                employeePolicies = resp.Data;
                cb(resp.Data);
            });
        }




        
        google.load("visualization", "1", {packages: ["corechart"]});
        google.setOnLoadCallback(drawPendingChart);
        google.setOnLoadCallback(drawApprovedChart);
        google.setOnLoadCallback(drawCancelledChart);
        google.setOnLoadCallback(drawRejectedChart);

        function drawPendingChart(data) {
            if(data === undefined) return;
            var data = google.visualization.arrayToDataTable(data);
            var options = {
                title: 'Pending Time-off',
                is3D: true,
                legend: {position: 'top', maxLines: 3}
            };
            var chart = new google.visualization.PieChart(document.getElementById('js-pending-chart'));
            chart.draw(data, options);
        }


        function drawApprovedChart(data) {
            if(data === undefined) return;
            var data = google.visualization.arrayToDataTable(data);
            var options = {
                title: 'Approved Time-off',
                is3D: true,
                legend: {position: 'top', maxLines: 3}
            };
            var chart = new google.visualization.PieChart(document.getElementById('js-approved-chart'));
            chart.draw(data, options);
        }


        function drawCancelledChart(data) {
            if(data === undefined) return;
            var data = google.visualization.arrayToDataTable(data);
            var options = {
                title: 'Cancelled Time-off',
                is3D: true,
                legend: {position: 'top', maxLines: 3}
            };
            var chart = new google.visualization.PieChart(document.getElementById('js-cancelled-chart'));
            chart.draw(data, options);
        }

        function drawRejectedChart(data) {
            if(data === undefined) return;
            var data = google.visualization.arrayToDataTable(data);
            var options = {
                title: 'Rejected Time-off',
                is3D: true,
                legend: {position: 'top', maxLines: 3}
            };
            var chart = new google.visualization.PieChart(document.getElementById('js-rejected-chart'));
            chart.draw(data, options);
        }

        //
        <?php $this->load->view('timeoff/scripts/common'); ?>
    })

</script>
