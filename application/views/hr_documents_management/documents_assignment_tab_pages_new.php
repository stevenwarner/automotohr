<?php
$ncd = $pp = $cd = $nad = 0;
$canAccessDocument = hasDocumentsAssigned($session['employer_detail']);

$action_btn_flag = true;
if ($pp_flag == 1 || $canAccessDocument) {
    $action_btn_flag = false;
}

$document_all_permission = false;
if ($session['employer_detail']['access_level_plus'] == 1 || $canAccessDocument) {
    $document_all_permission = true;
}

// Modify Assigned document
// only available for Access_level_plus
// employees.
// Added in Not Completed, Completed
// and Not Required tabs
$completedDocumentsList = [];
$notCompletedDocumentsList = [];
$noActionRequiredDocumentsList = [];
?>
<style>
    .download_document_note {
        display: block;
        margin-top: 20px;
    }

    .jsCategoryManagerBTN {
        display: none;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <ul class="nav nav-tabs nav-justified doc_assign_nav_tab">
            <li class="active doc_assign_nav_li"><a data-toggle="tab" href="#in_complete_doc_details">Not Completed (<span class="js-ncd">0</span>)</a></li>
            <li class="doc_assign_nav_li"><a data-toggle="tab" href="#signed_doc_details">Completed Documents (<span class="js-cd">0</span>)</a></li>
            <li class="doc_assign_nav_li"><a data-toggle="tab" href="#no_action_required_doc_details">No Action Required (<span class="js-nad">0</span>)</a></li>
        </ul>
        <div class="tab-content hr-documents-tab-content">
            <!-- Not Completed Document Start -->
            <?php $this->load->view('hr_documents_management/partials/notcompleted_doc_tab_details.php'); ?>
            <!-- Not Completed Document End -->

            <!-- Offer Letter Document Start -->
            <?php $this->load->view('hr_documents_management/partials/offer_letter_doc_tab_details.php'); ?>
            <!-- Offer Letter Document End -->

            <!-- Signed Document Start -->
            <?php $this->load->view('hr_documents_management/partials/completed_doc_tab_details.php'); ?>
            <!-- Signed Document End -->
            <!-- Completed Document End -->

            <!-- No Action Required Document Start -->
            <?php $this->load->view('hr_documents_management/partials/noaction_doc_tab_details.php'); ?>
            <!-- No Action Required Document End -->
        </div>
    </div>
</div>

<script>
    $('.js-ncd').text(<?= $ncd; ?>);
    $('.js-pp').text(<?= $pp; ?>);
    
    //
    $('.js-send-document').popover({
        trigger: 'hover',
        html: true
    });

    //
    $('.js-send-document').click((e) => {
        //
        e.preventDefault();
        //
        let sid = $(e.target).data('id');
        //
        alertify.confirm(
            'Confirm!',
            'Do you really want to send this document by email?',
            () => {
                $('body').css('overflow-y', 'hidden');
                $('#my_loader .loader-text').html('Please wait while we are sending this document....');
                $('#my_loader').show();
                //
                sendDocumentByEmail(sid);
            },
            () => {}
        ).set('labels', {
            ok: 'YES',
            cancel: 'NO'
        });
    });


    //
    function sendDocumentByEmail(
        assignedDocumentSid
    ) {
        $.post("<?= base_url('hr_documents_management/send_document_to_sign'); ?>", {
            assignedDocumentSid: assignedDocumentSid
        }, (resp) => {
            //
            $('#my_loader').hide(0);
            $('#my_loader .loader-text').html('Please wait while we generate your E-Signature...');
            $('body').css('overflow-y', 'auto');
            //
            if (resp.Status === false) {
                alertify.alert('WARNING!', resp.Response, () => {});
                return;
            }
            //
            alertify.alert('SUCCESS!', resp.Response, () => {});
            return;
        });
    }

    //
    function offer_letter_archive(document_sid) {

        var baseURI = "<?= base_url('hr_documents_management/handler'); ?>";

        var formData = new FormData();
        formData.append('document_sid', document_sid);
        formData.append('action', 'change_offer_letter_archive_status');

        $.ajax({
            url: baseURI,
            data: formData,
            method: 'POST',
            processData: false,
            contentType: false
        }).done(function(resp) {
            var successMSG = 'Offer letter archived successfully.';
            alertify.alert('SUCCESS!', successMSG, function() {
                window.location.reload();
            });
        });
    }
</script>


<?php
$GLOBALS['notCompletedDocumentsList'] = $notCompletedDocumentsList;
$GLOBALS['completedDocumentsList'] = $completedDocumentsList;
$GLOBALS['noActionRequiredDocumentsList'] = $noActionRequiredDocumentsList;
?>