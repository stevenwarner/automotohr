<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="row">
                                        <div class="applicant-reg-date">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="row">
                                                    <div class="form-wrp">
                                                        <form id="form-filters" method="post" enctype="multipart/form-data" action="">
                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="username">Username</label>
                                                                    <input type="text" id="username" class="invoice-fields" name="username" value="<?php echo $this->uri->segment(3) != 'all' ? urldecode($this->uri->segment(3)) : ''; ?>">
                                                                    <input type="hidden" id="search_sid" name="search_sid" value="<?php echo $this->uri->segment(4) != 0 ? urldecode($this->uri->segment(4)) : 0; ?>">
                                                                    <div class="hint-text text-muted"><b>Hint: </b> Type in the name and system will give suggestion. </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                <div class="form-group">
                                                                    <label>Status</label>
                                                                    <?php $statuses = $this->uri->segment(6) != 'all' ? urldecode($this->uri->segment(6)) : 'all';
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

                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ">
                                                                <div class="form-group">
                                                                    <label for="enddate">Company Name</label>
                                                                    <?php $order_by = $this->uri->segment(10) != 'all' ? urldecode($this->uri->segment(10)) : ''; ?>
                                                                    <select class="invoice-fields" id="company_name" name="company_name">
                                                                        <option value="all">All</option>
                                                                        <?php foreach ($companies as $account) { ?>
                                                                            <option value="<?php echo $account["sid"] ?>" <?= $order_by == $account["sid"] ? 'selected' : '';?>>
                                                                                <?php echo ucwords($account["CompanyName"]);?>
                                                                            </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ">
                                                                <div class="form-group">
                                                                    <label>Order Placed By</label>
                                                                    <?php $order_by = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : ''; ?>
                                                                    <select class="invoice-fields" id="order_by" name="order_by">
                                                                        <option value="all">All</option>
                                                                    </select>
                                                                    <div class="hint-text text-muted"><b>Hint: </b> Employee name who executed the report. (Select Company)</div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                <div class="form-group">
                                                                    <?php $startdate = $this->uri->segment(8) != 'all' && $this->uri->segment(8) != '' ? urldecode($this->uri->segment(8)) : date('m-01-Y');?>
                                                                    <label for="startdate">Start Date</label>
                                                                    <input type="text" id="startdate" class="invoice-fields" name="startdate" placeholder="Start Date" readonly="" value="<?php echo set_value('startdate', $startdate);?>">
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                <div class="form-group">
                                                                    <?php $end_date = $this->uri->segment(9) != 'all' && $this->uri->segment(9) != '' ? urldecode($this->uri->segment(9)) : date('m-t-Y');?>
                                                                    <label for="enddate">End Date</label>
                                                                    <input type="text" id="enddate" class="invoice-fields" name="enddate" placeholder="End Date" readonly="" value="<?php echo set_value('enddate', $end_date);?>">
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ">
                                                                <div class="form-group">
                                                                    <label>Product Type</label>
                                                                    <?php $type = $this->uri->segment(7) != 'all' ? urldecode($this->uri->segment(7)) : 'all'; ?>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields" name="product_type" id="product_type">
                                                                            <option value="all" <?= $type == 'all' ? 'selected' : '';?>>All</option>
                                                                            <option value="background-checks" <?= $type == 'background-checks' ? 'selected' : '';?>>Background Checks</option>
                                                                            <option value="drug-testing" <?= $type == 'drug-testing' ? 'selected' : '';?>>Drug Testing</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 ">
                                                                <div class="form-group pull-right">
                                                                    <label class="transparent-label">test</label>
                                                                    <div class="row">
                                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                            <button class="btn btn-success btn-block" id="btn_apply_filters">Filter</button>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                            <a class="btn btn-success btn-block" href="<?= base_url('manage_admin/accurate_background');?>">Clear</a>
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
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <form name="users_form" method="post">
                                                <div class="hr-box-header">
                                                    <div class="hr-items-count">
                                                        <strong>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> Orders out of <?php echo $checks_count?></strong>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12">
                                                        <?php echo $links; ?>
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="table-responsive table-outer">
                                                <div class="hr-displayResultsTable">
                                                    <form name="multiple_actions" id="multiple_actions_company" method="POST">
                                                        <table class="table table-bordered table-striped table-hover">
                                                            <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Ordered By</th>
                                                                <th>Candidate</th>
                                                                <th>Type</th>
                                                                <th>Product Name</th>
                                                                <th>Company Name</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <!--All records-->
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
                                                                    <td><?php echo convert_date_to_frontend_format($value['date_applied']) ?></td>
                                                                    <td><?php echo $value['first_name'] ?> <?php echo $value['last_name'] ?></td>
                                                                    <td><?php echo $value['user_first_name'] ?></td>
                                                                    <td><?php echo ucfirst($value['users_type']) ?></td>
                                                                    <td><?php echo $value['product_name'] ?></td>
                                                                    <td><?php echo ucwords($value['cname']) ?></td>
                                                                    <td <?php echo $status_color;?>><?php echo ($value['status'] == 'DRAFT') ? 'AWAITING CANDIDATE INPUT' : ucwords($value['status']); ?></td>
                                                                    <td><a class="btn btn-success btn-sm" href="<?php echo base_url(); ?>manage_admin/accurate_background/order_status/<?php echo $value['order_sid'] ?>" >Order Status</a></td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                        <input type="hidden" name="execute" value="multiple_action">
                                                        <input type="hidden" id="type" name="type" value="company">
                                                    </form>
                                                </div>
                                            </div>
                                            <form name="users_form" method="post">
                                                <div class="hr-box-header hr-box-footer">
                                                    <div class="hr-items-count">
                                                        <strong>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> Orders out of <?php echo $checks_count?></strong>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12">
                                                        <?php echo $links; ?>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var applicantXHR = null;
    function generate_search_url() {
        var startDate = $('#startdate').val();
        var endDate = $('#enddate').val();
        var status = $('#status').val();
        var product_type = $('#product_type').val();
        var username = $('#username').val();
        var search_sid = $('#search_sid').val();
        var order_by = $('#order_by').val();
        var company_name = $('#company_name').val();

        username = username != '' && username != null && username != undefined && username != 0 ? encodeURIComponent(username) : 'all';
        search_sid = search_sid != '' && search_sid != null && search_sid != undefined && search_sid != 0 ? encodeURIComponent(search_sid) : 'all';
        order_by = order_by != '' && order_by != null && order_by != undefined && order_by != 0 ? encodeURIComponent(order_by) : 'all';
        status = status != '' && status != null && status != undefined && status != 0 ? encodeURIComponent(status) : 'all';
        product_type = product_type != '' && product_type != null && product_type != undefined && product_type != 0 ? encodeURIComponent(product_type) : 'all';
        startDate = startDate != '' && startDate != null && startDate != undefined && startDate != 0 ? encodeURIComponent(startDate) : 'all';
        endDate = endDate != '' && endDate != null && endDate != undefined && endDate != 0 ? encodeURIComponent(endDate) : 'all';
        company_name = company_name != '' && company_name != null && company_name != undefined && company_name != 0 ? encodeURIComponent(company_name) : 'all';

        var url = '<?php echo base_url('manage/accurate_background'); ?>' + '/' + username + '/' + search_sid + '/' + order_by + '/' + status + '/' + product_type + '/' + startDate + '/' + endDate + '/' + company_name + '/';

        $('#btn_apply_filters').attr('href', url);
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

    $('body').on('change', '#company_name', function(){
        var selected = $(this).val();
        if(selected != 'all'){

            var my_data = {'company_sid': selected, 'perform_action': 'fetch_employee'};
            var myRequest = $.ajax({
                type: "POST",
                url: "<?php echo base_url('manage_admin/accurate_background/ajax_responder'); ?>",
                data: my_data
            });

            myRequest.done(function (response) {
                var options = '<option value="all">All</option>';
                $.each(response,function(index,object){
                    var temp = '<option value="'+object.id+'">'+object.value+'</option>';
                    options = options + temp;
                });
                $('#order_by').html(options);
            });
        }else{
            var options = '<option value="all">All</option>';
            $('#order_by').html(options);
        }
    });

    function get_applicant(req, res) {
        if(applicantXHR !== null) applicantXHR.abort();

        applicantXHR = $.get("<?= base_url();?>manage_admin/background-check/get-applicant/"+ req.term +"", function(resp){
            if(!resp) return false;
            res(resp);
            applicantXHR = null;
        });
    }

    $(document).ready(function () {
        $('#company_name').trigger('change');
        var order_by = '<?= urldecode($this->uri->segment(5)); ?>';
        setTimeout(function(){
            $('#order_by').val(order_by);
        },2000);
        console.log(order_by);

        $('#btn_apply_filters').on('click', function(e){
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });

        $("#startdate").datepicker({
            dateFormat: 'mm-dd-yy',
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