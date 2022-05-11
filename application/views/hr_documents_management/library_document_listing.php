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

    .completedocument {}
</style>
<?php $archive_section = 'no'; ?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
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
                            <h1 class="section-ttile">Documents Library</h1>
                            <strong> Information:</strong> If you are unable to view the documents library, kindly reload the page. <h3 style="float: right;margin-top: 0px; margin-bottom: 0px;" id="total_documents"><?php echo $total_documents; ?> </h3>
                        </div>
                    </div>

                    <div id="no_action_required_doc_details" class="tab-pane fade in hr-innerpadding">
                        <div class="panel-body">
                            <?php
                            $total_documents = 0;
                            $employee_sid = $this->session->userdata('logged_in')['employer_detail']['sid'];
                            if (!empty($categories_no_action_documents)) { ?>
                                <?php foreach ($categories_no_action_documents as $category_document) { ?>
                                    <?php if ($category_document['category_sid'] != 27) { ?>
                                        <?php if (isset($category_document['documents'])) { ?>

                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="panel panel-default hr-documents-tab-content">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title">
                                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_no_action<?php echo $category_document['category_sid']; ?>">
                                                                    <span class="glyphicon glyphicon-plus"></span>
                                                                    <?php echo $category_document['name']; ?>
                                                                    <?php
                                                                    $total_record = 0;
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

                                                        <div id="collapse_no_action<?php echo $category_document['category_sid']; ?>" class="panel-collapse collapse">
                                                            <div class="table-responsive full-width">
                                                                <table class="table table-plane">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="col-lg-3">Document Name</th>
                                                                            <th class="col-lg-1">Status</th>
                                                                            <th class="col-lg-2">Started Date</th>
                                                                            <th class="col-lg-2">Completed Date</th>

                                                                            <th class="col-lg-3 text-center" colspan="4">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if (count($category_document['documents']) > 0) { ?>
                                                                            <?php foreach ($category_document['documents'] as $document) { ?>
                                                                                <?php $noActionRequiredDocumentsList[] = $document; ?>
                                                                                <?php $nad++; ?>
                                                                                <tr>
                                                                                    <td class="col-lg-3">
                                                                                        <?php
                                                                                        $assigned_document_data = get_documents_assigned_data($document['sid'], $employee_sid, 'employee');
                                                                                        $document_status = check_document_completed($assigned_document_data);
                                                                                        $document_completed_date = check_document_completed_date($assigned_document_data);
                                                                                        echo $document['document_title'] . '<br>';
                                                                                        echo  "Type: " . $document['document_type'];
                                                                                        ?>
                                                                                    </td>

                                                                                    <?php
                                                                                    $no_action_document_url = $document['document_s3_name'];
                                                                                    $no_action_document_info = get_required_url($no_action_document_url);
                                                                                    $no_action_print_url = $no_action_document_info['print_url'];
                                                                                    $no_action_download_url = $no_action_document_info['download_url'];
                                                                                    ?>

                                                                                    <td class="col-lg-1">
                                                                                        <?php
                                                                                        if (!empty($assigned_document_data) && $assigned_document_data['status'] == 1) {
                                                                                            echo ($document_status == 'Completed') ? $document_status : 'Started' . "<br>";
                                                                                        }

                                                                                        ?>

                                                                                    <td class="col-lg-2"><?php if (isset($assigned_document_data['assigned_date']) && $assigned_document_data['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                                                echo  reset_datetime(array('datetime' => $assigned_document_data['assigned_date'], '_this' => $this));
                                                                                                            } ?></td>
                                                                                    <td class="col-lg-2"><?php
                                                                                                            // echo  date('M d Y, D', strtotime($document_completed_date['signature_timestamp']));
                                                                                                            echo   reset_datetime(array('datetime' => $document_completed_date, '_this' => $this));
                                                                                                            ?></td>

                                                                                    </td>
                                                                                    <td class="col-lg-4">
                                                                                        <div class="col-lg-3"><a href="<?= base_url('hr_documents_management/perform_action_on_document_content' . '/' . $document['sid'] . '/' . ($isCompleted == 1 ? 'submitted' : 'assigned') . '/company_document/print'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                                                        </div>
                                                                                        <div class="col-lg-4"><a href="<?= base_url('hr_documents_management/perform_action_on_document_content' . '/' . $document['sid'] . '/' . ($isCompleted == 1 ? 'submitted' : 'assigned') . '/company_document/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                                                        </div>
                                                                                        <div class="col-lg-5"><button class="btn btn-success btn-sm btn-block completedocument" document_sid="<?= $document['sid']; ?>" document_status="<? if (!empty($assigned_document_data) && $assigned_document_data['status'] == 1) {
                                                                                                                                                                                                                                            echo $document_status;
                                                                                                                                                                                                                                        }; ?>" document_assigned_sid="<?= $assigned_document_data['sid'] ?>">Complete Document</button>
                                                                                        </div>
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
                                    <?php } ?>
                                <?php } ?>
                            <?php } else {
                            ?>
                                <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Found!</b></td>
                            <?php
                            } ?>

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
            var document_url_view = '<?= base_url('hr_documents_management/sign_hr_document/d') ?>/' + document_assigned_sid;
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
                var document_url_view = '<?= base_url('hr_documents_management/sign_hr_document/d') ?>/' + resp;
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