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
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $title; ?></h1>
                                    </div>
                                    <br />
                                    <br />
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="hr-search-criteria opened">
                                                <strong>Click to modify search criteria</strong>
                                            </div>
                                            <div class="hr-search-main search-collapse-area" style="display: block;">
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <?php $company_sid = $this->uri->segment(4); ?>
                                                        <div class="field-row">
                                                            <label>Company</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" id="company_sid" name="company_sid">
                                                                    <option value="all">All Companies</option>
                                                                    <?php foreach ($companies as $company) { ?>
                                                                        <option <?php echo $company_sid == $company['sid'] ? 'selected="selected"' : ''; ?> value="<?php echo $company['sid'] ?>"><?php echo ucwords($company['CompanyName']); ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-xs-2">
                                                        <div class="field-row">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-success btn-block btn-equalizer" onclick="func_apply_filters();">Search</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="hr-box">
                                                <div class="hr-innerpadding">
                                                    <?php if (!empty($links)) { ?>
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <?php echo $links; ?>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                    <?php } ?>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover table-striped table-condensed">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="col-xs-4">Company Name</th>
                                                                            <th class="col-xs-2">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <thead>
                                                                        <?php if (!empty($company_records)) { ?>
                                                                            <?php foreach ($company_records as $record) { ?>
                                                                                <tr>

                                                                                    <td><?php echo $record['CompanyName']; ?></td>

                                                                                    <td>
                                                                                        <a class="btn btn-success btn-sm btn-block" title="View" href="javascript:;" onclick="getcompanydata('<?php echo $record['sid']; ?>','<?php echo $record['CompanyName']; ?>')">View</a>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        <?php } else { ?>
                                                                            <tr>
                                                                                <td colspan="7" class="text-center">
                                                                                    <span class="no-data">No Records</span>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if (!empty($links)) { ?>
                                                        <hr />
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <?php echo $links; ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
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
    </div>
</div>


<div id="bulk_email_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Company Name </h4>
            </div>
            <div class="modal-body">
                <div class="compose-message">
                    <div class="universal-form-style-v2">

                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>


<!-- Loader Start -->
<div id="document_loader" class="text-center my_loader" style="display: none; z-index: 1234;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i aria-hidden="true" class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" id="loader_text_div" style="display:block; margin-top: 35px;"></div>
    </div>
</div>
<!-- Loader End -->


<script>
    $(document).ready(function() {
        $("#company_sid").val('<?php echo $companySid; ?>');
    });

    function getcompanydata(companyid,companyname) {
        $('#loader_text_div').text('Processing');
        $('#document_loader').show();

        $.ajax({
            'url': '<?php echo base_url('manage_admin/2022/reports/complynet/getcompanyemployees/'); ?>' + companyid,
            'type': 'GET',

            success: function(urls) {
            
                $('.modal-body').html(urls);
                $('#document_modal .modal-footer').html('footer_content');
                $('.modal-title').html('Company Name: '+companyname);
                $('#bulk_email_modal').modal("toggle");
                $('#loader_text_div').text('');
                $('#document_loader').hide();

            }
        });


    }



    function func_apply_filters() {
        var company_sid = $('#company_sid').val();
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        var base_url = '<?php echo base_url('manage_admin/2022/reports/complynet'); ?>';

        company_sid = company_sid == '' || company_sid == undefined || company_sid == null ? 'all' : encodeURIComponent(company_sid);
        date_start = date_start == '' || date_start == undefined || date_start == null ? 'all' : encodeURIComponent(date_start);
        date_end = date_end == '' || date_end == undefined || date_end == null ? 'all' : encodeURIComponent(date_end);

        var url = base_url + '/' + company_sid + '/' + date_start + '/' + date_end;

        window.location = url;
    }
</script>


<style>
    .modal-backdrop {
        z-index: 1;
    }

    .universal-form-style-v2 ul li label,
    .universal-form-style-v2 form label {
        float: none !important;
    }
</style>