<?php
    $activeEmployees = $inActiveEmployees = array();
    if(sizeof($employees)){
        foreach ($employees as $employee) {
            if($employee['status'] == 1) $activeEmployees[] = $employee;
            if($employee['status'] == 0) $inActiveEmployees[] = $employee;
        }
    }
?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?><!-- profile_left_menu_company -->
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>Government Agents</span>
                            <a href="<?= base_url('govt_user/add') ?>" class="dashboard-link-btn">Create Agent</a>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <?php if($this->session->flashdata('error')){ ?>
                        <div class="flash_error_message">
                            <div class="alert alert-info alert-dismissible" role="alert">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <?php echo $this->session->flashdata('error');?>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="hr-search-main" style="display: block;">
                            <form method="GET" action="javascript:void(0)" id="js-search-filter">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                        <div class="field-row">
                                            <label class="">Agencies</label>
                                            <select class="invoice-fields js-select2" id="js-agencies">
                                                <option value="all">[Select a Agency]</option>
                                                <?php
                                                    if(sizeof($agencies)){
                                                        foreach ($agencies as $agency) {
                                                            if($agency['agency_name'] == '') continue;
                                                            echo '<option value="'.( $agency['agency_name'] ).'">'.( $agency['agency_name'] ).'</option>';
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                        <div class="field-row">
                                            <label class="">Agents</label>
                                            <select class="invoice-fields js-select2" id="js-agents">
                                                <option value="all">[Select a Agent]</option>
                                                <?php
                                                    if(sizeof($agents)){
                                                        foreach ($agents as $agent) {
                                                            if($agent['agent_name'] == '') continue;
                                                            echo '<option value="'.( $agent['agent_name'] ).'">'.( $agent['agent_name'] ).'</option>';
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                        <div class="field-row">
                                            <label class="">Status</label>
                                            <select id="js-status" class="js-select2">
                                                <option value="all" selected="true">All</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">In-Active</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                        <div class="field-row">
                                            <label class="">Date From</label>
                                            <input class="invoice-fields"
                                                   type="text"
                                                   readonly="true"
                                                   name="js-start-date-input"
                                                   id="js-filter-start-date"
                                                   value="<?=$startDate;?>"
                                                   />
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                        <div class="field-row">
                                            <label class="">Date To</label>
                                            <input class="invoice-fields"
                                                   type="text"
                                                   readonly="true"
                                                   name="js-end-date-input"
                                                   id="js-filter-end-date"
                                                   value="<?=$endDate;?>"
                                                   />
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                        <div class="field-row">
                                            <label class="">&nbsp;</label>
                                            <a class="btn btn-success btn-block js-apply-filter-btn" href="javascript:void(0)" >Apply Filters</a>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                        <div class="field-row">
                                            <label class="">&nbsp;</label>
                                            <a class="btn btn-success btn-block" href="<?=base_url('govt_user/view');?>">Reset Filters</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <hr />
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <!-- <th>Sid</th> -->
                                    <th>Agency Name</th>
                                    <th>Name / Username / Email</th>
                                    <th>Employees</th>
                                    <th>Note</th>
                                    <th>Status</th>
                                    <th>Expiration Date</th>
                                    <th>Created Date</th>
                                    <th>Send Credentials</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        <tbody>
                        <!-- Records -->
                        <?php
                            $r = array();
                            if(sizeof($records)){
                                $date = date('Y-m-d');
                                foreach($records as $record){
                                    $record = (Object) $record;
                                    $r[] = array('Id' => $record->sid, 'EmployeeIds' => explode(',', $record->employee_sids));
                                    $employeeSids = explode(',', $record->employee_sids);
                                    $totalRecords = sizeof($employeeSids);
                                    if($employeeSids[0] == 'all'){
                                        $totalRecords = sizeof(
                                            $record->employee_type == 'all' ? $employees : (
                                                $record->employee_type == 'active' ? $activeEmployees : $inActiveEmployees
                                            )
                                        );
                                    }
                                    $isExpired = false;
                                    if($record->is_expired == 1) $isExpired = true;
                                    else if($record->expire_at != null && $record->expire_at < $date == 1) $isExpired = true;
                        ?>
                            <tr data-id="<?=$record->sid;?>">
                                <!-- <td class="align-middle"><?php //echo $record->sid;?></td> -->
                                <td class="align-middle"><?=$record->agency_name;?></td>
                                <td class="align-middle"><p>
                                    <strong><?=$record->agent_name;?></strong> <br />
                                    <strong><?=$record->username;?></strong> <br />
                                    <?=$record->email;?>
                                </p></td>
                                <td class="align-middle"><abbr
                                    class="js-abbr-<?=$record->sid;?>"
                                        data-html="true"
                                    data-placement="left"
                                    data-toggle="popover"
                                    title="Employees"
                                    data-type="<?=$record->employee_type;?>"
                                    data-content=""><?=$totalRecords;?></abbr></td>
                                <td class="align-middle">
                                    <i
                                        class="fa fa-sticky-note<?=$record->note == '' ? '-o' : '';?>"
                                        <?php if($record->note != '') { ?>
                                        style="color: #81b431;"
                                        data-placement="left"
                                        data-toggle="popover"
                                        data-html="true"
                                        onmouseenter="this.click()"
                                        onmouseleave="this.click()"
                                        title="Note"
                                        data-content="<?=$record->note == '' ? 'N/A' : $record->note;?>"
                                        <?php } ?>
                                    ></i>

                                </td>
                                <td class="align-middle <?php
                                    if($record->is_expired == 1) echo 'text-danger';
                                    else if($record->expire_at != null && $record->expire_at < $date == 1) echo 'text-danger';
                                    else echo 'text-success';
                                ?>"><?php
                                    if($record->is_expired == 1) echo 'In-Active';
                                    else if($record->expire_at != null && $record->expire_at < $date == 1) echo 'In-Active';
                                    else echo 'Active';
                                ?></td>
                                <td class="align-middle"><?=$record->expire_at == null ? 'N/A' : reset_datetime(array(
                                    'datetime' => $record->expire_at,
                                    '_this' => $this
                                ))?></td>
                                <td class="align-middle"><?=reset_datetime(array(
                                    'datetime' => $record->created_at,
                                    '_this' => $this
                                ))?></td>
                                <td class="align-middle" align="center">
                                    <img src="<?=base_url('assets/manage_admin/images/bulb-'.( $record->last_sent_date != '' ? 'green' : 'red' ).'.png');?>" />
                                    <br />
                                    <a href="javascript:void(0)" class="btn btn-success <?=$isExpired ? 'disabled' : 'js-send-credentials';?>">Send Credentials</a>
                                    <?php if($record->last_sent_date != null){ ?>
                                    <br />
                                    <p><?=reset_datetime(array(
                                        'datetime' => $record->last_sent_date,
                                        '_this' => $this
                                    ));?></p>
                                    <?php } ?>
                                </td>
                                <td class="align-middle">
                                    <a href="<?=base_url('govt_user/edit/'.( $record->sid ).'');?>" class="btn btn-warning"> <i class="fa fa-pencil"></i></a>
                                    <a href="javascript:void(0)" class="btn btn-danger <?=$isExpired ? 'disabled js-reactivate' : ' js-expire';?>" title="Deactivate Credentials"> <i class="fa fa-shield"></i></a>
                                </td>
                            </tr>
                        <?php
                                }
                            } else {
                        ?>
                            <tr>
                                <td colspan="9">
                                    <p class="alert alert-info text-center">No Government Agent Credentials records found.</p>
                                </td>
                            </tr>
                        <?php
                            }
                        ?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Loader -->
<div id="my_loader" class="text-center my_loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we are generating a preview
        </div>
    </div>
</div>


<script>
    //
    String.prototype.ucwords = function() {
        str = this.toLowerCase();
        return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function(s){ return s.toUpperCase(); });
    };
    $(function (){
        var employees = <?=json_encode($employees);?>,
        records = <?=json_encode($r);?>;
        //
        loadEmployees();
        function loadEmployees(){
            if(employees.length != 0 && records.length != 0){
                records.map(function(v0){
                    if(v0.EmployeeIds.length === 0) return 0;
                    var type = $('.js-abbr-'+( v0.Id )+'').data('type');
                    //
                    var name = '';
                    if(v0['EmployeeIds'][0] == 'all'){
                        var i = 0,
                        il = employees.length;
                        for(i; i < il; i++){
                            if(type == 'active' && employees[i]['status'] != 1){}
                            else if(type == 'inactive' && employees[i]['status'] != 0){}
                            else name += '<p>'+remakeEmployeeName(employees[i])+'</p> ';
                        }
                    }else{
                        v0.EmployeeIds.map(function(v1){
                            var i = 0,
                            il = employees.length;
                            for(i; i < il; i++){
                                if(employees[i]['sid'] == v1) {
                                    name += '<p>'+remakeEmployeeName(employees[i])+'</p> ';
                                    break;
                                }
                            }
                        });
                    }
                    name = name.substring(0, name.length - 2);
                    $('.js-abbr-'+( v0.Id )+'').attr('data-content', name);
                });
            }

            $('abbr').popover({ html : true});
            $('#my_loader').fadeOut();
        }
        $('abbr').hover(function(){ $(this).click() })

        
        //
        function remakeEmployeeName(
            o,
            d
        ){
            //
            var r = '';
            //
            if(d === undefined) r += o.first_name+' '+o.last_name;
            //
            r = r.ucwords();
            //
            if(o.job_title != '' && o.job_title != null) r += ' ('+( o.job_title )+')';
            //
            r += ' [';
            //
            if(typeof(o['is_executive_admin']) !== undefined && o['is_executive_admin'] != 0) r += 'Executive ';
            //
            if(o['access_level_plus'] == 1 && o['pay_plan_flag'] == 1)  r += o['access_level']+' Plus / Payroll';
            else if(o['access_level_plus'] == 1) r += o['access_level']+' Plus';
            else if(o['pay_plan_flag'] == 1) r += o['access_level']+' Payroll';
            else r += o['access_level'];
            //
            r += ']';
            //
            return r;
        }


        $('.js-send-credentials').click(function(){
            $('.loader-text').text('Please wait while we are sending email.');
            $('#my_loader').fadeIn();
            var sid = $(this).closest('tr').data('id');
            $.post("<?=base_url('govt_user/handler');?>", {
                action: 'send_email',
                sid: sid
            }, function(resp){
                alertify.alert('SUCCESS!', resp.Response, function(){ window.location.reload(); });
            });
        });

        $('.js-expire').click(function(){
            var sid = $(this).closest('tr').data('id');
            alertify.confirm('Do you really want to deactivate this agent?',
                function(){
                    $('.loader-text').text('Please wait while we are deactivating this agent.');
                    $('#my_loader').fadeIn();
                    $.post("<?=base_url('govt_user/handler');?>", {
                        action: 'expire_agent',
                        sid: sid
                    }, function(resp){
                        alertify.alert('SUCCESS!', resp.Response, function(){ window.location.reload(); });
                    });
                }
            )
            .set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        });

        //
        $('.js-apply-filter-btn').click(function(e){
            e.preventDefault();
            var url = "<?=base_url('govt_user/view');?>/";
            url += $('#js-agencies').val()+'/';
            url += $('#js-agents').val()+'/';
            url += $('#js-status').val()+'/';
            url += ($('#js-filter-start-date').val() == '' ? 'all' : $('#js-filter-start-date').val()) +'/';
            url += ($('#js-filter-end-date').val() == '' ? 'all' : $('#js-filter-end-date').val());

            window.location.href = url;
        });

        $('#js-filter-start-date').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function (v) { $('#js-filter-end-date').datepicker('option', 'minDate', v); }
        })
        $('#js-filter-end-date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
        }).datepicker('option', 'minDate', $('#js-filter-start-date').val());


        // $('.js-reactivate').click(function(){
        //     var sid = $(this).closest('tr').data('id');
        //     alertify.confirm('Do you really want to reactivate this agent?',
        //         function(){
        //             $('.loader-text').text('Please wait while we are activating this agent.');
        //             $('#my_loader').fadeIn();
        //             $.post("<?=base_url('govt_user/handler');?>", {
        //                 action: 'activate_agent',
        //                 sid: sid
        //             }, function(resp){
        //                 alertify.alert('SUCCESS!', resp.Response, function(){ window.location.reload(); });
        //             });
        //         }
        //     )
        //     .set('labels', {
        //         ok: 'Yes',
        //         cancel: 'No'
        //     });
        // });

        $('.js-select2').select2();
        <?php if($search['agency'] != 'all') { ?>
        $('#js-agencies').select2('val', "<?=$search['agency'];?>");
        <?php } ?>
        <?php if($search['agent'] != 'all') { ?>
        $('#js-agents').select2('val', "<?=$search['agent'];?>");
        <?php } ?>
        <?php if($search['status'] != 'all') { ?>
        $('#js-status').select2('val', "<?=$search['status'];?>");
        <?php } ?>

    })
</script>

<style>
    .hr-required{ color: #cc0000; font-weight: bolder; }
    .align-middle{ vertical-align: middle !important; }
    .select2-container--default .select2-selection--single{
        background-color: #fff !important;
        border: 1px solid #aaa !important;
    }
    .select2-container .select2-selection--single .select2-selection__rendered{
        padding-left: 8px !important;
        padding-right: 20px !important;
    }
    .table thead tr{ background-color: #81b431 ; color: #fff; }
    .table thead tr th{ vertical-align: middle; }
</style>
