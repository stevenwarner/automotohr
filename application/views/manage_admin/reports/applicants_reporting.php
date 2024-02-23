<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="hr-search-criteria <?php echo $flag == true ? 'opened' : ''; ?>">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main" <?php echo $flag == true ? 'style="display: block;"' : '' ?>>
                                        <form method="GET" action="<?php echo base_url('manage_admin/reports/applicants_report'); ?>" name="search" id="search">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                    <div class="field-row" id="company_div">
                                                        <label class="text-left">Company</label>
                                                        <?php
                                                        $selectedCompanies = [];
                                                        $selectedCompanies = explode(',', urldecode($this->uri->segment(4)));
                                                        ?>
                                                        <div class="hr-select-dropdown">
                                                            <select name="company_sid" id="company_sid" multiple="multiple">
                                                                <option value="all" <?php if (in_array('all', $selectedCompanies)) { ?> selected="selected" <?php } ?>>All</option>
                                                                <?php if (!empty($companies)) {

                                                                ?>
                                                                    <?php foreach ($companies as $active_company) { ?>
                                                                        <option <?php if (in_array($active_company['sid'], $selectedCompanies)) { ?> selected="selected" <?php } ?> value="<?php echo $active_company['sid']; ?>">
                                                                            <?php echo $active_company['CompanyName']; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                    <div class="field-row">
                                                        <?php $keyword = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : ''; ?>
                                                        <label>Keyword</label>
                                                        <input placeholder="John Doe" class="invoice-fields" type="text" id="keyword" name="keyword" value="<?php echo set_value('keyword', $keyword); ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                    <div class="field-row">
                                                        <label class="">&nbsp;</label>
                                                        <a id="btn_apply_filters" class="btn btn-success btn-block" href="#">Apply Filters</a>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                    <div class="field-row">
                                                        <label class="">&nbsp;</label>
                                                        <a id="btn_reset_filters" class="btn btn-success btn-block" href="<?php echo base_url('manage_admin/reports/applicantsReporting'); ?>">Reset Filters</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- *** table *** -->
                                    <?php if (isset($applicants) && sizeof($applicants) > 0) { ?>
                                        <div class="col-xs-12 col-sm-12 margin-top">
                                            <div class="row">
                                                <div class="bt-panel">
                                                    <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');">
                                                        <i class="fa fa-print" aria-hidden="true"></i>
                                                        Print
                                                    </a>
                                                    <form method="post" id="export" name="export">
                                                        <input type="hidden" name="submit" value="Export" />
                                                        <button class="btn btn-success" type="submit">
                                                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                                            Export To Excel
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Applicants Report</h1>
                                            </span>
                                            <span class="pull-right">
                                                <h1 class="hr-registered">Total Records Found : <?php echo $applicants_count; ?></h1>
                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-left">
                                                        <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $applicants_count ?></p>
                                                    </span>
                                                    <span class="pull-right">
                                                        <?php echo $page_links ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="table-responsive" id="print_div">
                                                        <table class="table table-bordered" id="example">
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th>Company</th>
                                                                    <th>Applied Jobs</th>
                                                                    <th>Last Applied</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (isset($applicants) && sizeof($applicants) > 0) {

                                                                ?>
                                                                    <?php foreach ($applicants as $applicant) { ?>
                                                                        <tr>
                                                                            <td><strong>Name:</strong> <?php echo $applicant['first_name'] . ' ' . $applicant['last_name'] ?><br>
                                                                                <strong>Email: </strong><?php echo $applicant['email']; ?><br>
                                                                                <strong>Phone Number: </strong><?php echo $applicant['phone_number']; ?><br>
                                                                                <strong>Address: </strong><?php echo $applicant['address']; ?>
                                                                            </td>
                                                                            <td><?php echo $applicant['CompanyName']; ?></td>
                                                                            <td>

                                                                                <button class="btn btn-warning csF16 jsJobsView" data-title="<?php echo $applicant['first_name'] . ' ' . $applicant['last_name'] ?>" data-applicant-id="<?php echo $applicant['applicant_sid'] ?>">
                                                                                    <strong><?php echo $applicant['total_jobs_applied']; ?></strong>
                                                                                    &nbsp;view Detail
                                                                                </button>
                                                                            </td>
                                                                            <td><?php echo date_with_time($applicant['date_applied']); ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td class="text-center" colspan="4">
                                                                            <?php if (isset($applicants) && sizeof($applicants) <= 0) { ?>
                                                                                <div class="no-data">No applicants found.</div>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-left">
                                                        <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $applicants_count ?></p>
                                                    </span>
                                                    <span class="pull-right">
                                                        <?php echo $page_links ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (isset($applicants) && sizeof($applicants) > 0) { ?>
                                        <div class="col-xs-12 col-sm-12 margin-top">
                                            <div class="row">
                                                <div class="bt-panel">
                                                    <a href="javascript:;" class="btn btn-success" onclick="print_page('#print_div');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                                                    <form method="post" id="export" name="export">
                                                        <input type="hidden" name="submit" value="Export" />
                                                        <button class="btn btn-success" type="submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To Excel</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!-- *** table *** -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<link rel="stylesheet" href="<?= base_url("assets/v1/plugins/ms_modal/main.css"); ?>">
<script src="<?= base_url("assets/v1/plugins/ms_modal/main.js"); ?>"></script>

<script type="text/javascript">
    $(document).keypress(function(e) {
        if (e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });
    $(document).ready(function() {
        var company_selectize = $('#company_sid').selectize({
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



        $('#btn_apply_filters').on('click', function(e) {
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });

        generate_search_url();

    });


    function generate_search_url() {
        var company_sid = $("#company_sid").val();
        var keyword = $('#keyword').val();
        company_sid = company_sid != '' && company_sid != null && company_sid != undefined && company_sid != 0 ? encodeURIComponent(company_sid) : 'all';
        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';
        var url = '<?php echo base_url('manage_admin/reports/applicantsReporting'); ?>' + '/' + company_sid + '/' + keyword + '/';
        $('#btn_apply_filters').attr('href', url);
    }



    function print_page(elem) {
        $("table").removeClass("horizontal-scroll");

        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>' + '<?php echo $page_title; ?>' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome-animation.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/responsive.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/jquery-ui.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery.datetimepicker.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/images/favi-icon.png'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/alertify.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/themes/default.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/select2.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/chosen.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/chosen.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();

        $("table").addClass("horizontal-scroll");
    }


    $(function benefits() {


        $(document).on("click", ".jsJobsView", function(event) {
            //
            event.preventDefault();
            //
            var appicantSid = $(this).attr('data-applicant-id');
            var title = $(this).attr('data-title');
            //
            Modal({
                    Id: "jsJobsViewModal",
                    Loader: "jsJobsViewModalLoader",
                    Body: '<div id="jsJobsViewModalBody"></div>',
                    Title: title,
                },
                function() {
                    loadjsJobsViewView(appicantSid);

                }
            );
        });


        //
        function loadjsJobsViewView(id) {
            //
            baseurl = '<?php echo base_url("manage_admin/reports/applicants_reporting/viewJobs/") ?>' + id;
            $.ajax({
                    url: baseurl,
                    method: "GET",
                    cache: false,
                })
                .success(function(resp) {
                    $("#jsJobsViewModalBody").html(resp.view);
                    ml(false, "jsJobsViewModalLoader");

                })
                .fail(handleErrorResponse)
                .always(function() {
                    ml(false, "jsJobsViewModalLoader");
                });
        }

    });
</script>