<script type="text/javascript" src="<?= base_url("assets/js/owl.carousel.min.js"); ?>"></script>

<script>
    var intervalHolder = null;
    $(function(){
        var
        requests = {},
        FMLACategories = {
            health: 'Certification of Health Care Provider for Employee’s Serious Health Condition',
            medical: 'Notice of Eligibility and Rights & Responsibilities',
            designation: 'Designation Notice'
        },
        currentRequest = {},
        employeePolicies = [],
        overwrite = {
            'teamlead' : 'Team Lead',
            'supervisor' : 'Supervisor',
            'approver' : 'Approver'
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
        filterActive = false,
        dataTarget = $('.js-data-load-area'),
        fetchRequestOBJ = {
            action: 'fetch_employee_requests',
            fromDate: 'all',
            toDate: 'all',
            status: 'all',
            employeeSid: <?=$employerData['sid'];?>,
            companySid: <?=$companyData['sid'];?>
        };

        // Filter
        $('#js-filter-from-date').datepicker({
            format: 'mm/dd/yyyy',
            onSelect: function(dt){
               $('#js-filter-to-date').datepicker("option", "minDate", dt);
            }
        });
        $('#js-filter-to-date').datepicker({
            format: 'mm/dd/yyyy',
            onSelect: function(dt){
               $('#js-filter-from-date').datepicker("option", "maxDate", dt);
            }
        });
        //
        $('#js-filter-status').select2();
        //
        $('#js-filter-apply-btn').click(function(e){
            e.preventDefault();
            if($('#js-filter-from-date').val() != '') fetchRequestOBJ.fromDate = moment($('#js-filter-from-date').val(), 'MM/DD/YYYY').format('YYYY-MM-DD');
            if($('#js-filter-to-date').val() != '') fetchRequestOBJ.toDate = moment($('#js-filter-to-date').val(), 'MM/DD/YYYY').format('YYYY-MM-DD');
            if($('#js-filter-status').val() != null) fetchRequestOBJ.status = $('#js-filter-status').val();
            //
            fetchRequests();
        });
        $('#js-filter-reset-btn').click(function(e){
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
        setHolidayList();
        //
        fetchMatricsList();
        //
        fetchRequests();

        function setHolidayList(){
            $.post(baseURI+'handler', {
                action: 'get_company_all_holidays',
                companySid: <?=$company_sid;?>
            }, function(resp){
                if(resp.Status === false){
                    console.log(resp.Response);
                    return;
                }
                if(resp.Data.length === 0) return;
                //
                var target = $('.js-holiday-list-ul'),
                holidays = resp.Data,
                rows = '',
                trs = '';
                //
                holidays.map(function(holiday){
                    rows += '<li><p><strong>'+( getHolidayText(holiday) )+'</strong><br />'+( holiday.holiday_title )+'<p></li>';
                    trs += '<tr><td><strong>'+( holiday.holiday_title )+'</strong></td><td>'+( getHolidayText(holiday) )+'</td></tr>';
                });
                target.css({'height': '200px', 'overflow-y': 'hidden'});

                target.html(rows);
                rows = '<hr /><p class="text-center text-info js-expand-holiday" style="cursor: pointer; color: #0000ff;">See More</p>';
                target.parent().append(rows);
                $('#js-timeoff-holiday-list-modal tbody').html(trs);
            });
        }

        $('#js-company-holidays-btn').click(function(){
            $('#js-timeoff-holiday-list-modal').modal();
        });
        //
        $(document).on('click', '.js-expand-holiday', function(){
            $(this).removeClass('js-expand-holiday').addClass('js-less-holiday').text('See Less');
            $('.js-holiday-list-ul').css({'height': 'auto', 'overflow-y': 'auto'});
        });
        $(document).on('click', '.js-less-holiday', function(){
            $(this).removeClass('js-less-holiday').addClass('js-expand-holiday').text('See More');
            $('.js-holiday-list-ul').css({'height': '200px', 'overflow-y': 'hidden'});
        });
        //
        $(document).on('click', '.js-request-title-li', function(e){
            //
            e.preventDefault();
            //
            $('.js-request-title-li').attr('style', '');
            $(this).attr('style', 'color: #3554dc !important;');
            $('.js-request-title-li').parent().removeClass('active');
            $(this).parent().addClass('active');
            //
            $('#js-data-load-area tr').hide();
            $('#js-data-load-area tr[data-type="'+( $(this).data('key') )+'"]').show();
        });
        //
        $(document).on('click', '.js-cancel-request', function(e){
            //
            e.preventDefault();
            //
            var megaOBJ = {};
            megaOBJ.action = 'cancel_employee_request';
            megaOBJ.requestId = $(this).closest('tr').data('id');
            megaOBJ.employeeSid = <?=$employerData['sid']?>;
            megaOBJ.companySid = <?=$companyData['sid']?>;
            //
            alertify.confirm('Do you really want to cancel this Time Off request?', function(){
                cancelEmployeeRequest(megaOBJ);
            }).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        });
        //
        $('#js-apply-filter-btn').click(function(e){
            e.preventDefault();
            $('#js-filter').toggle();
        });
        //
        function cancelEmployeeRequest(obj){
            $.post(handlerURI, obj, function(resp) {
                alertify.alert(resp.Response, function(){
                    window.location.reload();
                });
            });
        }
        //
        function fetchRequests(){
            loader('show', 'Please wait, while we are fetching your requests.');
            $.post(handlerURI, fetchRequestOBJ, function(resp){
                //
                if(resp.Status === false){
                    loader('hide');
                    $('#js-data-load-area').html('<tr><td colspan="'+( $('.js-data-head tr th').length )+'"><p class="alert alert-info text-center">No Time Off Requests Found!</p></td></tr>');
                    return;
                }
                //
                setEmployeeRequestTable(
                    resp.Data
                );
            });
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
        function setEmployeeRequestTable(requests){
            //
            $('#js-data-load-area').html('');
            $('.js-request-title-li').remove();
            var 
            rows = '',
            trows = '';
            if(requests.length == 0) rows += '<tr><td colspan="'+( $('thead tr th').length )+'"><p class="alert alert-info text-center">No requests found</p></td></tr>';
            else{
                var i = 0,
                l = requests.Requests.length;
                //
                for(i; i < l; i++){
                    rows += '<tr data-type="'+( requests['Requests'][i]['Category']['category_name'].toLowerCase().replace(/\s+/g, '_') )+'" data-id="'+( requests['Requests'][i]['requestId'] )+'" style="display: none;">';
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
                    rows += '   <td style="vertical-align: middle;">';
                    rows += '      <div>';
                    rows += '           <p class="cs-status text-center '+( requests['Requests'][i]['status'].toLowerCase() == 'approved' ? 'text-success' : 'text-danger' )+'"><span class="'+( requests['Requests'][i]['status'].toLowerCase() )+'-stat">'+(  requests['Requests'][i]['is_draft'] == 1 ? 'Draft' : requests['Requests'][i]['status'].toUpperCase() )+'</span></p>';
                    rows += '      </div>';
                    rows += '    </td>';
                    rows += '    <td>';
                    rows += '        <div class="progress-bar-custom">';
                    if( requests['Requests'][i]['is_draft'] == 1 ){
                        rows += '            <div class="progress-bar-tooltip">';
                        rows += '                <div class="progress">';
                        rows += '                    <div class="progress-bar progress-bar-success progress-bar-success-blue" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0">';
                        rows += '                        <div class="sr-only"></div>';
                        rows += '                    </div>';
                        rows += '                </div>';
                        rows += '            </div>';
                        rows += '            <div class="progress-percent progress-percent-custom">0% Completed</div>';
                    }else{
                        rows += '            <div class="progress-bar-tooltip">';
                        rows += '                <div class="progress">';
                        rows += '                    <div class="progress-bar progress-bar-success progress-bar-success-blue" role="progressbar" aria-valuenow="'+( requests.Requests[i]['Progress']['CompletedPercentage'] )+'" aria-valuemin="0" aria-valuemax="'+( requests.Requests[i]['Progress']['Total'] )+'" style="width: '+( requests.Requests[i]['Progress']['CompletedPercentage'] )+'%">';
                        rows += '                        <div class="sr-only"></div>';
                        rows += '                    </div>';
                        rows += '                </div>';
                        rows += '            </div>';
                        rows += '            <div class="progress-percent progress-percent-custom">'+( requests.Requests[i]['Progress']['CompletedPercentage'] )+'% Completed</div>';
                    }
                    rows += '        </div>';
                    rows += '    </td>';
                    if( requests['Requests'][i]['is_draft'] == 1 ){
                        rows += '    <td colppan="2">';
                        rows += '        <a href="javascript:void()" class="icon_blue js-edit-request">';
                        rows += '            <div class="text-action text-success">';
                        rows += '                <p class="text-center icon_blue" title="Edit Time Off"><i class="fa fa-edit"></i></p>';
                        rows += '            </div>';
                        rows += '        </a>';
                        rows += '    </td>';
                    }else{
                        if( requests['Requests'][i]['status'] == 'pending' ){
                            rows += '    <td>';
                            rows += '        <a href="javascript:void()" class="icon_blue js-edit-request">';
                            rows += '            <div class="text-action text-success">';
                            rows += '                <p class="text-center icon_blue" title="Edit Time Off"><i class="fa fa-edit"></i></p>';
                            rows += '            </div>';
                            rows += '        </a>';
                            rows += '    </td>';
                        }else{
                            rows += '    <td>';
                            rows += '        <a href="javascript:void()" class="icon_blue js-edit-request">';
                            rows += '            <div class="text-action text-success">';
                            rows += '                <p class="text-center icon_blue" title="View Time Off">View</p>';
                            rows += '            </div>';
                            rows += '        </a>';
                            rows += '    </td>';
                        }
                        rows += '    <td>';
                        rows += '        <a href="javascript:void()" class="icon_blue '+( requests.Requests[i]['status'] != 'cancelled' && requests.Requests[i]['expired'] != true ? 'js-cancel-request' : '' )+'" '+( requests.Requests[i]['status'] != 'cancelled' && requests.Requests[i]['expired'] != true ? '' : 'style="cursor: text;"' )+'>';
                        rows += '            <div class="text-action text-danger">';
                        rows += '                <p class="text-center">'+( requests.Requests[i]['status'] != 'cancelled' && requests.Requests[i]['expired'] != true ? 'Cancel' : '-' )+'</p>';
                        rows += '            </div>';
                        rows += '        </a>';
                        rows += '    </td>';
                    }
                    rows += '</tr>';
                }

                // set title
                i = 0;
                l = requests.Titles.length;

                for(i; i < l; i++){
                    trows += '<li class="'+( i == 0 ? 'active active-tab' : '' )+'"><a href="javascript:void(0)" '+( i == 0 ? 'style="color: #3554dc !important;"' : '' )+'  data-key="'+( requests.Titles[i].toLowerCase().replace(/\s+/g, '_') )+'" class="js-request-title-li">'+( requests.Titles[i] )+'</a></li>';
                }
            }
            //
            $('#js-data-load-area').html(rows);
            $('.js-request-titles').prepend(trows);
            $('#js-data-load-area tr[data-type="'+( requests.Titles[0].toLowerCase().replace(/\s+/g, '_') )+'"]').show();

            loader('hide');
        }
        //
        function setPoliciesList(){
            if(policies.length == 0) {
                intervalHolder = setInterval(function () {
                    setPoliciesList();
                }, 1000);
                return;
            }
            //
            clearInterval(intervalHolder);
            //
            setPolicySlider();
            //
            var target = $('.js-policy-list-ul'),
            rows = '';
            //
            policies.map(function(policy){
                rows += '<li>'+( policy.title )+'<span>'+( policy.timeoff_breakdown !== undefined ?  policy.timeoff_breakdown.text : 'Unlimited' )+'</span></li>';
            });

            target.html(rows);
        }
        //
        function setPolicySlider(){
            //
            var target = $('.js-policy-slider'),
            rows = '';
            //
            policies.map(function(policy){
                rows += '<div class="item">';
                rows += '    <div class="widget-box">';
                rows += '       <a href="#">';
                rows += '           <div class="link-box bg-cyan full-width" style="background-color: #3554dc !important;" >';
                rows += '                <h2>'+( policy.title )+'</h2>';
                rows += '                <div class="current-date" style="">';
                rows += '                    <span style="font-size: 20px;">'+( policy.timeoff_breakdown !== undefined ?  policy.timeoff_breakdown.text : 'Unlimited' )+'</span>';
                rows += '                </div>';
                rows += '            </div>';
                rows += '       </a>';
                rows += '    </div>';
                rows += '</div>';
            });

            target.html(rows);
            setTimeout(function(){
                // attachOwl(target);
            }, 2000);
        }
        //
        function attachOwl(target){
            $(target).owlCarousel({
                loop:false,
                margin:17,
                nav:true,
                autoWidth:true,
                navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:3
                    },
                    1000:{
                        items:5
                    }
                }
            });
            $('.owl-nav.disabled').show(0);
        }
        //
        function setTimeView2(target, data){
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

        // View mode
        $(document).on('click', '.js-edit-request', loadEditPage); 
        //
        function loadEditPage(e){
            e.preventDefault();
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
            var m = $('#js-timeoff-edit-modal');
            //
            if(currentRequest.Info.is_draft == '1'){
                showDraft();
                // $('.js-fmla-popup').prop('id', 'js-fmla-modal-'+( attachmentMode )+'');
                $('.js-fmla-popup').prop('id', 'js-fmla-modal-draft');
                return;
            }

            if(currentRequest.Info.status != 'pending'){
                showView();
                $('.js-fmla-popup').prop('id', 'js-fmla-modal-view');
                return;
            }
            resetEditModal();
            attachmentMode = 'edit';
            $('.js-fmla-popup').prop('id', 'js-fmla-modal-'+( attachmentMode )+'');
            // Set predefined values
            $('#js-types-edit').select2('val', currentRequest.Info.Category);
            $('#js-policies-edit').select2('val', currentRequest.Info.policyId);
            // $('#js-timeoff-date-edit').val(moment(currentRequest.Info.requested_date, 'YYYY-MM-DD').format('MM-DD-YYYY'));
            //
            if(currentRequest.Info.timeoff_breakdown.active.hours !== undefined)
            $('#js-request-hours-edit').val(currentRequest.Info.timeoff_breakdown.active.hours);
            if(currentRequest.Info.timeoff_breakdown.active.minutes !== undefined)
            $('#js-request-minutes-edit').val(currentRequest.Info.timeoff_breakdown.active.minutes);
            if(currentRequest.Info.timeoff_breakdown.active.days !== undefined)
            $('#js-request-days-edit').val(currentRequest.Info.timeoff_breakdown.active.days);
            // FMLA
             // Set Partial box
            if(currentRequest.Info.fmla != null && currentRequest.Info.fmla != '0' && currentRequest.Info.fmla != 'no'){
                $('.js-fmla-box-edit').show();
                $('.js-fmla-type-check-edit[value="'+(currentRequest.Info.fmla)+'"]').prop('checked', true);
                $('.js-fmla-check-edit[value="yes"]').prop('checked', true);
            }else{
                $('.js-fmla-check-edit[value="no"]').trigger('click');
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
                    $('#js-compensatory-return-date-edit').val(
                        currentRequest.Info.return_date == null || currentRequest.Info.return_date == '0000-00-00' ? '' : moment(currentRequest.Info.return_date, 'YYYY-MM-DD').format('MM/DD/YYYY')
                    );
                    $('#js-compensatory-start-time-edit').val(currentRequest.Info.compensation_start_time);
                    $('#js-compensatory-end-time-edit').val(currentRequest.Info.compensation_end_time);
                    $('.js-compensatory-row-edit').show();
                break;
            }
            // Multiple days off
            var ld = {};
            ld.startDate = moment(currentRequest.Info.request_from_date, 'YYYY-MM-DD').format('MM-DD-YYYY');
            ld.endDate = moment(currentRequest.Info.request_to_date, 'YYYY-MM-DD').format('MM-DD-YYYY');
            ld.days = currentRequest.Info.timeoff_days;
            //
            $('#js-timeoff-start-date-edit').val(ld.startDate.replace(/-/g,'/'));
            $('#js-timeoff-end-date-edit').val(ld.endDate.replace(/-/g,'/'));
            remakeRangeRowsEdit(ld);
            // Phase 3 ends
            //
            $('#js-update-edit-sid').val(currentRequest.Info.requestId);
            //
            CKEDITOR.instances['js-reason-edit'].setData(currentRequest.Info.reason);
            // Set attachments
            attachedDocuments = {};
            if(currentRequest.Attachments.length !== 0){
                var i = 0, il = currentRequest.Attachments.length;
                for(i; i < il; i++){
                    var atc = currentRequest.Attachments[i];

                    let sd = atc.serialized_data == 'null' || atc.serialized_data == null ? {} : JSON.parse(atc.serialized_data);
                    atc.showEmployerSection = sd.hasOwnProperty('type') && atc.document_title.toLowerCase() == 'health' ? true : false;
                    //
                    attachedDocuments[atc.sid] = {
                        id: atc.sid,
                        created_at: moment(atc.created_at).format('MM-DD-YYYY'),
                        type: atc.document_type,
                        serialized_data: atc.serialized_data,
                        file: {
                            name: sd.hasOwnProperty('type') ? sd.type : atc.s3_filename,
                            s3_filename: atc.s3_filename
                        },
                        showEmployerSection: atc.showEmployerSection,
                        slug: atc.document_title.replace(/[^0-9a-zA-Z.]/g, '_').toLowerCase(),
                        title: atc.document_type == 'generated' ? FMLACategories[atc.document_title.toLowerCase()] : atc.document_title
                    };
                }

                setAttachmentTable();
            }
            //
            $('#js-timeoff-edit-modal').modal();
            loader('hide');
            CKEDITOR.instances['js-reason-edit'].destroy(); 
            CKEDITOR.replace('js-reason-edit');
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
                        rows += '<div class="rows cs-timeoff-history-row">';
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
        function resetViewModal(){
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
        // Set attachment views
        function setAttachments(){
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
            rows = '<tr class="js-no-records"><td colspan="4"><p class="alert alert-info text-center">No attachments found.</p></td></tr>';
            if(attachments.length !== 0){
                rows = '';
                attachments.map(function(attachment){
                    let sd = attachment.serialized_data == null ? {} : JSON.parse(attachment.serialized_data);

                    attachment.showEmployerSection = sd.hasOwnProperty('type') && attachment.document_title.toLowerCase() == 'health' ? true : false;

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
                        title: attachment.document_type == 'generated' ? FMLACategories[attachment.document_title.toLowerCase()] : attachment.document_title
                    };
                    //
                    rows += '<tr class="js-attachments" data-id="'+( attachment.sid )+'">';
                    rows += '   <td>'+( attachment.document_type == 'generated' ? FMLACategories[attachment.document_title.toLowerCase()] : attachment.document_title )+'</td>';
                    rows += '   <td>'+( attachment.document_type.ucwords() )+'</td>';
                    rows += '   <td>'+( moment(attachment.created_at).format('MM-DD-YYYY') )+'</td>';
                    rows += '   <td>';
                    if(attachment.document_type == 'uploaded'){
                        rows += '       <button class="btn btn-info btn-5 js-attachment-view">View</button>';
                    }else if(attachment.s3_filename != null){
                        rows += '       <button class="btn btn-info btn-5 js-attachment-view">View</button>';
                    }else{
                        rows += '       <button class="btn btn-info btn-5 js-attachment-view">View</button>';
                    }
                    rows += '   </td>';
                    rows += '</tr>';
                });
            }
            $('#js-attachment-tbody').html(rows);
        }
         // Page Shifter
        $(document).on('click', '.js-tab-btn', function(e){
            //
            $('.js-tab-btn').removeClass('active');
            $(this).addClass('active');

            $('.js-em-page').fadeOut();
            $(`#${$(this).find('a').data('target')}`).fadeIn();
        });
        $(document).on('click', '.js-attachment-view', viewDocument);
        function viewDocument(){
            var file = attachedDocuments[$(this).closest('tr').data('id')];
            if(file.type === 'uploaded' ){
                // Generate modal content
                var URL = '';
                var iframe = '';
                if(file.file.name.match(/(.doc|.docx|.ppt|.pptx)$/) !== null){
                    URL = "https://view.officeapps.live.com/op/embed.aspx?src=<?=AWS_S3_BUCKET_URL;?>"+( file.file.name )+"&embedded=true";
                    iframe = '<iframe src="'+( URL )+'" style="width: 100%; height: 500px;" frameborder="0"></iframe>'
                }else if(file.file.name.match(/(.png|.jpg|.jpeg)$/) !== null){
                    URL = "<?=AWS_S3_BUCKET_URL;?>"+( file.file.name )+"";
                    iframe = '<img src="'+( URL )+'" style="width: 100%;">'
                }else{
                    URL = "https://docs.google.com/gview?url=<?=AWS_S3_BUCKET_URL;?>"+( file.file.name )+"&embedded=true";
                    iframe = '<iframe src="'+( URL )+'" style="width: 100%; height: 500px;" frameborder="0"></iframe>'
                }

                loadModal(file, iframe, URL);
            }else if(file.file.s3_filename != null ){
                // Generate modal content
                var URL = '';
                var iframe = '';
                if(file.file.s3_filename.match(/(.doc|.docx|.ppt|.pptx)$/) !== null){
                    URL = "https://view.officeapps.live.com/op/embed.aspx?src=<?=AWS_S3_BUCKET_URL;?>"+( file.file.s3_filename )+"&embedded=true";
                    iframe = '<iframe src="'+( URL )+'" style="width: 100%; height: 500px;" frameborder="0"></iframe>'
                }else if(file.file.s3_filename.match(/(.png|.jpg|.jpeg)$/) !== null){
                    URL = "<?=AWS_S3_BUCKET_URL;?>"+( file.file.s3_filename )+"";
                    iframe = '<img src="'+( URL )+'" style="width: 100%;">'
                }else{
                    URL = "https://docs.google.com/gview?url=<?=AWS_S3_BUCKET_URL;?>"+( file.file.s3_filename )+"&embedded=true";
                    iframe = '<iframe src="'+( URL )+'" style="width: 100%; height: 500px;" frameborder="0"></iframe>'
                }

                loadModal(file, iframe, URL);
            }else{
                // for generated
                $.post(baseURI+'handler', {
                    action: 'get_generated_fmla_view',
                    companySid: <?=$companyData['sid'];?>,
                    fmla: file
                }, function(resp) {
                    //
                    if(resp.Status === false){
                        console.log('Failed to load view');
                        return;
                    }
                    //
                    loadModal(file, resp.View);
                });
            }
        }
        //
        function loadModal(file, iframe, URL){
            //
            var 
            modal = '<div class="modal fade" id="js-attachment-view-modal">';
            modal +='    <div class="modal-dialog modal-lg">';
            modal +='            <div class="modal-content">';
            modal +='                <div class="modal-header modal-header-bg">';
            modal +='                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            modal +='                    <h4 class="modal-title">'+( file.title )+'</h4>';
            modal +='                </div>';
            modal +='                <div class="modal-body">';
            modal +=  iframe
            modal +='                </div>';
            modal +='                <div class="modal-footer">';
            if(file.type === 'uploaded'){
                modal +='         <a href="<?=base_url('hr_documents_management/download_upload_document');?>/'+( file.file.name )+'" class="btn btn-info btn-5">Download</a>';
                modal +='         <a href="'+( URL )+'" target="_blank" class="btn btn-info btn-5">Print</a>';
            }else if(file.file.s3_filename != null){
                modal +='         <a href="<?=base_url('hr_documents_management/download_upload_document');?>/'+( file.file.s3_filename )+'" class="btn btn-info btn-5">Download</a>';
                modal +='         <a href="'+( URL )+'" target="_blank" class="btn btn-info btn-5">Print</a>';
            }else{
                modal +='         <a href="<?=base_url('timeoff/download/document');?>/'+( file.id )+'" target="_blank" class="btn btn-info btn-5">Download</a>';
                modal +='         <a href="<?=base_url('timeoff/print/document');?>/'+( file.id )+'" target="_blank" class="btn btn-info btn-5">Print</a>';
            }
            modal +='                </div>';
            modal +='            </div>';
            modal +='     </div>';
            modal +='</div>';
            //
            emt.modal.modal('hide');
            // Show modal content
            $('#js-attachment-view-modal').remove();
            $('body').append(modal);
            $('#js-attachment-view-modal').modal();
        }
        $(document).on('hidden.bs.modal', '#js-attachment-view-modal', function (e) { emt.modal.modal('show'); });

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
        function showDraft(){
            resetCreateModal();
            attachmentMode = 'draft';
            // Set predefined values
            $('#js-types-draft').select2('val', currentRequest.Info.Category);
            $('#js-policies-draft').select2('val', currentRequest.Info.policyId);
            //
            if(currentRequest.Info.timeoff_breakdown.active.hours !== undefined)
            $('#js-request-hours-draft').val(currentRequest.Info.timeoff_breakdown.active.hours);
            if(currentRequest.Info.timeoff_breakdown.active.minutes !== undefined)
            $('#js-request-minutes-draft').val(currentRequest.Info.timeoff_breakdown.active.minutes);
            if(currentRequest.Info.timeoff_breakdown.active.days !== undefined)
            $('#js-request-days-draft').val(currentRequest.Info.timeoff_breakdown.active.days);
            
             // FMLA
             // Set Partial box
            if(currentRequest.Info.fmla != null && currentRequest.Info.fmla != '0'){
                $('.js-fmla-box-draft').show();
                $('.js-fmla-type-check-draft[value="'+(currentRequest.Info.fmla)+'"]').prop('checked', true);
                $('.js-fmla-check-draft[value="yes"]').prop('checked', true);
            }else{
                $('.js-fmla-check-draft[value="no"]').trigger('click');
            }
             // Multiple days off
            var ld = {};
            ld.startDate = moment(currentRequest.Info.request_from_date, 'YYYY-MM-DD').format('MM-DD-YYYY');
            ld.endDate = moment(currentRequest.Info.request_to_date, 'YYYY-MM-DD').format('MM-DD-YYYY');
            ld.days = currentRequest.Info.timeoff_days;
            //
            $('#js-timeoff-start-date-draft').val(ld.startDate.replace(/-/g,'/'));
            $('#js-timeoff-end-date-draft').val(ld.endDate.replace(/-/g,'/'));
            remakeRangeRowsDraft(ld);
            //
            $('#js-update-draft-sid').val(currentRequest.Info.requestId);
            //
            // Phase 3
            //
            switch (currentRequest.Info.Category.toLowerCase()) {
                case 'vacation':
                    $('#js-vacation-return-date-draft').val(currentRequest.Info.return_date == null || currentRequest.Info.return_date == '0000-00-00' ? '' : moment(currentRequest.Info.return_date, 'YYYY-MM-DD').format('MM/DD/YYYY'));
                    $('#js-vacation-address-draft').val(currentRequest.Info.temporary_address);
                    $('#js-vacation-contact-number-draft').val(currentRequest.Info.emergency_contact_number);
                    $('.js-vacation-row-draft').show();
                break;

                case 'bereavement':
                    $('#js-bereavement-return-date-draft').val(currentRequest.Info.return_date == null || currentRequest.Info.return_date == '0000-00-00' ? '' : moment(currentRequest.Info.return_date, 'YYYY-MM-DD').format('MM/DD/YYYY'));
                    $('#js-bereavement-relationship-draft').val(currentRequest.Info.relationship);
                    $('.js-bereavement-row-draft').show();
                break;

                case 'compensatory/ in lieu time':
                    $('#js-compensatory-return-date-draft').val(currentRequest.Info.return_date == null || currentRequest.Info.return_date == '0000-00-00' ? '' : moment(currentRequest.Info.return_date, 'YYYY-MM-DD').format('MM/DD/YYYY'));
                    $('#js-compensatory-start-time-draft').val(currentRequest.Info.compensation_start_time);
                    $('#js-compensatory-end-time-draft').val(currentRequest.Info.compensation_end_time);
                    $('.js-compensatory-row-draft').show();
                break;
            }
            // Phase 3 ends
            //
            CKEDITOR.instances['js-reason-draft'].setData(currentRequest.Info.reason);
            // Set attachments
            attachedDocuments = {};
            if(currentRequest.Attachments.length !== 0){
                var i = 0, il = currentRequest.Attachments.length;
                for(i; i < il; i++){
                    var atc = currentRequest.Attachments[i];

                    let sd = atc.serialized_data == null ? {} : JSON.parse(atc.serialized_data);
                    atc.showEmployerSection = sd.hasOwnProperty('type') && atc.document_title.toLowerCase() == 'health' ? true : false;
                    //
                    attachedDocuments[atc.sid] = {
                        id: atc.sid,
                        created_at: moment(atc.created_at).format('MM-DD-YYYY'),
                        type: atc.document_type,
                        serialized_data: atc.serialized_data,
                        file: {
                            name: sd.hasOwnProperty('type') ? sd.type : atc.s3_filename,
                            s3_filename: atc.s3_filename
                        },
                        showEmployerSection: atc.showEmployerSection,
                        slug: atc.document_title.replace(/[^0-9a-zA-Z.]/g, '_').toLowerCase(),
                        title: atc.document_type == 'generated' ? FMLACategories[atc.document_title.toLowerCase()] : atc.document_title
                    };
                }
                setAttachmentTable();
            }
            //
            $('#js-timeoff-draft-modal').modal();
            loader('hide');
            CKEDITOR.instances['js-reason-draft'].destroy(); 
            CKEDITOR.replace('js-reason-draft');
        }
        //
        function showView(){
            resetViewModal();
            $('#js-timeoff-view-modal').find('input, textarea, select').prop('disabled', true);
            attachmentMode = 'view';
            // Set predefined values
            $('#js-types-view').val(currentRequest.Info.Category);
            $('#js-policies-view').val(currentRequest.Info.policyId);
            $('#js-timeoff-date-view').val(moment(currentRequest.Info.requested_date, 'YYYY-MM-DD').format('MM-DD-YYYY'));
            //
            if(currentRequest.Info.timeoff_breakdown.active.hours !== undefined)
            $('#js-request-hours-view').val(currentRequest.Info.timeoff_breakdown.active.hours);
            if(currentRequest.Info.timeoff_breakdown.active.minutes !== undefined)
            $('#js-request-minutes-view').val(currentRequest.Info.timeoff_breakdown.active.minutes);
            if(currentRequest.Info.timeoff_breakdown.active.days !== undefined)
            $('#js-request-days-view').val(currentRequest.Info.timeoff_breakdown.active.days);
            
             // FMLA
             // Set Partial box
            if(currentRequest.Info.fmla != null && currentRequest.Info.fmla != '0'){
                $('.js-fmla-box-view').show();
                $('.js-fmla-type-check-view[value="'+(currentRequest.Info.fmla)+'"]').prop('checked', true);
                $('.js-fmla-check-view[value="yes"]').prop('checked', true);
            }else{
                $('.js-fmla-check-view[value="no"]').trigger('click');
            }
            // Multiple days off
            var ld = {};
            ld.startDate = moment(currentRequest.Info.request_from_date, 'YYYY-MM-DD').format('MM-DD-YYYY');
            ld.endDate = moment(currentRequest.Info.request_to_date, 'YYYY-MM-DD').format('MM-DD-YYYY');
            ld.days = currentRequest.Info.timeoff_days;
            //
            $('#js-timeoff-start-date-view').val(ld.startDate.replace(/-/g,'/'));
            $('#js-timeoff-end-date-view').val(ld.endDate.replace(/-/g,'/'));
            remakeRangeRowsView(ld);
            //
            $('#js-update-view-sid').val(currentRequest.Info.requestId);
            //
            $('#js-reason-view').html(currentRequest.Info.reason);
            //
            // Phase 3
            //
            switch (currentRequest.Info.Category.toLowerCase()) {
                case 'vacation':
                    $('#js-vacation-return-date-view').val(currentRequest.Info.return_date == null || currentRequest.Info.return_date == '0000-00-00' ? '' : moment(currentRequest.Info.return_date, 'YYYY-MM-DD').format('MM-DD-YYYY'));
                    $('#js-vacation-address-view').val(currentRequest.Info.temporary_address);
                    $('#js-vacation-contact-number-view').val(currentRequest.Info.emergency_contact_number);
                    $('.js-vacation-row-view').show();
                break;

                case 'bereavement':
                    $('#js-bereavement-return-date-view').val(currentRequest.Info.return_date == null || currentRequest.Info.return_date == '0000-00-00' ? '' : moment(currentRequest.Info.return_date, 'YYYY-MM-DD').format('MM-DD-YYYY'));
                    $('#js-bereavement-relationship-view').val(currentRequest.Info.relationship);
                    $('.js-bereavement-row-view').show();
                break;

                case 'compensatory/ in lieu time':
                    $('#js-compensatory-return-date-view').val(currentRequest.Info.return_date == null || currentRequest.Info.return_date == '0000-00-00' ? '' : moment(currentRequest.Info.return_date, 'YYYY-MM-DD').format('MM-DD-YYYY'));
                    $('#js-compensatory-start-time-view').val(currentRequest.Info.compensation_start_time);
                    $('#js-compensatory-end-time-view').val(currentRequest.Info.compensation_end_time);
                    $('.js-compensatory-row-view').show();
                break;
            }
            // Phase 3 ends
            // Set attachments
            attachedDocuments = {};
            if(currentRequest.Attachments.length !== 0){
                var i = 0, il = currentRequest.Attachments.length;
                for(i; i < il; i++){
                    var atc = currentRequest.Attachments[i];

                    let sd = atc.serialized_data == null ? {} : JSON.parse(atc.serialized_data);
                    atc.showEmployerSection = sd.hasOwnProperty('type') && atc.document_title.toLowerCase() == 'health' ? true : false;
                    //
                    attachedDocuments[atc.sid] = {
                        id: atc.sid,
                        created_at: moment(atc.created_at).format('MM-DD-YYYY'),
                        type: atc.document_type,
                        serialized_data: atc.serialized_data,
                        file: {
                            name: sd.hasOwnProperty('type') ? sd.type : atc.s3_filename,
                            s3_filename: atc.s3_filename
                        },
                        showEmployerSection: atc.showEmployerSection,
                        slug: atc.document_title.replace(/[^0-9a-zA-Z.]/g, '_').toLowerCase(),
                        title: atc.document_type == 'generated' ? FMLACategories[atc.document_title.toLowerCase()] : atc.document_title
                    };
                }
                setAttachmentTable();
            }
            //
            $('#js-timeoff-view-modal').modal();
            loader('hide');
        }

        // $('#js-timeoff-draft-modal').on('hidden.bs.modal', resetCreateModal);
        //
        function resetCreateModal(){
            //
            $('#js-types-draft').select2();
            $('#js-types-draft').select2('val', 0);
            //
            //
            $('#js-policies-draft').select2();
            $('#js-policies-draft').select2('val', 0);
            $('.js-policy-box-draft').hide();
            //
            $('#js-timeoff-date-draft').val(moment().add(1, 'day').format('MM-DD-YYYY'));
            //
            $('#js-request-hours-draft').val('');
            $('#js-request-minutes-draft').val('');
            $('#js-request-days-draft').val('');
            //
            $('.js-partial-check[value="no"]').prop('checked', true);
            $('.js-note-box-draft').hide(0);
            $('#js-start-partial-time-draft').val('');
            $('#js-end-partial-time-draft').val('');
            $('#js-note-draft').val('');
            //
            $('.js-fmla-wrap-draft').hide(0);
            $('.js-fmla-check-draft[value="no"]').prop('checked', true);
            $('.js-fmla-type-check-draft').prop('checked', true);
            //
            CKEDITOR.instances['js-reason-draft'].setData('');
            //
            $('.js-attachment-tr').remove();
            $('.js-no-records').show();
            //
            fmla = {};
            //
            $('#js-attachment-listing-draft tbody').find('tr.js-attachments-draft').remove();
            $('#js-attachment-listing-draft tbody').find('tr.js-no-records-draft').show();
            attachedDocuments = {};
            localDocument = {};
            //
            $('#js-update-draft-sid').val('');
            //
            $('.js-vacation-row-draft').hide();
            $('#js-vacation-contact-number-draft').val('');
            $('#js-vacation-return-date-draft').val('');

            $('.js-bereavement-row-draft').hide();
            $('#js-bereavement-relationship-draft').val('');
            $('#js-bereavement-return-date-draft').val('');

            $('.js-compensatory-row-draft').hide();
            $('#js-compensatory-start-time-draft').val('');
            $('#js-compensatory-end-time-draft').val('');
            $('#js-compensatory-return-date-draft').val('');
        }

        function resetEditModal(){
            //
            $('#js-types-edit').select2();
            $('#js-types-edit').select2('val', 0);
            //
            //
            $('#js-policies-edit').select2();
            $('#js-policies-edit').select2('val', 0);
            $('.js-policy-box-edit').hide();
            //
            $('#js-timeoff-date-edit').val(moment().add(1, 'day').format('MM-DD-YYYY'));
            //
            $('#js-request-hours-edit').val('');
            $('#js-request-minutes-edit').val('');
            $('#js-request-days-edit').val('');
            //
            $('.js-partial-check[value="no"]').prop('checked', true);
            $('.js-note-box-edit').hide(0);
            $('#js-start-partial-time-edit').val('');
            $('#js-end-partial-time-edit').val('');
            $('#js-note-edit').val('');
            //
            $('.js-fmla-wrap-edit').hide(0);
            $('.js-fmla-check-edit[value="no"]').prop('checked', true);
            $('.js-fmla-type-check-edit').prop('checked', true);
            //
            CKEDITOR.instances['js-reason-edit'].setData('');
            //
            $('.js-attachment-tr').remove();
            $('.js-no-records').show();
            //
            fmla = {};
            //
            $('#js-attachment-listing-edit tbody').find('tr.js-attachments-edit').remove();
            $('#js-attachment-listing-edit tbody').find('tr.js-no-records-edit').show();
            attachedDocuments = {};
            localDocument = {};
            //
            $('#js-update-edit-sid').val('');
            //
            $('.js-vacation-row-edit').hide();
            $('#js-vacation-contact-number-edit').val('');
            $('#js-vacation-return-date-edit').val('');

            $('.js-bereavement-row-edit').hide();
            $('#js-bereavement-relationship-edit').val('');
            $('#js-bereavement-return-date-edit').val('');

            $('.js-compensatory-row-edit').hide();
            $('#js-compensatory-start-time-edit').val('');
            $('#js-compensatory-end-time-edit').val('');
            $('#js-compensatory-return-date-edit').val('');
        }

        function resetViewModal(){
            //
            $('#js-types-view').val('');
            //
            //
            $('#js-policies-view').val('');
            $('.js-policy-box-view').hide();
            //
            $('#js-timeoff-date-view').val(moment().add(1, 'day').format('MM-DD-YYYY'));
            //
            $('#js-request-hours-view').val('');
            $('#js-request-minutes-view').val('');
            $('#js-request-days-view').val('');
            //
            $('.js-partial-check[value="no"]').prop('checked', true);
            $('.js-note-box-view').hide(0);
            $('#js-start-partial-time-view').val('');
            $('#js-end-partial-time-view').val('');
            $('#js-note-view').val('');
            //
            $('.js-fmla-wrap-view').hide(0);
            $('.js-fmla-check-view[value="no"]').prop('checked', true);
            $('.js-fmla-type-check-view').prop('checked', true);
            //
            $('#js-reason-view').val('');
            //
            $('.js-attachment-tr').remove();
            $('.js-no-records').show();
            //
            fmla = {};
            //
            $('#js-attachment-listing-view tbody').find('tr.js-attachments-view').remove();
            $('#js-attachment-listing-view tbody').find('tr.js-no-records-view').show();
            attachedDocuments = {};
            localDocument = {};
            //
            $('#js-update-view-sid').val('');
            //
            $('.js-vacation-row-view').hide();
            $('#js-vacation-contact-number-view').val('');
            $('#js-vacation-return-date-view').val('');

            $('.js-bereavement-row-view').hide();
            $('#js-bereavement-relationship-view').val('');
            $('#js-bereavement-return-date-view').val('');

            $('.js-compensatory-row-view').hide();
            $('#js-compensatory-start-time-view').val('');
            $('#js-compensatory-end-time-view').val('');
            $('#js-compensatory-return-date-view').val('');
        }

        //
        $('#js-fmla-modal-draft').on('hide.bs.modal', function(){ 
            if(attachmentMode == 'add'){
                $('#js-timeoff-modal').modal('show'); 
            } else{
                $('#js-timeoff-'+( attachmentMode )+'-modal').modal('show'); 
            }
        });
        
    })
</script>


<style>
    .js-request-titles{ min-height: 51px !important; }
    .bg-cyan{
        width:250px !important;
        height:145px !important;
        }
    .current-date{
          position: absolute;
          bottom: 0;
    }
    .js-holiday-list-ul li,
    .js-holiday-list-ul li p{ color: #000000 !important; }
    .js-holiday-list-ul{ margin-top: 0; }
    .js-holiday-list-ul li p{ margin-top: 0; }

    .js-policy-list-ul li,
    .js-policy-list-ul li p,
    .js-policy-list-ul li span{ color: #000000 !important; }
    .js-policy-list-ul{ margin-top: 0; }
    .js-policy-list-ul li p{ margin-top: 0; }

    .paginationjs-pages{ margin-top: 10px; margin-left: 10px; }
</style>