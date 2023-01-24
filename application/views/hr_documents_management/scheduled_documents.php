<style>
.select2-container--default .select2-selection--single {
    background-color: #fff !important;
    border: 1px solid #aaa !important;
    border-radius: 4px !important;
}

.select2-container .select2-selection--single {
    height: 40px !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px !important;
}

.csLoader{
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    background-color: #fff;
    z-index: 1;
}
.csLoader i{
    position: relative;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 30px;
    color: #81b431;
}
</style>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="<?=base_url('assets/js/moment.min.js');?>"></script>


<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <!--  -->
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                    <!--  -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a href="<?php echo base_url('hr_documents_management'); ?>"
                                        class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Document management
                                    </a>
                                    <?php echo $title; ?>
                                    <a href="<?php echo base_url('hr_documents_management'); ?>"
                                        class="dashboard-link-btn jsFilterBTN hidden" style="right: 10px; left: auto;">
                                        <i class="fa fa-search"></i>Filter
                                    </a>
                                </span>
                            </div>
                            <hr />
                        </div>
                    </div>

                    <!-- Filter -->
                    <div style="border: 1px solid #ddd; padding: 10px; border-radius: 5px; display: none; hidden" id="jsFilterBar">
                        <div class="row">
                            <!--  -->
                            <div class="col-sm-6">
                                <label>Employee</label>
                                <select id="jsEmployees">
                                    <option value="all">All</option>
                                    <?php 
                                        if(count($employeesList)) {
                                            foreach($employeesList as $employee) { ?>
                                            <option value="<?=$employee['sid'];?>"><?=remakeEmployeeName($employee);?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <!--  -->
                            <div class="col-sm-6">
                                <span class="pull-right">
                                    <br>
                                    <button class="btn btn-success">Apply</button>
                                    <button class="btn btn-success">Clear</button>
                                </span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <br />
                    <!-- Charts -->
                    <div style="border: 1px solid #ddd; padding: 10px; border-radius: 5px; position: relative; min-height: 100px;">
                        <div class="csLoader"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                        <div class="row">
                            <div class="col-sm-4 col-xs-12">
                                <div id="piechart_3d"></div>
                                <br />
                                <h5 class="text-center"><strong>Scheduled Document(s)</strong></h5>
                            </div>

                            <div class="col-sm-4 col-xs-12">
                                <div id="piechart_3d2"></div>
                                <br />
                                <h5 class="text-center"><strong>Document(s)</strong></h5>
                            </div>

                            <div class="col-sm-4 col-xs-12">
                                <div id="piechart_3d22"></div>
                                <br />
                                <h5 class="text-center"><strong>Today(s)</strong></h5>
                            </div>
                        </div>
                    </div>

                    <br />
                    <!--  -->

                    <div id="jsReportArea" style=" position: relative;">
                        <!-- Loader -->
                        <div class="csLoader"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"
                                                href="#daily_tab">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                Daily
                                                <div class="pull-right total-records daily_table_rows_count"></div>
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="panel-body panel-collapse collapse" id="daily_tab">
                                        <div class="table-responsive table-outer" style="padding-bottom: 200px;">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Document Title</th>
                                                        <th>Document Type</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <!--  -->
                                                <tbody id="daily_table_rows">
                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"
                                                href="#weekly_tab">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                Weekly
                                                <div class="pull-right total-records weekly_table_rows_count"></div>
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="panel-body panel-collapse collapse" id="weekly_tab">
                                        <div class="table-responsive table-outer" style="padding-bottom: 200px;">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Document Title</th>
                                                        <th>Document Type</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <!--  -->
                                                <tbody id="weekly_table_rows">
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"
                                                href="#monthly_tab">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                Monthly
                                                <div class="pull-right total-records monthly_table_rows_count"></div>
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="panel-body panel-collapse collapse" id="monthly_tab">
                                        <div class="table-responsive table-outer" style="padding-bottom: 200px;">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Document Title</th>
                                                        <th>Document Type</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <!--  -->
                                                <tbody id="monthly_table_rows">
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"
                                                href="#yearly_tab">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                Yearly
                                                <div class="pull-right total-records yearly_table_rows_count"></div>
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="panel-body panel-collapse collapse" id="yearly_tab">
                                        <div class="table-responsive table-outer" style="padding-bottom: 200px;">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Document Title</th>
                                                        <th>Document Type</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <!--  -->
                                                <tbody id="yearly_table_rows">
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"
                                                href="#custom_tab">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                Custom
                                                <div class="pull-right total-records custom_table_rows_count"></div>
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="panel-body panel-collapse collapse" id="custom_tab">
                                        <div class="table-responsive table-outer" style="padding-bottom: 200px;">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Document Title</th>
                                                        <th>Document Type</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <!--  -->
                                                <tbody id="custom_table_rows">
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--  -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--  -->
<div class="modal fade" id="js-pending-employee-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="loader js-pde-loader"><i class="fa fa-spinner fa-spin"></i></div>
                <h4>Total Employee(s): <span>0</span></h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Employee Email</th>
                                <th>Completed</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .csModalLoader{
        position: absolute;
        right: 0;
        left: 0;
        top: 0;
        bottom: 0;
        width: 100%;
        background: #ffffff;
        z-index: 1;
    }
    .csModalLoader i{
        position: relative;
        top: 50%;
        left: 50%;
        font-size: 30px;
        transform: translate(-50%, -50%);
        color: #81b431;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="jsScheduleModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #81b431; color: #fff;">
                <h5 class="modal-title"><span></span></h5>
            </div>
            <div class="modal-body">
                <div class="csModalLoader jsModalLoader"><i class="fa fa-spin fa-circle-o-notch"></i></div>
                <!--  -->
                <div class="row">
                    <!-- Selection row -->
                    <div class="col-sm-12">
                        <!-- None -->
                        <label class="control control--radio">
                            None &nbsp;&nbsp;
                            <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="none"/>
                            <div class="control__indicator"></div>
                        </label>
                        <!-- Daily -->
                        <label class="control control--radio">
                            Daily &nbsp;&nbsp;
                            <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="daily"/>
                            <div class="control__indicator"></div>
                        </label>
                        <!-- Weekly -->
                        <label class="control control--radio">
                            Weekly &nbsp;&nbsp;
                            <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="weekly"/>
                            <div class="control__indicator"></div>
                        </label>
                        <!-- Monthly -->
                        <label class="control control--radio">
                            Monthly &nbsp;&nbsp;
                            <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="monthly"/>
                            <div class="control__indicator"></div>
                        </label>
                        <!-- Yearly -->
                        <label class="control control--radio">
                            Yearly &nbsp;&nbsp;
                            <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="yearly"/>
                            <div class="control__indicator"></div>
                        </label>
                        <!-- Custom -->
                        <label class="control control--radio">
                            Custom &nbsp;&nbsp;
                            <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="custom"/>
                            <div class="control__indicator"></div>
                        </label>
                        <!--  -->
                    </div>
                </div>
                <!--  -->
                <div class="row">
                    <!-- Custom date row -->
                    <div class="col-sm-12 jsCustomDateRow" style="display: none;">
                        <br />
                        <label>Select a date & time</label>
                        <div class="row">
                            <div class="col-sm-4">
                                <input type="text" class="form-control jsDatePicker" name="assignAndSendCustomDate" readonly="true" />
                            </div>
                            <div class="col-sm-4 nopaddingleft">
                                <input type="text" class="form-control jsTimePicker" name="assignAndSendCustomTime" readonly="true" />
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="row">
                    <div class="col-sm-12"><hr /></div>
                </div>
                <!--  -->
                <div class="row">
                    <!-- Against Selected Employees -->
                    <div class="col-sm-12">
                        <label>Employee(s)</label>
                        <select multiple="true" name="assignAdnSendSelectedEmployees[]" class="assignSelectedEmployees">
                            <option value="-1">All</option>
                            <?php foreach($employeesList as $key => $employee) { ?>
                                <option value="<?=$employee['sid'];?>"><?=remakeEmployeeName($employee);?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success jsModalSaveBTN">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script>
$(() => {

    let types = [
        'daily',
        'weekly',
        'monthly',
        'yearly',
        'custom'
    ],
    //
    daily_table_rows_count = 0,
    weekly_table_rows_count = 0,
    monthly_table_rows_count = 0,
    yearly_table_rows_count = 0,
    custom_table_rows_count = 0,
    total_documents_count = 0,
    total_documents_assigned_count = 0,
    total_documents_unassigned_count = 0,
    todaysReport = { assigned: 0, completed: 0 },
    selectedDocument = {},
    allDocuments = [];

    String.prototype.ucwords = function() {
        str = this.toLowerCase();
        return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
        function(s){
        return s.toUpperCase();
        });
    };

    //
    $('#jsEmployees').select2();
    //
    $('.jsFilterBTN').click(function(e){
        e.preventDefault();
        $('#jsFilterBar').toggle();
    });
    //
    google.charts.load("current", {
        packages: ["corechart"]
    });

    //
    $(document).on('click', '.jsViewEmployees', function(e){
        //
        e.preventDefault();
        //
        //
        $('#js-pending-employee-modal .modal-body tbody').html('');
        $('.js-pde-loader').fadeIn(300);
        //
        $('#js-pending-employee-modal .modal-title').html(
            $(this).closest('tr').data('title')
        );
        //
        $('#js-pending-employee-modal').modal();
        //
        //
        $.get(
            "<?=base_url('hr_documents_management/get_scheduled_documents_employee/');?>/"+( $(this).closest('tr').data('id') )+"",
            function(resp) {
                //
                var rows = '';
                //
                $('#js-pending-employee-modal .modal-body span').text(resp.length);
                //
                if(resp.length === 0) {
                    rows += '<tr><td colspan="3"><p class="alert alert-info text-center">No employee(s) found.</p></td></tr>';
                } else{
                    $.each(resp, function(i, v) {
                            rows += '<tr>';
                            rows += '  <td>'+( v.EmployeeName )+'</td>';
                            rows += '  <td>'+( v.EmployeeEmail )+'</td>';
                            rows += '  <td>'+( v.IsCompleted === false ? "No" : "Yes" )+'</td>';
                            rows += '</tr>';
                    });
                }

                $('#js-pending-employee-modal .modal-body tbody').html(rows);
                $('.js-pde-loader').fadeOut(300);
        });
    });

    loadData();
    //
    function loadData() {
        $.get(
            "<?=base_url("scheduled_documents/all");?>",
            (resp) => {
            
                $.each(resp['Documents'], function(key ,input_value) {
                    allDocuments[input_value.sid] = input_value;
                    //
                    let document_title = input_value.document_title;
                    let document_type = input_value.document_type;
                    let assign_type = input_value.assign_type;
                    let assigned_employee = input_value.assigned_employee_list;
                    //
                    let tr_html = generate_table_row(
                        input_value.sid, 
                        document_title, 
                        document_type
                    );
                    //
                    if (assign_type == 'daily') {
                        daily_table_rows_count = daily_table_rows_count + 1;
                        $("#daily_table_rows").append(tr_html);
                    } else if (assign_type == 'weekly') {
                        weekly_table_rows_count = weekly_table_rows_count + 1;
                        $("#weekly_table_rows").append(tr_html);
                    } else if (assign_type == 'monthly') {
                        monthly_table_rows_count = monthly_table_rows_count + 1;
                        $("#monthly_table_rows").append(tr_html);
                    } else if (assign_type == 'yearly') {
                        yearly_table_rows_count = yearly_table_rows_count + 1;
                        $("#yearly_table_rows").append(tr_html);
                    } else if (assign_type == 'custom') {
                        custom_table_rows_count = custom_table_rows_count + 1;
                        $("#custom_table_rows").append(tr_html);
                    } 
                    // 
                    total_documents_count = total_documents_count + 1;
                    // 
                    if (assigned_employee != null) {
                        total_documents_assigned_count = total_documents_assigned_count + 1;
                    } else {
                        total_documents_unassigned_count = total_documents_unassigned_count + 1;
                    }

                });
                //
                $(".daily_table_rows_count").html('<b>Total: '+daily_table_rows_count+'</b>');  
                $(".weekly_table_rows_count").html('<b>Total: '+weekly_table_rows_count+'</b>');  
                $(".monthly_table_rows_count").html('<b>Total: '+monthly_table_rows_count+'</b>');  
                $(".yearly_table_rows_count").html('<b>Total: '+yearly_table_rows_count+'</b>');  
                $(".custom_table_rows_count").html('<b>Total: '+custom_table_rows_count+'</b>');

                //
                todaysReport = resp.Sa;

                google.charts.setOnLoadCallback(drawChart);
                google.charts.setOnLoadCallback(drawChart2);

                $('.csLoader').fadeOut(300);
            }
        );
        
        google.charts.setOnLoadCallback(drawChart22);

    }
    
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Daily', daily_table_rows_count],
            ['Weekly', weekly_table_rows_count],
            ['Monthly', monthly_table_rows_count],
            ['Yearly', yearly_table_rows_count],
            ['Custom', custom_table_rows_count]
        ]);

        var options = {
            title: '',
            titleFontSize:16,
            pieHole: 0.4,
            // legend: 'none',
            chartArea: {width: 400, height: 300, top: '10%'},
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
    }
    
    function drawChart2() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Assigned', total_documents_assigned_count],
            ['Unassigned', total_documents_unassigned_count],
            ['Total', total_documents_count],
        ]);

        var options = {
            title: '',
            pieHole: 0.4,
            // legend: 'none',
            chartArea: {width: 400, height: 300, top: '10%'},
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d2'));
        chart.draw(data, options);
    }
       
    function drawChart22() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Assigned', todaysReport.assigned],
            ['Completed', todaysReport.completed],
        ]);

        var options = {
            title: '',
            pieHole: 0.4,
            // legend: 'none',
            chartArea: {width: 400, height: 300, top: '10%'},
            sliceVisibilityThreshold: 0
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d22'));
        chart.draw(data, options);
    }

    //
    function generate_table_row (
        documentSid, 
        document_title, 
        document_type
    ) {
        var rows = '';
        //
        rows += `<tr data-id="${documentSid}" data-title="${document_title}">`;

        rows += '   <td>';
        rows +=         document_title;
        rows += '   </td>';

        rows += '   <td>';
        rows +=         document_type.ucwords();
        rows += '   </td>';

        rows += '   <td align="left">';
        rows += '       <div class="btn-group">';
        rows += '           <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        rows += '               Action <span class="caret"></span>';
        rows += '           </button>';

        rows += '           <ul class="dropdown-menu">';
        rows += '               <li>';
        rows += `                   <a 
                                        href="javascript:void(0)"
                                        class="jsViewEmployees"
                                        >View Employee(s)</a>`;
        rows += '               </li>';
        rows += '               <li>';
        rows += `                   <a 
                                        href="javascript:void(0)"
                                        class="jsScheduleDocument"
                                    >Modify Schedule</a>`;
        rows += '               </li>';
        rows += '           </ul>';
        rows += '       </div>';
        rows += '   </td>';
        rows += '</tr>';
        //
        return rows;
    }

    //
    function remakeEmployeeName(o){
        //
        var r = '';
        //
        r += o.first_name+' '+o.last_name;
        //
        if(o.job_title != '' && o.job_title != null) r+= ' ['+( o.job_title )+']';
        //
        r += ' (';
        //
        if(typeof(o['is_executive_admin']) !== undefined && o['is_executive_admin'] != 0) r += 'Executive ';
        //
        if(o['access_level_plus'] == 1 && o['pay_plan_flag'] == 1)  r += o['access_level']+' Plus / Payroll';
        else if(o['access_level_plus'] == 1) r += o['access_level']+' Plus';
        else if(o['pay_plan_flag'] == 1) r += o['access_level']+' Payroll';
        else r += o['access_level'];
        //
        r += ')';
        //
        return r;
    }


    //
    $(document).on('click', '.jsScheduleDocument', function(e){
        //
        e.preventDefault();
        //
        $('.jsModalLoader').show();
        $('.jsModalSaveBTN').hide();
        $('#jsScheduleModal').find('.modal-header span').text($(this).closest('tr').data('title'));
        $('#jsScheduleModal').modal('show');
        //
        checkAndGetScheduleDocument($(this).closest('tr').data('id'));
    });

    //
    $('.jsModalSaveBTN').click((e) => {
        //
        e.preventDefault();
        //
        let obj = {};
        obj.assignAndSendDocument = $('.assignAndSendDocument:checked').val();
        obj.assignAdnSendSelectedEmployees = $('.assignSelectedEmployees').val();
        obj.assignAndSendCustomDate = $('input[name="assignAndSendCustomDate"]').val();
        obj.assignAndSendCustomTime = $('input[name="assignAndSendCustomTime"]').val();
        obj.documentSid = selectedDocument.sid;
        //
        $('.jsModalLoader').show();
        $('.jsModalSaveBTN').hide();
        //
        $.post(
            "<?=base_url('hr_documents_management/set_schedule_document');?>",
            obj,
            (resp) => {
                //
                if(resp == 'success'){
                    $('#jsScheduleModal').modal('hide');
                    alertify.alert('SUCCESS!', 'You have successfully updated the document.', () => {
                        window.location.reload();
                    });
                    return;
                }
                $('.jsModalLoader').hide();
                $('.jsModalSaveBTN').show();
                alertify.alert('ERROR!', 'Something went wrong while updating the document. Please, try again in a few moments.', () => {});
            }
        );

    });

    //
    function checkAndGetScheduleDocument(
        documentId
    ){
        //
        if(
            allDocuments[documentId] === undefined
        ){
            alertify.alert('ERROR!', 'The system in unable to verify this document.', () => {});
            return;
        }
        //
        selectedDocument = allDocuments[documentId];
        //
        let SE = null;
        //
        if(selectedDocument.assigned_employee_list != null && selectedDocument.assigned_employee_list != '' && selectedDocument.assigned_employee_list == 'all'){
            SE = ['-1'];
        }
        if(selectedDocument.assigned_employee_list != null && selectedDocument.assigned_employee_list != '' && selectedDocument.assigned_employee_list != 'all'){
            SE = JSON.parse(selectedDocument.assigned_employee_list);
        }
        //
        $(`.assignAndSendDocument[value="${selectedDocument.assign_type}"]`).prop('checked', true).trigger('change');
        $('.assignSelectedEmployees').select2('val', SE);
        $('.jsDatePicker').val(selectedDocument.assign_date);
        $('.jsTimePicker').val(selectedDocument.assign_time);
        //
        $('.jsModalLoader').hide();
        $('.jsModalSaveBTN').show();
    }

    //
    $('.assignSelectedEmployees').select2({ closeOnSelect: false });
    //
    $('.assignAndSendDocument[value="none"]').prop('checked', true);
    
    //
    $('.assignAndSendDocument').change(function(){
        //
        $('.jsCustomDateRow').hide();
        //
        if($(this).val().toLowerCase() == 'custom'){
            $('.jsCustomDateRow').show();
        }
    });
    
    //
    $('.jsDatePicker').datepicker({
        changeMonth: true,
        dateFormat: 'mm/dd'
    });

    //
    $('.jsTimePicker').datetimepicker({
        datepicker: false,
        format: 'g:i A',
        formatTime: 'g:i A',
        step: 15
    });








});
</script>