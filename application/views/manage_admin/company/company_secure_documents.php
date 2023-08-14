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
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-files-o"></i>Company Secure Documents
                                        </h1>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Manage Company</a>
                                    </div>

                                    <!--  -->
                                    <div class="message-action">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="hr-box">
                                                    <div class="hr-innerpadding">
                                                        <form enctype="multipart/form-data" id="document_search" method="get" novalidate="novalidate">
                                                            <div class="row" style="margin-top: 12px;">
                                                                <div class="col-lg-8 col-md-4 col-xs-12 col-sm-4">
                                                                    <label>Document title </label>
                                                                    <input type="text" name="title" id="document_title" value="<?php echo $documentTitle; ?>" class="invoice-fields">
                                                                </div>

                                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                                    <label>&nbsp;</label>
                                                                    <button type="submit" class="btn btn-block btn-success">Apply Filter</button>
                                                                </div>
                                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                                    <label>&nbsp;</label>
                                                                    <button type="button" class="btn btn-block btn-success" id="clear_filter">Clear Filter</button>
                                                                </div>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <?php if (!empty($secure_documents)) { ?>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                                                    <div class="table-responsive table-outer">
                                                        <div class="data-table">
                                                            <table id="categories_table" class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-xs-5">Document Title</th>
                                                                        <th class="col-xs-3">Created By</th>
                                                                        <th class="col-xs-2">Created At</th>
                                                                        <th class="col-xs-2">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($secure_documents as $document) {
                                                                    ?>
                                                                        <tr>
                                                                            <td style="vertical-align: middle;"><?php echo $document['document_title']; ?></td>
                                                                            <td style="vertical-align: middle;"><?php echo getUserNameBySID($document['created_by']) ?></td>
                                                                            <td style="vertical-align: middle;"><?php echo formatDateToDB($document['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></td>
                                                                            <td style="vertical-align: middle;">
                                                                                <button class="btn btn-info jsViewDocument" data-key=<?= $document['document_s3_name']; ?>>
                                                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                                                    &nbsp;View
                                                                                </button>
                                                                                <a class="btn btn-success csF16" href="<?php echo base_url('download/file/' . ($document['document_s3_name']) . ''); ?>">
                                                                                    <i class="fa fa-download csF16" aria-hidden="true"></i>
                                                                                    &nbsp;Download
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        <?php } else { ?>
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                    <div class="alert alert-info text-center">
                                                                        <p><strong>
                                                                                No records found.
                                                                            </strong></p>
                                                                    </div>
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
</div>


<?= GetCss([
    'v1/plugins/ms_modal/main',
]); ?>
<?= GetScripts([
    'js/app_helper',
    'v1/plugins/ms_modal/main',
    'v1/documents/main',
]); ?>




<script>
    $("#clear_filter").click(function() {
        $('#document_title').val('');
        $("form").submit();
    });
</script>