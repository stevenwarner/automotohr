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
</style>
<?php $archive_section = 'no'; ?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-xs-2 col-sm-2">
                            <br />
                            <a class="btn btn-info csRadius5" href="<?php echo base_url('dashboard'); ?>">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Dashboard</a>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="page-header full-width">
                            <h1 class="section-ttile">Assigned Documents Library</h1>
                            <strong> Information:</strong> If you are unable to view the authorized documents library, kindly reload the page.
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="min-height: 500px;">
                        <?php if (!empty($documents_list)) { ?>
                            <div class="table-responsive full-width table-outer">
                                <table class="table table-striped table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="col-lg-4">Document Name</th>
                                            <th class="col-lg-3">Employee / Applicant</th>
                                            <th class="col-lg-2 text-center">Assigned Date</th>
                                            <th class="col-lg-1 text-center">Sign Status</th>
                                            <th class="col-lg-2 text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $assigned_documents = array_reverse($documents_list);  ?>
                                        <?php foreach ($documents_list as $document) { ?>
                                            <?php if ($document['archive'] != 1 && $document['status'] != 0 && $document['assign_archive'] == 0) { ?>
                                                <?php
                                                $isCompleted = isDocumentCompletedCheck($document, true);

                                                ?>
                                                <tr class="">
                                                    <td class="col-lg-3">
                                                        <?php
                                                        echo $document['document_title'] . '&nbsp; <br />';
                                                        echo "(" . (!empty($document['offer_letter_type']) ? "Offer Letter - " . (ucwords($document['offer_letter_type'])) . "" : "Document - " . (ucwords($document['document_type'])) . "") . ')&nbsp;';

                                                        if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                            echo "<br><b>Assigned At: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
                                                        }

                                                        if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                            echo "<br><b>Consent At: </b>" . reset_datetime(array('datetime' => $document['signature_timestamp'], '_this' => $this));
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="col-lg-4">
                                                        <?php
                                                        $user_type = '';
                                                        $user_name = '';
                                                        if ($document['user_type'] == 'applicant') {
                                                            $user_type = 'Applicant';
                                                            $user_name = get_applicant_name($document['user_sid']);
                                                        } else {
                                                            $user_type = 'Employee';
                                                            $user_name = getUserNameBySID($document['user_sid']);
                                                        }
                                                        echo $user_name . "<br /> <b>(" . $user_type . ")</b>";
                                                        ?>
                                                    </td>
                                                    <td class="col-lg-2  text-center">
                                                        <?php echo reset_datetime(array('datetime' => $document['assigned_by_date'], '_this' => $this)); ?>
                                                    </td>
                                                    <td class="col-lg-1">

                                                        <?php

                                                        if ($document['fillable_documents_slug'] == 'employee-performance-evaluation') {
                                                            $performance_document_json = json_decode($document['performance_document_json'], true);
                                                            $performanceEvaluationSection4 = 0;
                                                            if (
                                                                $performance_document_json['section4']['data']['section4managerSignature']
                                                                && $performance_document_json['section4']['data']['section4nextLevelSignature']
                                                                && $performance_document_json['section4']['data']['section4hrSignature']
                                                            ) {
                                                                $performanceEvaluationSection4 = 1;
                                                            }
                                                        }
                                                        ?>
                                                        <?php if (!empty($document['authorized_signature'])) { ?>
                                                            <img class="img-responsive text-center signature_status_bulb" title="Document Signed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                                        <?php } else if ($document['fillable_documents_slug'] == 'employee-performance-evaluation' && $performanceEvaluationSection4 == 1) { ?>
                                                            <img class="img-responsive text-center signature_status_bulb" title="Document Signed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">

                                                        <?php  } else { ?>
                                                            <img class="img-responsive text-center signature_status_bulb" title="Document Not Signed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                                        <?php } ?>
                                                    </td>

                                                    <td class="col-lg-2">
                                                        <?php $btn_show = empty($document['authorized_signature']) ?  'btn blue-button btn-sm btn-block' : 'btn btn-success btn-sm btn-block'; ?>
                                                        <?php $btn_text = empty($document['authorized_signature']) ?  'Sign Doc - Not Completed' : 'Sign Doc - Completed'; ?>

                                                       <?php if ($document['fillable_documents_slug'] == 'employee-performance-evaluation' && $performanceEvaluationSection4 == 1) { 
                                                       $btn_text = 'Sign Doc - Completed'; }?>

                                                        <?php $doc_type = $document['document_type'] == "offer_letter" ?  'o' : 's'; ?>
                                                        <a class="<?php echo $btn_show; ?>" href="<?php echo  base_url('view_assigned_authorized_document' . '/' . $doc_type . '/' . $document['sid']); ?>">
                                                            <?php echo $btn_text; ?>
                                                        </a>

                                                        <?php if ($document['user_type'] == 'applicant') { ?>
                                                            <a href="javascript:;" document_sid="<?php echo $document['sid']; ?>" class="btn btn-warning btn-sm btn-block archive_document">Archive</a>
                                                        <?php } ?>

                                                        <?php if ($document['fillable_documents_slug'] != null && $document['fillable_documents_slug'] != '') { ?>
                                                            <a target="_blank" class="btn btn-success btn-sm btn-block" href="<?php echo base_url('v1/fillable_documents/PrintPrevieFillable/') . '/' . $document['fillable_documents_slug'] . '/' . $document['sid'] . '/submited/' . '/print'; ?>">Print</a>
                                                            <a target="_blank" class="btn btn-success btn-sm btn-block" href="<?php echo base_url('v1/fillable_documents/PrintPrevieFillable/') . '/' . $document['fillable_documents_slug'] . '/' . $document['sid'] . '/submited/' . '/download'; ?>">Download</a>
                                                        <?php } else {  ?>

                                                            <a href="<?= base_url('hr_documents_management/perform_action_on_document_content' . '/' . $document['sid'] . '/' . ($isCompleted == 1 ? 'submitted' : 'assigned') . '/assigned_document/print'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>

                                                            <a href="<?= base_url('hr_documents_management/perform_action_on_document_content' . '/' . $document['sid'] . '/' . ($isCompleted == 1 ? 'submitted' : 'assigned') . '/assigned_document/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>

                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } else { ?>
                                                <?php $archive_section = 'yes'; ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php echo $links; ?>
                        <?php } else { ?>
                            <h1 class="section-ttile text-center"> No Document Assigned! </h1>
                        <?php } ?>
                    </div>


                    <?php if ($archive_section == 'yes') { ?>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header full-width">
                                <h1 class="section-ttile">Archived Documents</h1>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php if (!empty($documents_list)) { ?>
                                <div class="table-responsive full-width table-outer">
                                    <table class="table  table-striped table-condensed table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="col-lg-4">Document Name</th>
                                                <th class="col-lg-2">Applicant Name</th>
                                                <th class="col-lg-2 text-center">Archived On</th>
                                                <th class="col-lg-2 text-center">Archived By</th>
                                                <th class="col-lg-2 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($documents_list as $document) { ?>
                                                <?php if ($document['archive'] != 1 && $document['status'] != 0 && $document['assign_archive'] == 1) { ?>
                                                    <?php $isCompleted = isDocumentCompletedCheck($document, true); ?>
                                                    <tr class="">
                                                        <td class="col-lg-4">
                                                            <?php
                                                            echo $document['document_title'] . '&nbsp; <br />';
                                                            echo "(" . (!empty($document['offer_letter_type']) ? "Offer Letter - " . (ucwords($document['offer_letter_type'])) . "" : "Document - " . (ucwords($document['document_type'])) . "") . ')&nbsp;';

                                                            if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                echo "<br><b>Assigned At: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
                                                            }

                                                            if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                                echo "<br><b>Consent At: </b>" . reset_datetime(array('datetime' => $document['signature_timestamp'], '_this' => $this));
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="col-lg-2">
                                                            <?php
                                                            $user_type = '';
                                                            $user_name = '';
                                                            if ($document['user_type'] == 'applicant') {
                                                                $user_type = 'Applicant';
                                                                $user_name = get_applicant_name($document['user_sid']);
                                                            } else {
                                                                $user_type = 'Employee';
                                                                $user_name = getUserNameBySID($document['user_sid']);
                                                            }
                                                            echo $user_name . "<br /> <b>(" . $user_type . ")</b>";
                                                            ?>
                                                        </td>
                                                        <td class="col-lg-2  text-center">
                                                            <?php echo reset_datetime(array('datetime' => $document['archived_by_date'], '_this' => $this)); ?>
                                                        </td>
                                                        <td class="col-lg-2">
                                                            <?php
                                                            echo getUserNameBySID($document['archived_by']);
                                                            ?>
                                                        </td>
                                                        <td class="col-lg-2">
                                                            <?php if ($document['user_type'] == 'applicant') { ?>
                                                                <a href="javascript:;" document_sid="<?php echo $document['sid']; ?>" class="btn blue-button btn-sm btn-block activate_document">Activate</a>
                                                            <?php } ?>

                                                            <a href="<?= base_url('hr_documents_management/perform_action_on_document_content' . '/' . $document['sid'] . '/' . ($isCompleted == 1 ? 'submitted' : 'assigned') . '/assigned_document/print'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>

                                                            <a href="<?= base_url('hr_documents_management/perform_action_on_document_content' . '/' . $document['sid'] . '/' . ($isCompleted == 1 ? 'submitted' : 'assigned') . '/assigned_document/download'); ?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php echo $links; ?>
                            <?php } else { ?>
                                <h1 class="section-ttile text-center"> No Document Assigned! </h1>
                            <?php } ?>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        if ($('.js-uncompleted-docs tbody tr').length == 0) {
            $('.js-uncompleted-docs').html('<h1 class="section-ttile text-center"> No Document Assigned! </h1>');
        }
    });

    $('.archive_document').on('click', function() {
        var document_sid = $(this).attr('document_sid');
        alertify.confirm('Confirm', "Are you sure you want to archive this authorized  document?",
            function() {
                setTimeout(function() {
                    archive_type(document_sid);
                }, 0);
            },
            function() {
                alertify.alert('Note', 'Archive process terminated.');
            }).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });

    });

    function archive_type(document_sid) {

        alertify.confirm('Action', "Do you want to archive this authorized document for all managers?",
            function() {
                var user_sid = '<?php echo $employer_sid; ?>';
                modify_assign_document(document_sid, 'archive', 'multiple');
            },
            function() {
                modify_assign_document(document_sid, 'archive', 'single');
            }).set('labels', {
            ok: 'Yes',
            cancel: 'No, just for me'
        });
    }

    $('.activate_document').on('click', function() {
        var document_sid = $(this).attr('document_sid');
        alertify.confirm('Confirm', "Are you sure you want to activate this authorized document?",
            function() {
                setTimeout(function() {
                    activate_type(document_sid);
                }, 0);
            },
            function() {
                alertify.alert('Note', 'Activate process terminated.');
            }).set('labels', {
            ok: 'Yes',
            cancel: 'No'
        });

    });

    function activate_type(document_sid) {

        alertify.confirm('Action', "Do you want to activate this authorized document for all managers?",
            function() {
                var user_sid = '<?php echo $employer_sid; ?>';
                modify_assign_document(document_sid, 'active', 'multiple');
            },
            function() {
                modify_assign_document(document_sid, 'active', 'single');
            }).set('labels', {
            ok: 'Yes',
            cancel: 'No, just for me'
        });
    }

    function modify_assign_document(document_sid, action_name, action_type) {
        var user_sid = '<?php echo $employer_sid; ?>';
        var archive_url = '<?= base_url('hr_documents_management/handler') ?>';

        var form_data = new FormData();
        form_data.append('action', 'modify_authorized_document');
        form_data.append('document_sid', document_sid);
        form_data.append('user_sid', user_sid);
        form_data.append('action_name', action_name);
        form_data.append('action_type', action_type);

        $.ajax({
            url: archive_url,
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function(resp) {
                alertify.alert('SUCCESS!', resp.Response, function() {
                    window.location.reload();
                });
            },
            error: function() {}
        });
    }
</script>
<!-- Archive For Me -->
<style>
    .btn-success {
        background-color: #3554dc !important;
    }
</style>