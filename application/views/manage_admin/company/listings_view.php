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
                                    <div class="hr-search-criteria <?= $flag ? 'opened' : "" ?>">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main" <?= $flag ? "style='display:block'" : "" ?>>
                                        <form method="GET" action="">
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <?php $ContactName = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : ''; ?>
                                                    <label>Contact Name:</label>
                                                    <input type="text" name="ContactName" id="ContactName" value="<?= $ContactName; ?>"  class="invoice-fields">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <?php $CompanyName = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : ''; ?>
                                                    <label>Company Name:</label>
                                                    <input type="text" name="CompanyName" id="CompanyName" value="<?= $CompanyName; ?>"  class="invoice-fields">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <label>Company Type:</label>
                                                            <div class="hr-select-dropdown">
                                                                <?php $is_paid = $this->uri->segment(6) != 'all' ? urldecode($this->uri->segment(6)) : ''; ?>
                                                                <select class="invoice-fields" name="is_paid" id="is_paid">
                                                                    <option value="1">Main List</option>
                                                                    <option value="0" <?php echo $is_paid == '0' ? 'selected="selected"' : ''; ?>>Secondary List</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <label>Company Status:</label>
                                                            <div class="hr-select-dropdown">
                                                                <?php $active = $this->uri->segment(7) != 'all' ? urldecode($this->uri->segment(7)) : ''; ?>
                                                                <select class="invoice-fields" name="active" id="active">
                                                                    <option value="all">All</option>
                                                                    <option value="1" <?php echo $active == '1' ? 'selected="selected"' : ''; ?>>Active</option>
                                                                    <option value="0" <?php echo $active == '0' ? 'selected="selected"' : ''; ?>>In-Active</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 field-row">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <?php $start_date = $this->uri->segment(8) != 'all' && $this->uri->segment(8) != '' ? urldecode($this->uri->segment(8)) : ''; ?>
                                                            <label>Registration Date From:</label>
                                                            <input type="text" name="start" value="<?= $start_date; ?>" class="invoice-fields" id="startdate" readonly>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <?php $end_date = $this->uri->segment(9) != 'all' && $this->uri->segment(9) != '' ? urldecode($this->uri->segment(9)) : ''; ?>
                                                            <label>Registration Date To:</label>
                                                            <input type="text" name="end" value="<?= $end_date; ?>" class="invoice-fields" id="enddate" readonly>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
<!--                                                    <input type="submit" class="btn btn-success" value="Search">-->
                                                    <a href="javascript:;" id="btn_apply_filters" class="btn btn-success">Search</a>
                                                    <a href="<?php echo base_url('manage_admin/companies'); ?>" class="btn btn-success">Clear</a>
                                                </div>
                                            
                                        </form>
                                    </div>

                                    <form name="users_form" method="post">
                                        <div class="hr-box-header">
                                            <div class="hr-items-count">
                                                <strong><?php echo $total; ?></strong> Companies
                                            </div>
                                            <?php if(check_access_permissions_for_view($security_details, 'show_company_multiple_actions')){ ?>
                                                    <?php $this->load->view('templates/_parts/admin_manage_multiple_actions'); ?>
                                            <?php } ?> 
                                            <?php echo $links; ?>
                                        </div>
                                    </form>
                                    <div class="table-responsive table-outer">
                                        <div class="hr-displayResultsTable">
                                            <form name="multiple_actions" id="multiple_actions_company" method="POST">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th><input type="checkbox" id="check_all"></th>
                                                            <th>ID</th>
                                                            <th>Contact Name</th>
                                                            <th>Company Name</th>
                                                            <th>Phone Number</th>
                                                            <th>Registration Date</th>
                                                            <th>Expiry Date</th>
                                                            <th>Status</th>
                                                            <?php if(check_access_permissions_for_view($security_details, 'edit_company')){ ?>
                                                                <th class="text-center" colspan="4">Actions</th>
                                                            <?php } ?>
                                                        </tr> 
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($companies as $key => $value) { ?>
                                                            <tr>
                                                                <td><input type="checkbox" name="checkit[]" value="<?php echo $value['sid']; ?>" class="my_checkbox"></td>
                                                                <td><a><b><?php echo $value['sid']; ?></b></a></td>
                                                                <td><a><?php echo $value['ContactName']; ?></a></td>
                                                                <td><?php echo $value['CompanyName']; ?></td>
                                                                <td><?=phonenumber_format($value['PhoneNumber']); ?></td>
                                                                <td><?php echo date_with_time($value['registration_date']); ?></td>
                                                                <td><?php   if (!empty($value['expiry_date'])) {
                                                                                echo date_with_time($value['expiry_date']);
                                                                            } else { 
                                                                                echo 'Not Set';
                                                                            } ?>
                                                                </td>
                                                                <td>
                                                        <?php       if ($value['active'] == 1) {  ?>
                                                                        <span style="color:green;">Active</span>
                                                        <?php       } else { ?>
                                                                        <span style="color:red;">In-Active</span>
                                                        <?php       } ?>
                                                                </td>
                                                                <?php if(check_access_permissions_for_view($security_details, 'edit_company')){ ?>
                                                                    <td>
                                                                        <a class="hr-edit-btn" href="<?php echo base_url('manage_admin/companies/manage_company') . '/' . $value['sid']; ?>">Manage</a>
                                                                    </td>
                                                                <?php } ?>
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
                                                <strong><?php echo $total; ?></strong> Companies
                                            </div>
                                            <?php //$this->load->view('templates/_parts/admin_manage_multiple_actions');    ?>
                                            <!-- Pagination Start -->
                                            <?php echo $links; ?>
                                            <!-- Pagination End -->
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
<script type="text/javascript">
    function generate_search_url() {
        var ContactName = $("#ContactName").val();
        var CompanyName = $("#CompanyName").val();
        var is_paid = $("#is_paid").val();
        var active = $('#active').val();
        var startdate = $('#startdate').val();
        var enddate = $('#enddate').val();

        ContactName = ContactName != '' && ContactName != null && ContactName != undefined && ContactName != 0 ? encodeURIComponent(ContactName) : 'all';
        CompanyName = CompanyName != '' && CompanyName != null && CompanyName != undefined && CompanyName != 0 ? encodeURIComponent(CompanyName) : 'all';
        is_paid = is_paid != '' && is_paid != null && is_paid != undefined ? encodeURIComponent(is_paid) : 1;
        active = active != '' && active != null && active != undefined ? encodeURIComponent(active) : 1;
        startdate = startdate != '' && startdate != null && startdate != undefined && startdate != 0 ? encodeURIComponent(startdate) : 'all';
        enddate = enddate != '' && enddate != null && enddate != undefined && enddate != 0 ? encodeURIComponent(enddate) : 'all';

        var url = '<?php echo base_url('manage_admin/companies/search_company'); ?>' + '/' + ContactName + '/' + CompanyName + '/' + is_paid + '/' + active + '/' + startdate + '/' + enddate;

        $('#btn_apply_filters').attr('href', url);
    }
    $(document).ready(function(){
        $('#btn_apply_filters').on('click', function(e){
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });
        $('input').on('keyup', function () {
            generate_search_url();
        });
        $("#is_paid").change(function () {
            generate_search_url();
        });
        $("#active").change(function () {
            generate_search_url();
        });
        $('#startdate').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function (value) { //console.log(value);
                $('#enddate').datepicker('option', 'minDate', value);
                generate_search_url();
            }
        }).datepicker('option', 'maxDate', $('#enddate').val());

        $('#enddate').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function (value) { //console.log(value);
                $('#startdate').datepicker('option', 'maxDate', value);
                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#startdate').val());
    });
    function sendActivatinEmail(company_id) {
        url = "<?= base_url() ?>manage_admin/companies/send_company_activation_email";
        alertify.confirm('Confirmation', "Are you sure you want to send activation email to this Company?",
                function () {
                    $.post(url, {action: 'email', sid: company_id})
                            .done(function (data) {
                                console.log(data);
                                alertify.success('Activation email sent.');
                            });
                },
                function () {
                    alertify.error('Canceled');
                });
    }
</script>