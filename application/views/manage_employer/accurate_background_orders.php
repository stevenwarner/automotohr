<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('manage_employer/settings_left_menu_reporting'); ?><!-- main/employer_column_left_view -->
                </div>  
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php echo $title; ?> </span>
                        </div>
                        <div class="box-wrapper">
                            <div class="row">
                                <div class="applicant-reg-date">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="row">
                                            <form id="form-filters" method="post" enctype="multipart/form-data" action="">
                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                    <div class="form-col-100">
                                                        <label for="username">Username</label>
                                                        <input type="text" id="username" class="invoice-fields" name="username" value="<?php echo $this->uri->segment(2) != 'all' ? urldecode($this->uri->segment(2)) : ''; ?>">
                                                        <input type="hidden" id="search_sid" name="search_sid" value="<?php echo $this->uri->segment(3) != 0 ? urldecode($this->uri->segment(3)) : 0; ?>">
                                                        <div class="video-link" style='font-style: italic;'><b>Hint: </b> Type in the name and system will give suggestion. </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                    <div class="form-col-100">
                                                        <label>Order Placed By</label>
                                                        <?php $order_by = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : ''; ?>
                                                        <select class="invoice-fields" id="order_by" name="order_by">
                                                            <option value="all">All</option>
                                                            <?php foreach ($company_accounts as $account) { ?>
                                                                <option value="<?php echo $account["sid"] ?>" <?= $order_by == $account["sid"] ? 'selected' : '';?>>
                                                                    <?php if ($employer_id == $account["sid"]) { ?>
                                                                        You
                                                                    <?php } else {
                                                                        if($account["is_executive_admin"] == 1) {
                                                                            $employee_type = "Executive Admin";
                                                                        } else {
                                                                            $employee_type = $account["access_level"];
                                                                        }
                                                                        echo $account["first_name"]."&nbsp;".$account["last_name"].' ('.$employee_type.')';
                                                                    } ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                        <div class="video-link" style='font-style: italic;'><b>Hint: </b> Employee name who executed the report</div>
    <!--                                                    <label for="keyword">Order By</label>-->
    <!--                                                    <input type="text" id="order_by" class="invoice-fields" name="order_by" value="--><?php //echo $this->uri->segment(3) != 'all' ? urldecode($this->uri->segment(3)) : ''; ?><!--">-->
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 margin-top">
                                                    <div class="form-col-100">
                                                        <label>Status</label>
                                                        <?php $statuses = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : 'all';
                                                        $statuses = explode(',',$statuses); ?>
                                                        <div class="hr-select-dropdown ">
                                                            <select class="chosen-select" multiple="multiple" name="status[]" id="status" >
                                                                <option value="all" <?= in_array('all',$statuses) ? 'selected' : '';?>>All</option>
                                                                <option value="draft" <?= in_array('draft',$statuses) ? 'selected' : '';?>>Awaiting Candidate Input</option>
                                                                <option value="pending" <?= in_array('pending',$statuses) ? 'selected' : '';?>>Pending</option>
                                                                <option value="cancelled" <?= in_array('cancelled',$statuses) ? 'selected' : '';?>>Cancelled </option>
                                                                <option value="completed" <?= in_array('completed',$statuses) ? 'selected' : '';?>>Completed</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 margin-top">
                                                    <div class="form-col-100">
                                                        <label>Product Type</label>
                                                        <?php $type = $this->uri->segment(6) != 'all' ? urldecode($this->uri->segment(6)) : 'all'; ?>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" name="product_type" id="product_type">
                                                                <option value="all" <?= $type == 'all' ? 'selected' : '';?>>All</option>
                                                                <option value="background-checks" <?= $type == 'background-checks' ? 'selected' : '';?>>Background Checks</option>
                                                                <option value="drug-testing" <?= $type == 'drug-testing' ? 'selected' : '';?>>Drug Testing</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 margin-top">
                                                    <div class="form-col-100">
                                                        <?php $startdate = $this->uri->segment(7) != 'all' && $this->uri->segment(7) != '' ? urldecode($this->uri->segment(7)) : date('m-01-Y');?>
                                                        <label for="startdate">Start Date</label>
                                                        <input type="text" id="startdate" class="invoice-fields" name="startdate" placeholder="Start Date" readonly="" value="<?php echo set_value('startdate', $startdate);?>">
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 margin-top">
                                                    <div class="form-col-100">
                                                        <?php $end_date = $this->uri->segment(8) != 'all' && $this->uri->segment(8) != '' ? urldecode($this->uri->segment(8)) : date('m-t-Y');?>
                                                        <label for="enddate">End Date</label>
                                                        <input type="text" id="enddate" class="invoice-fields" name="enddate" placeholder="End Date" readonly="" value="<?php echo set_value('enddate', $end_date);?>">
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 pull-right">
                                                    <div class="report-btns">
                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                <button class="form-btn" id="btn_apply_filters" onclick="fApplyDateFilters();">Filter</button>
                                                            </div>
                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                <a class="form-btn" href="<?= base_url('accurate_background');?>">Clear</a>
                                                                <!--                                                                        <button class="form-btn" onclick="fClearDateFilters();">Clear</button>-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="table-responsive table-outer">
                            <?php if ($is_check_active == 1) { ?>
                                <strong id="invoiceCount">Placed Orders: <?= $checks_count; //count($checks);    ?></strong>
                                <div class="col-xs-12 col-sm-12">
                                    <?php echo $links; ?>
                                </div>
                                <div class="table-responsive table-outer">
                                    <table class="table table-bordered table-stripped table-hover " id="example"  data-order='[[ 0, "desc" ]]'>
                                        <thead>
                                            <tr>
                                                <th><a>Ordered By</a></th>
                                                <th><a>Username</a></th>
                                                <th><a>User Type</a></th>
                                                <th><a>Product Name</a></th>
                                                <th><a>Product Type</a></th>
                                                <th><a>Date Applied</a></th>
                                                <th>Status</th>
                                                <th><a>Action</a></th>
                                            </tr> 
                                        </thead>
                                        <?php if(sizeof($checks) > 0){ ?>
                                            <tbody>
                                                <?php foreach ($checks as $key => $value) {
                                                    $status_color = '';

                                                    if($value['status'] == 'DRAFT'){
                                                        $status_color = 'style="color: #FF0000"';
                                                    } elseif(ucwords($value['status']) == 'Pending'){
                                                        $status_color = 'style="color: #0000FF";';
                                                    } elseif(ucwords($value['status']) == 'COMPLETED'){
                                                        $status_color = 'style="color: #006400";';
                                                    } elseif(ucwords($value['status']) == 'Cancelled'){
                                                        $status_color = 'style="color: #FF8C00";';
                                                    } ?>
                                                    <tr>
                                                        <td><?php echo $value['first_name'] ?> <?php echo $value['last_name'] ?></td>
                                                        <td><?php echo $value['user_first_name'] ?></td>
                                                        <td><?php echo ucfirst($value['users_type']) ?></td>
                                                        <td><?php echo $value['product_name'] ?></td>
                                                        <td><?php echo $value['product_type'] ?></td>
                                                        <td><?php echo date_with_time($value['date_applied']) ?></td>
                                                        <td <?php echo $status_color;?>>
                                                            <?php   if($value['status'] == 'DRAFT') {
                                                                        echo 'AWAITING CANDIDATE INPUT';
                                                                    } else if($value['status'] == NULL || $value['status'] == '') {
                                                                        echo 'PENDING';
                                                                    } else {
                                                                        echo ucwords($value['status']);
                                                                    }
                                                            //echo ($value['status'] == 'DRAFT' || $value['status'] == '') ? 'AWAITING CANDIDATE INPUT' : ucwords($value['status']); ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($value['product_type'] == 'background-checks') { ?>
                                                                <?php if ($value['users_type'] == 'employee') { ?>
                                                                    <a class="hr-edit-btn" href="<?php echo base_url(); ?>background_check/employee/<?php echo $value['users_sid'] ?>" >View Report</a>
                                                                <?php } else { ?>
                                                                    <a class="hr-edit-btn" href="<?php echo base_url(); ?>background_check/applicant/<?php echo $value['users_sid'] ?>" >View Report</a>
                                                                <?php } ?>
                                                            <?php } else if ($value['product_type'] == 'drug-testing') { ?>
                                                                <?php if ($value['users_type'] == 'employee') { ?>
                                                                    <a class="hr-edit-btn" href="<?php echo base_url(); ?>drug_test/employee/<?php echo $value['users_sid'] ?>" >View Report</a>
                                                                <?php } else { ?>
                                                                    <a class="hr-edit-btn" href="<?php echo base_url(); ?>drug_test/applicant/<?php echo $value['users_sid'] ?>" >View Report</a>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            <!--<a class="hr-edit-btn" href="<?php echo base_url(); ?>background_report/<?php echo $value['order_sid'] ?>" >View Report</a>-->
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        <?php } else{ ?>
                                            <tbody>
                                                <tr><td colspan="8" class="text-center">No Background Checks Found</td></tr>
                                            </tbody>
                                        <?php } ?>
                                    </table>
                                </div>
                            <?php } else { ?>
                                <div class="table-wrp data-table product-detail-area mylistings-wrp text-center">
                                    <p>Background checks not activated.</p>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if ($is_check_active == 1) { ?>
                        <div class="col-xs-12 col-sm-12">
                            <?php echo $links; ?>
                        </div>
                    <?php } ?>
                </div>          
            </div>
        </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/dataTables.bootstrap.min.css">

<script>
    var applicantXHR = null;
    function fApplyDateFilters(){
        var startDate = $('#startdate').val();
        var endDate = $('#enddate').val();
        var status = $('#status').val();
        var product_type = $('#product_type').val();
        var username = $('#username').val();
        var search_sid = $('#search_sid').val();
        var order_by = $('#order_by').val();
        url = '<?php echo base_url();?>' + 'accurate_background/';

        if(username != ''){
            url += encodeURI(username)+ '/';
        } else {
            url += encodeURI('all/');
        }

        if(search_sid != 0){
            url += encodeURI(search_sid)+ '/';
        } else {
            url += encodeURI('0/');
        }

        if(order_by != '' && order_by != null){
            url += encodeURI(order_by)+ '/';
        } else {
            url += encodeURI('all/');
        }

        if(status != '' && status != null){
            url += encodeURIComponent(status)+ '/';
        } else {
            url += encodeURI('all/');
        }

        if(product_type != ''){
            url += encodeURI(product_type)+ '/';
        } else {
            url += encodeURI('all/');
        }

        if(startDate != '' && endDate == ''){
            url += encodeURIComponent(startDate) + '/all/';
        }

        if(endDate != '' && startDate == ''){
            url += 'all/' + encodeURIComponent(endDate) + '/';
        }

        if((startDate != '') && (endDate != '')){
            url += encodeURIComponent(startDate) + '/' + encodeURIComponent(endDate) + '/';
        }
        
        $('#form-filters').attr('action', url);
        $('#form-filters').submit();
    }

    $('#username').autocomplete({
        source: get_applicant,
        minLength: 2,
        select: function(e, ui){
            $('#search_sid').val(ui.item.id);
        }
    });

    $(document).on('keydown','#username',function(e){
        if(e.keyCode == 8){
            $('#search_sid').val(0);
        }
    });

    function get_applicant(req, res) {
        if(applicantXHR !== null) applicantXHR.abort();

        applicantXHR = $.get("<?= base_url();?>background-check/get-applicant/"+ req.term +"", function(resp){
            if(!resp) return false;
            res(resp);
            applicantXHR = null;
        });
    }

    $(document).ready(function () {
        $('#btn_apply_filters').on('click', function(e){
            e.preventDefault();
//            generate_search_url();
            fApplyDateFilters();
//            window.location = $(this).attr('href').toString();
        });
        
        $("#startdate").datepicker({
            dateFormat: 'mm-dd-yy',
            yearRange: "1960:+0",
            onSelect: function (selected) {
                var dt = $.datepicker.parseDate("mm-dd-yy", selected);
                dt.setDate(dt.getDate() + 1);
                $("#enddate").datepicker("option", "minDate", dt);
            }
        }).on('focusin', function () {
            $(this).prop('readonly', true);
        }).on('focusout', function () {
            $(this).prop('readonly', false);
        });

        $('.chosen-select').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: true,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        $("#enddate").datepicker({
            dateFormat: 'mm-dd-yy',
            setDate: new Date(),
            onSelect: function (selected) {
                var dt = $.datepicker.parseDate("mm-dd-yy", selected);
                dt.setDate(dt.getDate() - 1);
                $("#startdate").datepicker("option", "maxDate", dt);
            }
        }).on('focusin', function () {
            $(this).prop('readonly', true);
        }).on('focusout', function () {
            $(this).prop('readonly', false);
        });
    });
</script>
