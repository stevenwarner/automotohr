<style>
    .signature_status_bulb {
        width: 22px;
        height: 22px;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .alertify-button-cancel {
        color: #518401 !important;
    }

    .panel-body {
        padding: 0px !important;
    }
</style>
<?php $archive_section = 'no'; ?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container" style=" min-height: 62vh;">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-xs-2 col-sm-2">
                                <br />
                                <a class="btn btn-info btn-block mb-2 csRadius5" href="<?php echo base_url('employee_management_system'); ?>">
                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Dashboard</a>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header full-width">
                                <h1 class="section-ttile">Employee Forms Library</h1>
                                <strong> Information:</strong> If you are unable to view the forms library, kindly reload the page. <h3 style="float: right;margin-top: 0px; margin-bottom: 0px;" id="total_documents"><?php echo $total_documents + count($verificationDocuments); ?> </h3>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <p>
                                <strong class="text-danger csF18"><i><?= $this->lang->line('document_librray_helping_text'); ?></i></strong>
                            </p>
                        </div>


                        <div class="tab-pane fade in hr-innerpadding">
                            <?php
                            $total_documents = count($verificationDocuments);
                            $employee_sid = $this->session->userdata('logged_in')['employer_detail']['sid'];
                            ?>

                            <!--  -->
                            <?php $this->load->view('hr_documents_management/library_document_verification'); ?>

                            <?php if (!empty($categories_documents)) { ?>
                                <?php foreach ($categories_documents as $category_document) { ?>
                                    <?php if (isset($category_document['documents'])) { ?>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="panel panel-default hr-documents-tab-content">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_no_action<?php echo $category_document['category_sid']; ?>">
                                                                <span class="glyphicon glyphicon-plus"></span>
                                                                <?php
                                                                echo $category_document['name'];
                                                                //
                                                                $total_record = 0;
                                                                //
                                                                if (count($category_document['documents']) > 0) {
                                                                    foreach ($category_document['documents'] as $cou => $document) {
                                                                        if ($document['archive'] != 1 && $document['manual_document_type'] != 'offer_letter') {
                                                                            $total_record = $total_record + 1;
                                                                            $total_documents = $total_documents + 1;
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                                <div class="pull-right total-records"><b><?php echo '&nbsp;Total: ' . $total_record; ?></b></div>
                                                            </a>
                                                        </h4>
                                                    </div>

                                                    <div id="collapse_no_action<?php echo $category_document['category_sid']; ?>" class="panel-body panel-collapse collapse in">
                                                        <div class="table-responsive full-width">
                                                            <table class="table table-plane">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-lg-2">Document Name</th>
                                                                        <th class="col-lg-1">Status</th>
                                                                        <th class="col-lg-2">Started Date</th>
                                                                        <th class="col-lg-2">Completed Date</th>
                                                                        <th class="col-lg-5 text-center" colspan="3">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if (count($category_document['documents']) > 0) { ?>
                                                                        <?php foreach ($category_document['documents'] as $document) { ?>
                                                                            <?php
                                                                            $font_color = "";
                                                                            $document_show_status = "";
                                                                            $document_btn_name = "";
                                                                            $modify_assigned_date = "-";
                                                                            $modify_completed_date = "-";
                                                                            $print_original_url = "";
                                                                            $print_completed_url = "";
                                                                            $download_original_url = "";
                                                                            $download_completed_url = "";
                                                                            //
                                                                            $assigned_document_data = get_documents_assigned_data($document['sid'], $employee_sid, 'employee');
                                                                            //
                                                                            $document_status = check_document_completed($assigned_document_data);
                                                                            //
                                                                            if (!empty($assigned_document_data) && $assigned_document_data['status'] == 1) {
                                                                                //
                                                                                $modify_assigned_date = get_document_action_date($assigned_document_data, "assigned");
                                                                                //
                                                                                if ($assigned_document_data["document_type"] == "uploaded") {
                                                                                    $assign_links = getUploadedDocumentURL($assigned_document_data["document_s3_name"]);
                                                                                } else {
                                                                                    $body = $assigned_document_data['document_description'];
                                                                                    $isAuthorized = preg_match('/{{authorized_signature}}|{{authorized_signature_date}}|{{authorized_editable_date}}/i', $body);
                                                                                    $assign_links = getGeneratedDocumentURL($assigned_document_data, "uncompleted", $isAuthorized);
                                                                                }
                                                                                //
                                                                                $print_original_url = $assign_links["print_url"];
                                                                                $download_original_url = $assign_links["download_url"];
                                                                                //
                                                                                if ($document_status == 'Completed') {
                                                                                    $font_color = "color:#81b431;";
                                                                                    $document_show_status = strtoupper($document_status);
                                                                                    $document_btn_name = "Re-Initiate";
                                                                                    $modify_completed_date = get_document_action_date($assigned_document_data, "completed");
                                                                                    //
                                                                                    if ($assigned_document_data["document_type"] == "uploaded") {
                                                                                        $completed_links = getUploadedDocumentURL($assigned_document_data["uploaded_file"]);
                                                                                    } else {
                                                                                        $body = $assigned_document_data['document_description'];
                                                                                        $isAuthorized = preg_match('/{{authorized_signature}}|{{authorized_signature_date}}|{{authorized_editable_date}}/i', $body);
                                                                                        $completed_links = getGeneratedDocumentURL($assigned_document_data, "completed", $isAuthorized);
                                                                                    }
                                                                                    //
                                                                                    $print_completed_url = $completed_links["print_url"];
                                                                                    $download_completed_url = $completed_links["download_url"];
                                                                                } else {
                                                                                    $font_color = "color:#fd7a2a;";
                                                                                    $document_show_status = strtoupper('Started');
                                                                                    $document_btn_name = "Complete";
                                                                                }
                                                                            } else {
                                                                                $font_color = "color:#3554dc;";
                                                                                $document_show_status = "Not Initiated";
                                                                                $document_btn_name = "Initiate";
                                                                                //
                                                                                if ($document["document_type"] == "uploaded") {
                                                                                    $links = getUploadedDocumentURL($document["uploaded_document_s3_name"]);
                                                                                } else {
                                                                                    $body = $document['document_description'];
                                                                                    $isAuthorized = preg_match('/{{authorized_signature}}|{{authorized_signature_date}}|{{authorized_editable_date}}/i', $body);
                                                                                    $links = getGeneratedDocumentURL($document, "company", $isAuthorized);
                                                                                }
                                                                                //
                                                                                $print_original_url = $links["print_url"];
                                                                                $download_original_url = $links["download_url"];
                                                                            }

                                                                            ?>
                                                                            <tr>
                                                                                <td class="col-lg-3">
                                                                                    <?php
                                                                                    echo $document['document_title'];
                                                                                    ?>
                                                                                </td>
                                                                                <td class="col-lg-1" style="<?php echo $font_color; ?>">
                                                                                    <?php
                                                                                    echo $document_show_status . "<br>";
                                                                                    ?>
                                                                                </td>
                                                                                <td class="col-lg-2">
                                                                                    <?php
                                                                                    echo $modify_assigned_date;
                                                                                    ?>
                                                                                </td>
                                                                                <td class="col-lg-2">
                                                                                    <?php
                                                                                    echo $modify_completed_date;
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <a title="Print the original document" class="btn  btn-info btn-orange csRadius5" target="_blank" href="<?= $print_original_url; ?>"><i class="fa fa-print" aria-hidden="true"></i></a>
                                                                                    <a title="Download the original document" class="btn  btn-black csRadius5" target="_blank" href="<?= $download_original_url; ?>"><i class="fa fa-download" aria-hidden="true"></i></a>
                                                                                    <?php if (empty($assigned_document_data) || $assigned_document_data['status'] != 1) : ?>
                                                                                        <a title="View the assigned document" class="btn  btn-success csRadius5" target="_blank" href="<?= base_url('hr_documents_management/preview_document') . '/company/' . $document['sid']; ?>">
                                                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                                                        </a>
                                                                                    <?php else : ?>
                                                                                        <a title="View the original document" class="btn  btn-success csRadius5" target="_blank" href="<?= base_url('hr_documents_management/sign_hr_document/d') . '/' . $assigned_document_data['sid'] . '/?document_backurl=library_document'; ?>">
                                                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                                                        </a>
                                                                                    <?php endif; ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php if (!empty($print_completed_url)) { ?>
                                                                                        <a title="Print the submitted document" class="btn btn-info btn-orange csRadius5" target="_blank" href="<?= $print_completed_url; ?>"><i class="fa fa-print" aria-hidden="true"></i></a>
                                                                                    <?php } ?>
                                                                                    <?php if (!empty($download_completed_url)) { ?>
                                                                                        <a title="Download the submitted document" class="btn btn-black csRadius5" target="_blank" href="<?= $download_completed_url; ?>"><i class="fa fa-download" aria-hidden="true"></i></a>
                                                                                    <?php } ?>
                                                                                    <?php if (!empty($assigned_document_data) && $assigned_document_data['status'] == 1) { ?>
                                                                                        <?php if ($document_status == 'Completed') { ?>
                                                                                            <a title="View the submitted document" class="btn btn-success csRadius5" target="_blank" href="<?= base_url('hr_documents_management/sign_hr_document/d') . '/' . $assigned_document_data['sid']; ?>">
                                                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                                                            </a>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                </td>
                                                                                <td>
                                                                                    <button class="btn btn-block <?php echo $document_btn_name == "Re-Initiate" ? "btn-warning" : "btn-success"; ?> completedocument csRadius5" document_sid="<?= $document['sid']; ?>" document_status="<?= !empty($assigned_document_data) && $assigned_document_data['status'] == 1 ?  $document_status : '' ?>" document_assigned_sid="<?= $assigned_document_data['sid']; ?>"><?= $document_btn_name; ?>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <tr>
                                                                            <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Found!</b></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php //} 
                                    ?>
                                <?php } ?>
                            <?php } else { ?>
                                <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Found!</b></td>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
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
    $("#total_documents").text(' Total: <?php echo $total_documents; ?>');

    $('.completedocument').on('click', function() {
        var document_sid = $(this).attr('document_sid');
        var document_status = $(this).attr('document_status');
        var document_assigned_sid = $(this).attr('document_assigned_sid');

        if (document_status == 'not_completed') {
            var document_url_view = '<?= base_url('hr_documents_management/sign_hr_document/d') ?>/' + document_assigned_sid + '/?document_backurl=library_document';
            window.location.href = document_url_view;
            exit();

        }

        var document_url = '<?= base_url('hr_documents_management/complete_library_document') ?>';

        var form_data = new FormData();
        form_data.append('document_sid', document_sid);

        $.ajax({
            url: document_url,
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            beforeSend: function() {
                $('#loader_text_div').text('Processing');
                $('#document_loader').show();
            },
            success: function(resp) {
                $('#loader_text_div').text('');
                $('#document_loader').hide();
                // alertify.alert('SUCCESS!', 'Saved Successfully', function(){
                var document_url_view = '<?= base_url('hr_documents_management/sign_hr_document/d') ?>/' + resp + '/?document_backurl=library_document';
                window.location.href = document_url_view;
                //  });

            },
            error: function() {}
        });

    });


    $(function() {
        if ($('.js-uncompleted-docs tbody tr').length == 0) {
            $('.js-uncompleted-docs').html('<h1 class="section-ttile text-center"> No Document Assigned! </h1>');
        }
    });
</script>

<style>
    .btn-success {
        background-color: #3554dc !important;
    }
</style>