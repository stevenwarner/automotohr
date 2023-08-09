<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">
                            <a class="dashboard-link-btn" href="<?php echo base_url('settings') ?>"><i class="fa fa-chevron-left"></i>Settings</a>
                            <?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <?= $title; ?>
                        </span>
                    </div>

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

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="message-action-btn">
                                    <a class="submit-btn" href="<?php echo base_url('company/documents/secure/add'); ?>">Upload Document(s)</a>
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
                                                <div id="show_no_jobs">
                                                    <span class="applicant-not-found">No Documents Found!</span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                    ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("#clear_filter").click(function() {
        $('#document_title').val('');
        $("form").submit();
    });

</script>