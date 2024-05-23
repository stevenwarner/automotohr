<?php
$requiredMessage = 'You must complete this document to finish the onboarding process.';
$ncd = $cd = 0;
if (isset($applicant)) {
    $i9_url = base_url('onboarding/form_i9/' . $unique_sid);
    $w4_url = base_url('onboarding/form_w4/' . $unique_sid);
    $w9_url = base_url('onboarding/form_w9/' . $unique_sid);
} else if (isset($employee)) {
    $i9_url = base_url('form_i9');
    $w4_url = base_url('form_w4');
    $w9_url = base_url('form_w9');
}

//
if (isset($employee) && $i9_form && $i9_form['version']) {
    $i9_url = base_url('forms/i9/my');
} else if (isset($employee) && $i9_form && $i9_form['user_consent'] == 0) {
    $i9_url = base_url('forms/i9/my');
}

if (isset($eev_i9) && sizeof($eev_i9)) {
    $i9_form['user_consent'] = $i9_form['status'] = 1;
    $i9_status = 1;
} else $i9_status = isset($i9_form) && sizeof($i9_form) && $i9_form['status'] != 0 && !empty($i9_form['user_consent']) && $i9_form['user_consent'] ? 1 : 0;

if (isset($eev_w4) && sizeof($eev_w4)) {
    $w4_form['user_consent'] = $w4_form['status'] = 1;
    $w4_status = 1;
} else $w4_status = isset($w4_form) && sizeof($w4_form) && $w4_form['status'] != 0 && $w4_form['user_consent'] ? 1 : 0;

if (isset($eev_w9) && sizeof($eev_w9)) {
    $w9_form['user_consent'] = $w9_form['status'] = 1;
    $w9_status = 1;
} else $w9_status = isset($w9_form) && sizeof($w9_form) && $w9_form['status'] != 0 && !empty($w9_form['user_consent']) && $w9_form['user_consent'] ? 1 : 0;


$nc = 0;
if ($i9_status == 0 && sizeof($i9_form)) $nc++;
if ($w4_status == 0 && sizeof($w4_form)) $nc++;
if ($w9_status == 0 && sizeof($w9_form)) $nc++;

if (isset($eeoc_form) && !empty($eeoc_form) && $eeoc_form["is_expired"] == 0 && $eeoc_form["status"] == 1) {
    $nc++;
}
$eeoc_status = 0;
if (isset($eeoc_form) && !empty($eeoc_form) && $eeoc_form["is_expired"] == 1 && $eeoc_form["status"] == 1) {
    $eeoc_status = 1;
}

?>
<style>
    #tab-nav>.active>a,
    #tab-nav>.active>a:hover,
    #tab-nav>.active>a:focus {
        color: #fff;
        background: #3554dc;
        font-weight: bold;
        font-size: 20px;
    }

    .nav-tabs.nav-justified>li>a {
        color: #0000ff;
        font-weight: bold;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <ul class="nav nav-tabs nav-justified" style="background-color: #e0e0e0;" id="tab-nav">
            <li class="active"><a data-toggle="tab" href="#in_complete_doc_details">Not Completed</a></li>
            <!-- <li><a data-toggle="tab" href="#offer_letter_doc_details">Offer Letter / Pay Plan</a></li> -->
            <li><a data-toggle="tab" href="#completed_doc_details">Completed</a></li>
            <li><a data-toggle="tab" href="#no_action_required_doc_details">No Action Required</a></li>
        </ul>
        <div class="tab-content hr-documents-tab-content">

            <!-- Not Completed Document Start -->
            <div id="in_complete_doc_details" class="tab-pane fade in active hr-innerpadding">
                <div>
                    <h3 class="tab-title" style="color: #0000EE;">Not Completed Document Detail</h3>
                    <div class="table-responsive full-width">
                        <table class="table table-plane cs-w4-table">
                            <thead>
                                <tr>
                                    <th class="col-lg-8 hidden-xs">Document Name</th>
                                    <th class="col-xs-12 hidden-md hidden-lg hidden-sm">Document</th>
                                    <th class="col-lg-4 col-xs-12 hidden-xs text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (sizeof($assigned_documents) || sizeof($uncompleted_offer_letter) || sizeof($uncompleted_payroll_documents)) { ?>
                                    <?php $assigned_documents = array_reverse($assigned_documents);  ?>
                                    <?php foreach ($assigned_documents as $document) { ?>
                                        <?php if ($document['archive'] != 1) { ?>
                                            <?php if ($document['status'] != 0) { ?>
                                                <?php $pdBtn = getPDBTN($document, 'btn-info'); ?>
                                                <tr>
                                                    <td class="">
                                                        <?php
                                                        echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '');
                                                        echo $document['user_consent'] == 1 ? '<b> (Waiting Authorize Signature)</b>' : '';
                                                        echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                        echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                        echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';
                                                        if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                            echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
                                                        }
                                                        ?>
                                                        <div class="hidden-lg hidden-md hidden-sm">
                                                            <?= $pdBtn['pm'] . $pdBtn['dm']; ?>
                                                            <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                        </div>
                                                    </td>
                                                    <td class="text-center hidden-xs">

                                                        <?php if ($document['document_type'] == 'hybrid_document') {
                                                            echo $pdBtn['pwnew'] . $pdBtn['dwnew'];
                                                        } else {
                                                            echo $pdBtn['pw'] . $pdBtn['dw'];
                                                        } ?>

                                                        <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                        <?php if ($document['isdoctolibrary'] == 1) { ?>
                                                            <a href="javascript:void(0);" class="btn btn-danger jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $document['sid']; ?>">Revoke</a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="7" class="col-lg-12 text-center"><b>No Document(s) Found!</b></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (sizeof($uncompleted_offer_letter)) { ?>
                        <?php $pdBtn = getPDBTN($uncompleted_offer_letter, 'btn-info'); ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <br />
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle open_not_completed_doc" data-toggle="collapse" data-parent="#accordion" href="#collapse_uncompleted_offer_letter">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <?php echo 'Offer Letter / Pay Plan'; ?>
                                                <div class="pull-right total-records"><b>&nbsp;Total: 1</b></div>
                                            </a>
                                        </h4>
                                    </div>

                                    <div id="collapse_uncompleted_offer_letter" class="panel-collapse collapse in">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-8">Document Name</th>
                                                        <th class="col-lg-4 text-center hidden-xs">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($uncompleted_offer_letter)) { ?>
                                                        <?php $pdBtn = getPDBTN($uncompleted_offer_letter, 'btn-info'); ?>
                                                        <?php //foreach ($uncompleted_offer_letter as $document) { 
                                                        ?>
                                                        <?php $ncd++; ?>
                                                        <tr>
                                                            <td class="">
                                                                <?php
                                                                echo $uncompleted_offer_letter['document_title'];
                                                                echo $uncompleted_offer_letter['status'] ? '' : '<b>(revoked)</b>';

                                                                if (isset($uncompleted_offer_letter['assigned_date']) && $uncompleted_offer_letter['assigned_date'] != '0000-00-00 00:00:00') {
                                                                    echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $uncompleted_offer_letter['assigned_date'], 'format' => 'M d Y, D', '_this' => $this));
                                                                }
                                                                ?>
                                                                <div class="hidden-lg hidden-md hidden-sm">
                                                                    <?php if ($uncompleted_offer_letter['document_type'] == 'offer_letter') { ?>
                                                                        <div class="">
                                                                            <?php
                                                                            if ($uncompleted_offer_letter['user_consent'] == 1) {
                                                                                $btn_name = 'View Offer Letter';
                                                                            } else {
                                                                                $btn_name = 'View Sign';
                                                                            }
                                                                            ?>
                                                                            <?= $pdBtn['pm'] . $pdBtn['dm']; ?>
                                                                            <a href="<?php echo $document_offer_letter_base . '/' . $uncompleted_offer_letter['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <div class="">
                                                                            <?= $pdBtn['pm'] . $pdBtn['dm']; ?>
                                                                            <a href="<?php echo $document_d_base . '/' . $uncompleted_offer_letter['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </td>
                                                            <?php if ($uncompleted_offer_letter['document_type'] == 'offer_letter') { ?>
                                                                <td class="hidden-xs text-center">
                                                                    <?php
                                                                    if ($uncompleted_offer_letter['user_consent'] == 1) {
                                                                        $btn_name = 'View Offer Letter';
                                                                    } else {
                                                                        $btn_name = 'View Sign';
                                                                    }
                                                                    ?>
                                                                    <?= $pdBtn['pw'] . $pdBtn['dw']; ?>
                                                                    <a href="<?php echo $document_offer_letter_base . '/' . $uncompleted_offer_letter['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                                    <?php if ($uncompleted_offer_letter['isdoctolibrary'] == 1) { ?>
                                                                        <a href="javascript:void(0);" class="btn btn-danger jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $uncompleted_offer_letter['sid']; ?>">Revoke</a>
                                                                    <?php } ?>
                                                                </td>
                                                            <?php } else { ?>
                                                                <td class=" hidden-xs text-center">
                                                                    <?= $pdBtn['pw'] . $pdBtn['dw']; ?>
                                                                    <a href="<?php echo $document_d_base . '/' . $uncompleted_offer_letter['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                    <?php if ($uncompleted_offer_letter['isdoctolibrary'] == 1) { ?>
                                                                        <a href="javascript:void(0);" class="btn btn-danger jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $uncompleted_offer_letter['sid']; ?>">Revoke</a>
                                                                    <?php } ?>
                                                                </td>
                                                            <?php } ?>
                                                        </tr>
                                                        <?php //} 
                                                        ?>
                                                        <?php //} 
                                                        ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Completed!</b></td>
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
                    <?php if (sizeof($uncompleted_payroll_documents)) { ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <br />
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle open_not_completed_doc" data-toggle="collapse" data-parent="#accordion" href="#collapse_uncompleted_payroll_documents">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <?php echo 'Payroll Documents'; ?>
                                                <div class="pull-right total-records"><b>Total: <?php echo count($uncompleted_payroll_documents); ?></b></div>
                                            </a>
                                        </h4>
                                    </div>

                                    <div id="collapse_uncompleted_payroll_documents" class="panel-collapse collapse in">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-8">Document Name</th>
                                                        <th class="col-lg-4 text-center hidden-xs">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($uncompleted_payroll_documents)) { ?>
                                                        <?php foreach ($uncompleted_payroll_documents as $document) { ?>
                                                            <?php $pdBtn = getPDBTN($document, 'btn-info'); ?>
                                                            <?php $ncd++; ?>
                                                            <tr>
                                                                <td class="">
                                                                    <?php
                                                                    echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '');
                                                                    echo $document['status'] ? '' : '<b>(revoked)</b>';

                                                                    if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                        echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], 'format' => 'M d Y, D', '_this' => $this));
                                                                    }
                                                                    ?>
                                                                    <div class="hidden-lg hidden-md hidden-sm">
                                                                        <?php if ($document['document_type'] == 'offer_letter') { ?>
                                                                            <div class="">
                                                                                <?php
                                                                                if ($document['user_consent'] == 1) {
                                                                                    $btn_name = 'View Offer Letter';
                                                                                } else {
                                                                                    $btn_name = 'View Sign';
                                                                                }
                                                                                ?>
                                                                                <?= $pdBtn['pm'] . $pdBtn['dm']; ?>
                                                                                <a href="<?php echo $document_offer_letter_base . '/' . $document['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <div class="">
                                                                                <?= $pdBtn['pm'] . $pdBtn['dm']; ?>
                                                                                <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                            </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                </td>
                                                                <?php if ($document['document_type'] == 'offer_letter') { ?>
                                                                    <td class="hidden-xs text-center">
                                                                        <?php
                                                                        if ($document['user_consent'] == 1) {
                                                                            $btn_name = 'View Offer Letter';
                                                                        } else {
                                                                            $btn_name = 'View Sign';
                                                                        }
                                                                        ?>
                                                                        <?= $pdBtn['pw'] . $pdBtn['dw']; ?>
                                                                        <a href="<?php echo $document_offer_letter_base . '/' . $document['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                                        <?php if ($document['isdoctolibrary'] == 1) { ?>
                                                                            <a href="javascript:void(0);" class="btn btn-danger jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $document['sid']; ?>">Revoke</a>
                                                                        <?php } ?>
                                                                    </td>
                                                                <?php } else { ?>
                                                                    <td class=" hidden-xs text-center">
                                                                        <?= $pdBtn['pw'] . $pdBtn['dw']; ?>
                                                                        <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                        <?php if ($document['isdoctolibrary'] == 1) { ?>
                                                                            <a href="javascript:void(0);" class="btn btn-danger jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $document['sid']; ?>">Revoke</a>
                                                                        <?php } ?>
                                                                    </td>
                                                                <?php } ?>
                                                            </tr>
                                                            <?php //} 
                                                            ?>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Completed!</b></td>
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
                </div>
                <br>
                <?php $this->load->view("hr_documents_management/partials/tabs/my_not_completed_state_forms"); ?>
                <!-- Verification Documents -->
                <?php if ($nc > 0) { ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default hr-documents-tab-content">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle open_not_completed_doc" data-toggle="collapse" data-parent="#accordion" href="#collapse_uncompleted_fillable">
                                            <span class="glyphicon glyphicon-plus"></span>
                                            Verification Documents
                                            <div class="pull-right total-records"><b><?php echo 'Total:' . $nc; ?></b></div>
                                        </a>

                                    </h4>
                                </div>

                                <div id="collapse_uncompleted_fillable" class="panel-collapse collapse in">
                                    <div class="table-responsive">
                                        <table class="table table-plane cs-w4-table">
                                            <thead>
                                                <tr>
                                                    <th class="col-lg-10 hidden-xs">Document Name</th>
                                                    <th class="col-lg-10 hidden-md hidden-lg hidden-sm">Document</th>
                                                    <th class="col-xs-2 text-center hidden-xs" colspan="2">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (isset($w4_form) && sizeof($w4_form) && $w4_form['status'] != 0 && (empty($w4_form['user_consent']) || $w4_form['user_consent'] == 0)) { ?>
                                                    <tr>
                                                        <td class="col-lg-10">
                                                            <?php
                                                            echo 'W4 Fillable' . ($w4_form['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '') . '';
                                                            echo $w4_form['status'] ? '' : '<b>(revoked)</b>';

                                                            if (isset($w4_form['sent_date']) && $w4_form['sent_date'] != '0000-00-00 00:00:00') {
                                                                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $w4_form['sent_date'], '_this' => $this));
                                                            }
                                                            ?>
                                                            <div class="hidden-sm hidden-lg text-center hidden-md">
                                                                <a href="<?php echo $w4_url; ?>" class="btn btn-info">View Sign</a>
                                                            </div>
                                                        </td>
                                                        <td class="col-lg-2 hidden-xs text-center">
                                                            <a href="<?php echo $w4_url; ?>" class="btn btn-info">View Sign</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <?php if (isset($w9_form) && sizeof($w9_form) && $w9_form['status'] != 0 && (empty($w9_form['user_consent']) || $w9_form['user_consent'] == 0)) { ?>
                                                    <tr>
                                                        <td class="col-lg-10">
                                                            <?php
                                                            echo 'W9 Fillable' . ($w9_form['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '') . '';
                                                            echo $w9_form['status'] ? '' : '<b>(revoked)</b>';

                                                            if (isset($w9_form['sent_date']) && $w9_form['sent_date'] != '0000-00-00 00:00:00') {
                                                                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $w9_form['sent_date'], '_this' => $this));
                                                            }
                                                            ?>
                                                            <div class="hidden-sm hidden-lg hidden-md">
                                                                <a href="<?php echo $w9_url; ?>" class="btn btn-info">View Sign</a>
                                                            </div>
                                                        </td>
                                                        <td class="col-lg-2 hidden-xs text-center">
                                                            <a href="<?php echo $w9_url; ?>" class="btn btn-info">View Sign</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <?php if (isset($i9_form) && sizeof($i9_form) && $i9_form['status'] != 0 && (empty($i9_form['user_consent']) || $i9_form['user_consent'] == 0)) { ?>
                                                    <tr>
                                                        <td class="col-lg-10">
                                                            <?php
                                                            echo 'I9 Fillable' . ($i9_form['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '') . '';
                                                            echo $i9_form['status'] ? '' : '<b>(revoked)</b>';
                                                            if (isset($i9_form['sent_date']) && $i9_form['sent_date'] != '0000-00-00 00:00:00') {
                                                                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $i9_form['sent_date'], '_this' => $this));
                                                            }
                                                            ?>
                                                            <div class="hidden-sm hidden-lg hidden-md">
                                                                <a href="<?php echo $i9_url; ?>" class="btn btn-info">View Sign</a>
                                                            </div>
                                                        </td>
                                                        <td class="col-lg-2 hidden-xs text-center">
                                                            <a href="<?php echo $i9_url; ?>" class="btn btn-info">View Sign</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <?php if (isset($eeoc_form) && sizeof($eeoc_form) && $eeoc_form['status'] != 0 && (empty($eeoc_form['is_expired']) || $eeoc_form['is_expired'] == 0)) { ?>
                                                    <tr>
                                                        <td class="col-lg-10">
                                                            <?php

                                                            echo 'EEOC Form';
                                                            echo $eeoc_form['status'] ? '' : '<b>(revoked)</b>';
                                                            if (isset($eeoc_form['last_sent_at']) && $eeoc_form['last_sent_at'] != '0000-00-00 00:00:00') {
                                                                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $eeoc_form['last_sent_at'], '_this' => $this));
                                                            }
                                                            ?>
                                                            <div class="hidden-sm hidden-lg hidden-md">
                                                                <a href="<?php echo $i9_url; ?>" class="btn btn-info">View Sign</a>
                                                            </div>
                                                        </td>
                                                        <td class="col-lg-2 hidden-xs text-center">
                                                            <a href="<?php echo base_url("my_eeoc_form"); ?>" class="btn btn-info">View Sign</a>
                                                        </td>
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

                <!-- General Documents -->
                <?php if (!isset($GID) && isset($NotCompletedGeneralDocuments) && count($NotCompletedGeneralDocuments) > 0) { ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default hr-documents-tab-content">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle open_not_completed_doc" data-toggle="collapse" data-parent="#accordion" href="#jsGeneralNotCompletedDocuments">
                                            <span class="glyphicon glyphicon-plus"></span>
                                            General Documents
                                            <div class="pull-right total-records"><b><?php echo 'Total: ' . count($NotCompletedGeneralDocuments); ?></b></div>
                                        </a>

                                    </h4>
                                </div>

                                <div id="jsGeneralNotCompletedDocuments" class="panel-collapse collapse in">
                                    <div class="table-responsive">
                                        <table class="table table-plane cs-w4-table">
                                            <thead>
                                                <tr>
                                                    <th class="col-lg-8 hidden-xs">Document Name</th>
                                                    <th class="col-lg-10 hidden-md hidden-lg hidden-sm">Document</th>
                                                    <th class="col-xs-4 text-center hidden-xs" colspan="2">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($NotCompletedGeneralDocuments as $v) {
                                                    $docURL = base_url('general_info/' . ($v['document_type']) . '');
                                                    $printBTN = '<a href="' . (base_url('hr_documents_management/gpd/print/' . ($v['document_type']) . '/' . ($user_type) . '/' . ($user_sid) . '')) . '" target="_blank" class="btn btn-info btn-orange" style="margin-right: 5px;">Print</a>';
                                                    $downloadBTN = '<a href="' . (base_url('hr_documents_management/gpd/print/' . ($v['document_type']) . '/' . ($user_type) . '/' . ($user_sid) . '')) . '" target="_blank" class="btn btn-info btn-black">Download</a>';
                                                ?>
                                                    <tr>
                                                        <td class="">
                                                            <?php
                                                            echo ucwords(str_replace('_', ' ', $v['document_type'])) . '';
                                                            if ($v['is_required'] == 1) {
                                                                echo ' <i class="fa fa-asterisk text-danger"></i>';
                                                            }
                                                            echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $v['updated_at'], '_this' => $this));
                                                            ?>
                                                            <div class="hidden-sm hidden-lg hidden-md">
                                                                <a href="<?php echo $docURL; ?>" class="btn btn-info">View Sign</a>
                                                            </div>
                                                        </td>
                                                        <td class="hidden-xs text-center">
                                                            <?= $printBTN; ?>
                                                            <?= $downloadBTN; ?>
                                                            <a href="<?php echo $docURL; ?>" class="btn btn-info">View Sign</a>
                                                        </td>
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

                <!-- Performance Documents Start -->
                <?php if (checkIfAppIsEnabled('performancemanagement') && $assignPerformanceDocument && $pendingPerformanceSection) { ?>
                    <?php $this->load->view('employee_performance_evaluation/document_center_blue'); ?>
                <?php } ?>
                <!-- Performance Documents End -->
            </div>
            <!-- Not Completed Document End -->

            <!-- Offer Letter Document Start -->
            <div id="offer_letter_doc_details" class="tab-pane fade in hr-innerpadding">
                <div>
                    <h3 class="tab-title" style="color: #0000EE;">Assigned Offer Letter / Pay Plan Detail</h3>
                    <div class="table-responsive full-width">
                        <table class="table table-plane cs-w4-table">
                            <thead>
                                <tr>
                                    <th class="col-lg-8 hidden-xs">Document Name</th>
                                    <th class="col-lg-8 hidden-md hidden-lg hidden-sm">Document</th>
                                    <th class="col-lg-4 hidden-xs text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($assigned_offer_letters)) { ?>
                                    <?php $assigned_offer_letters = array_reverse($assigned_offer_letters);  ?>
                                    <?php foreach ($assigned_offer_letters as $document) { ?>
                                        <?php $pdBtn = getPDBTN($document, 'btn-info'); ?>
                                        <tr>
                                            <td class="col-sm-12 col-xs-12">
                                                <?php
                                                echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '') . '';
                                                echo $document['status'] ? '' : '<b>(revoked)</b>';

                                                if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                    echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], 'format' => 'M d Y, D', '_this' => $this));
                                                }
                                                ?>
                                                <div class="hidden-sm hidden-md hidden-lg">
                                                    <?php if ($document['document_type'] == 'offer_letter') { ?>
                                                        <div class="">
                                                            <?php
                                                            if ($document['user_consent'] == 1) {
                                                                $btn_name = 'View Offer Letter';
                                                            } else {
                                                                $btn_name = 'View Sign';
                                                            }
                                                            ?>
                                                            <?= $pdBtn['pm'] . $pdBtn['dm']; ?>
                                                            <a href="<?php echo $document_offer_letter_base . '/' . $document['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="">
                                                            <?= $pdBtn['pm'] . $pdBtn['dm']; ?>
                                                            <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                            <?php if ($document['document_type'] == 'offer_letter') { ?>
                                                <td class="hidden-xs text-center">
                                                    <?php
                                                    if ($document['user_consent'] == 1) {
                                                        $btn_name = 'View Offer Letter';
                                                    } else {
                                                        $btn_name = 'View Sign';
                                                    }
                                                    ?>
                                                    <?= $pdBtn['pw'] . $pdBtn['dw']; ?>
                                                    <a href="<?php echo $document_offer_letter_base . '/' . $document['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                </td>
                                            <?php } else { ?>
                                                <td class="hidden-xs text-center">
                                                    <?= $pdBtn['pw'] . $pdBtn['dw']; ?>
                                                    <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>

                                <?php } else { ?>
                                    <tr>
                                        <td colspan="7" class="col-lg-12 text-center"><b>No Offer Letter Assigned!</b></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Offer Letter Document End -->

            <!-- Completed Document Start -->
            <div id="completed_doc_details" class="tab-pane fade in hr-innerpadding">
                <div>
                    <!-- Category Completed Document Start -->

                    <?php
                    // $fillable_count = $w4_status + $w9_status + $i9_status + $eeoc_status;
                    $fillable_count = count($completed_w4) + count($completed_w9) + count($completed_i9) + $eeoc_status;

                    if (!empty($categories_documents_completed)) { ?>
                        <h3 class="tab-title" style="color: #0000EE;">Completed Document Detail</h3>
                        <?php foreach ($categories_documents_completed as $category_document) { ?>
                            <?php if ($category_document['category_sid'] != 27) { ?>
                                <?php if (isset($category_document['documents'])) { ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="panel panel-default hr-documents-tab-content">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_completed<?php echo $category_document['category_sid']; ?>">
                                                            <span class="glyphicon glyphicon-plus"></span>
                                                            <?php echo $category_document['name']; ?>
                                                        </a>
                                                        <div class="pull-right total-records"><b><?php echo 'Total: ' . count($category_document['documents']); ?></b></div>
                                                    </h4>

                                                </div>

                                                <div id="collapse_completed<?php echo $category_document['category_sid']; ?>" class="panel-collapse collapse">
                                                    <div class="table-responsive">
                                                        <table class="table table-plane">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-xs-8">Document Name</th>
                                                                    <th class="col-xs-4 text-center hidden-xs" colspan="2">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                // print_r($category_document['documents']);

                                                                if (count($category_document['documents']) > 0) { ?>
                                                                    <?php foreach ($category_document['documents'] as $document) { ?>
                                                                        <?php if ($document["is_history"] == 0) { ?>
                                                                            <?php $pdBtn = getPDBTN($document, 'btn-info'); ?>
                                                                            <tr>
                                                                                <td class="">
                                                                                    <?php
                                                                                    echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '');
                                                                                    echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                                                    echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                                                    echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';

                                                                                    if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                        echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
                                                                                    }
                                                                                    ?>
                                                                                    <div class="hidden-sm hidden-lg hidden-md">
                                                                                        <?= $pdBtn['pm'] . $pdBtn['dm']; ?>
                                                                                        <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                                    </div>
                                                                                </td>
                                                                                </td>
                                                                                <td class=" text-center hidden-xs">

                                                                                    <?php if ($document['document_type'] == 'hybrid_document') {
                                                                                        echo $pdBtn['pwnew'] . $pdBtn['dwnew'];
                                                                                    } else {
                                                                                        echo $pdBtn['pw'] . $pdBtn['dw'];
                                                                                    } ?>

                                                                                    <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                                    <?php if ($document['isdoctolibrary'] == 1) { ?>
                                                                                        <a href="javascript:void(0);" class="btn btn-danger jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $document['sid']; ?>">Revoke</a>
                                                                                    <?php } ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } else { ?>
                                                                            <?php $this->load->view('hr_documents_management/my_document_assign_history_row', ['user_sid' => $user_sid, 'user_type' => $user_type, 'history_document' => $document]); ?>
                                                                        <?php } ?>
                                                                        <?php if (!empty($document["history"])) { ?>
                                                                            <?php foreach ($document["history"] as $history_document) { ?>
                                                                                <?php $cd++; ?>
                                                                                <?php $this->load->view('hr_documents_management/my_document_assign_history_row', ['user_sid' => $user_sid, 'user_type' => $user_type, 'history_document' => $history_document]); ?>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td colspan="7" class="col-lg-12 text-center"><b>No Documents Found!</b></td>
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
                    <?php }
                    if ($fillable_count > 0) { ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_completed_fillable">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                Verification Documents
                                                <div class="pull-right total-records"><b><?php echo 'Total: ' . $fillable_count; ?></b></div>
                                            </a>

                                        </h4>
                                    </div>

                                    <div id="collapse_completed_fillable" class="panel-collapse collapse">
                                        <div class="table-responsive">
                                            <table class="table table-plane cs-w4-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="column" class="col-lg-8">
                                                            Document Name
                                                        </th>
                                                        <th scope="column" class="col-lg-2">
                                                            Status
                                                        </th>
                                                        <th scope="column" class="col-lg-2">
                                                            Actions
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($completed_w4)) { ?>
                                                        <?php foreach ($completed_w4 as $w4_form) { ?>
                                                            <?php $cd++; ?>
                                                            <tr>
                                                                <td class="col-lg-8">
                                                                    W4 Fillable
                                                                    <br />
                                                                    <strong>Assigned on: </strong>
                                                                    <?php echo date('M d Y, D', strtotime($w4_form['sent_date'])); ?>
                                                                </td>
                                                                <td class="col-lg-2">
                                                                    <?php echo $w4_form['form_status']; ?>
                                                                </td>
                                                                <td class="col-lg-2">
                                                                    <a href="javascript:;" data-type="W4_Form" data-section="employee_section" data-status="<?php echo $w4_form['form_status']; ?>" data-doc_sid="<?php echo $w4_form['sid']; ?>" class="btn btn-info btn-block jsShowVarificationDocument" title="" placement="top" data-original-title="View W4 form">View Sign</a>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <?php if (!empty($completed_w9)) { ?>
                                                        <?php foreach ($completed_w9 as $w9_form) { ?>
                                                            <?php $cd++; ?>
                                                            <tr>
                                                                <td class="col-lg-8">
                                                                    W9 Fillable
                                                                    <br />
                                                                    <strong>Assigned on: </strong>
                                                                    <?php echo date('M d Y, D', strtotime($w9_form['sent_date'])); ?>
                                                                </td>
                                                                <td class="col-lg-2">
                                                                    <?php echo $w9_form['form_status']; ?>
                                                                </td>
                                                                <td class="col-lg-2">
                                                                    <a href="javascript:;" data-type="W9_Form" data-section="employee_section" data-status="<?php echo $w9_form['form_status']; ?>" data-doc_sid="<?php echo $w9_form['sid']; ?>" class="btn btn-info btn-block jsShowVarificationDocument" title="" placement="top" data-original-title="View W9 form">View Sign</a>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <?php if (!empty($completed_i9)) { ?>
                                                        <?php foreach ($completed_i9 as $i9_form) { ?>
                                                            <?php $cd++; ?>
                                                            <tr>
                                                                <td class="col-lg-8">
                                                                    I9 Fillable
                                                                    <br />
                                                                    <strong>Assigned on: </strong>
                                                                    <?php echo date('M d Y, D', strtotime($i9_form['sent_date'])); ?>
                                                                </td>
                                                                <td class="col-lg-2">
                                                                    <?php echo $i9_form['form_status']; ?>
                                                                </td>
                                                                <td class="col-lg-2">
                                                                    <a href="javascript:;" data-type="I9_Form" data-section="employee_section" data-status="<?php echo $i9_form['form_status']; ?>" data-doc_sid="<?php echo $i9_form['sid']; ?>" class="btn btn-info btn-block jsShowVarificationDocument" title="" placement="top" data-original-title="View I9 form">View Sign</a>
                                                                </td>

                                                            </tr>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php if (isset($eeoc_form) && sizeof($eeoc_form) && $eeoc_form['status'] != 0 && (empty($eeoc_form['is_expired']) || $eeoc_form['is_expired'] == 1)) { ?>
                                                        <tr>
                                                            <td class="col-lg-10">
                                                                <?php
                                                                echo 'EEOC Form';
                                                                echo $eeoc_form['status'] ? '' : '<b>(revoked)</b>';
                                                                if (isset($eeoc_form['last_sent_at']) && $eeoc_form['last_sent_at'] != '0000-00-00 00:00:00') {
                                                                    echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $eeoc_form['last_sent_at'], '_this' => $this));
                                                                }
                                                                ?>
                                                                <div class="hidden-sm hidden-lg hidden-md">
                                                                    <a href="<?php echo $i9_url; ?>" class="btn btn-info">View Sign</a>
                                                                </div>
                                                            </td>
                                                            <td class="col-lg-2 hidden-xs text-center">
                                                                <a href="<?php echo base_url("my_eeoc_form"); ?>" class="btn btn-info">View Sign</a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                    if (empty($categories_documents_completed) && $fillable_count == 0) {
                    ?>
                        <td colspan="7" class="col-lg-12 text-center"><b>No Document(s) Found!</b></td>
                    <?php
                    } ?>

                    <?php if (!empty($completed_offer_letter)) { ?>
                        <?php $pdBtn = getPDBTN($completed_offer_letter, 'btn-info'); ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_completed_offer_letter">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                Offer Letter / Pay Plan
                                                <div class="pull-right total-records"><b>Total: 1</b></div>
                                            </a>
                                        </h4>
                                    </div>

                                    <div id="collapse_completed_offer_letter" class="panel-collapse collapse">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane cs-w4-table">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-8 hidden-xs">Document Name</th>
                                                        <th class="col-xs-12 hidden-md hidden-lg hidden-sm">Document</th>
                                                        <th class="col-lg-4 text-right ">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="col-lg-8">
                                                            <?php
                                                            echo $completed_offer_letter['document_title'] . '&nbsp;';

                                                            if (isset($completed_offer_letter['assigned_date']) && $completed_offer_letter['assigned_date'] != '0000-00-00 00:00:00') {
                                                                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $completed_offer_letter['assigned_date'], 'format' => 'M d Y, D', '_this' => $this));
                                                            }
                                                            ?>
                                                            <div class="hidden-lg hidden-md hidden-sm">
                                                                <?php
                                                                if ($completed_offer_letter['user_consent'] == 1) {
                                                                    $btn_name = 'View Offer Letter';
                                                                } else {
                                                                    $btn_name = 'View Sign';
                                                                }
                                                                ?>
                                                                <?= $pdBtn['pm'] . $pdBtn['dm']; ?>
                                                                <a href="<?php echo $document_offer_letter_base . '/' . $completed_offer_letter['sid']; ?>" class="btn btn-info">View Sign</a>
                                                            </div>
                                                        </td>
                                                        <td class="col-lg-4 text-center hidden-xs">
                                                            <?php
                                                            if ($completed_offer_letter['user_consent'] == 1) {
                                                                $btn_name = 'View Offer Letter';
                                                            } else {
                                                                $btn_name = 'View Sign';
                                                            }
                                                            ?>
                                                            <?= $pdBtn['pw'] . $pdBtn['dw']; ?>
                                                            <a href="<?php echo $document_offer_letter_base . '/' . $completed_offer_letter['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                            <?php if ($completed_offer_letter['isdoctolibrary'] == 1) { ?>
                                                                <a href="javascript:void(0);" class="btn btn-danger jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $completed_offer_letter['sid']; ?>">Revoke</a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (sizeof($completed_payroll_documents)) { ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <br />
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_completed_payroll_documents">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <?php echo 'Payroll Documents'; ?>
                                                <div class="pull-right total-records"><b>Total: <?php echo count($completed_payroll_documents); ?></b></div>
                                            </a>
                                        </h4>
                                    </div>

                                    <div id="collapse_completed_payroll_documents" class="panel-collapse collapse">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-8">Document Name</th>
                                                        <th class="col-lg-4 text-center hidden-xs">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($completed_payroll_documents)) { ?>
                                                        <?php foreach ($completed_payroll_documents as $document) { ?>
                                                            <?php $pdBtn = getPDBTN($document, 'btn-info'); ?>
                                                            <?php $ncd++; ?>
                                                            <?php if ($document["is_history"] == 0) { ?>
                                                                <tr>
                                                                    <td class="">
                                                                        <?php
                                                                        echo $document['document_title'] . ($document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="' . ($requiredMessage) . '"></i>' : '');
                                                                        echo $document['status'] ? '' : '<b>(revoked)</b>';

                                                                        if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                            echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], 'format' => 'M d Y, D', '_this' => $this));
                                                                        }
                                                                        ?>
                                                                        <div class="hidden-lg hidden-md hidden-sm">
                                                                            <?php if ($document['document_type'] == 'offer_letter') { ?>
                                                                                <div class="">
                                                                                    <?php
                                                                                    if ($document['user_consent'] == 1) {
                                                                                        $btn_name = 'View Offer Letter';
                                                                                    } else {
                                                                                        $btn_name = 'View Sign';
                                                                                    }
                                                                                    ?>
                                                                                    <?= $pdBtn['pm'] . $pdBtn['dm']; ?>
                                                                                    <a href="<?php echo $document_offer_letter_base . '/' . $document['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <div class="">
                                                                                    <?= $pdBtn['pm'] . $pdBtn['dm']; ?>
                                                                                    <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </td>
                                                                    <?php if ($document['document_type'] == 'offer_letter') { ?>
                                                                        <td class="hidden-xs text-center">
                                                                            <?php
                                                                            if ($document['user_consent'] == 1) {
                                                                                $btn_name = 'View Offer Letter';
                                                                            } else {
                                                                                $btn_name = 'View Sign';
                                                                            }
                                                                            ?>
                                                                            <?= $pdBtn['pw'] . $pdBtn['dw']; ?>
                                                                            <a href="<?php echo $document_offer_letter_base . '/' . $document['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                                            <?php if ($document['isdoctolibrary'] == 1) { ?>
                                                                                <a href="javascript:void(0);" class="btn btn-danger jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $document['sid']; ?>">Revoke</a>
                                                                            <?php } ?>
                                                                        </td>
                                                                    <?php } else { ?>
                                                                        <?= $pdBtn['pw'] . $pdBtn['dw']; ?>
                                                                        <td class="hidden-xs text-center">
                                                                            <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                            <?php if ($document['isdoctolibrary'] == 1) { ?>
                                                                                <a href="javascript:void(0);" class="btn btn-danger jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $document['sid']; ?>">Revoke</a>
                                                                            <?php } ?>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php } else { ?>
                                                                <?php $this->load->view('hr_documents_management/my_document_assign_history_row', ['user_sid' => $user_sid, 'user_type' => $user_type, 'history_document' => $document]); ?>
                                                            <?php } ?>
                                                            <?php if (!empty($document["history"])) { ?>
                                                                <?php foreach ($document["history"] as $history_document) { ?>
                                                                    <?php $cd++; ?>
                                                                    <?php $this->load->view('hr_documents_management/my_document_assign_history_row', ['user_sid' => $user_sid, 'user_type' => $user_type, 'history_document' => $history_document]); ?>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Completed!</b></td>
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
                    <!-- Category Completed Document End -->

                    <!-- General Documents -->
                    <?php if (isset($CompletedGeneralDocuments) && count($CompletedGeneralDocuments) > 0) { ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#jsGeneralCompletedDocuments">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                General Documents
                                                <div class="pull-right total-records"><b><?php echo 'Total: ' . count($CompletedGeneralDocuments); ?></b></div>
                                            </a>

                                        </h4>
                                    </div>

                                    <div id="jsGeneralCompletedDocuments" class="panel-collapse collapse">
                                        <div class="table-responsive">
                                            <table class="table table-plane cs-w4-table">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-8 hidden-xs">Document Name</th>
                                                        <th class="col-lg-10 hidden-md hidden-lg hidden-sm">Document</th>
                                                        <th class="col-xs-4 text-center hidden-xs" colspan="2">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($CompletedGeneralDocuments as $v) {
                                                        $docURL = base_url('general_info/' . ($v['document_type']) . '');
                                                        $printBTN = '<a href="' . (base_url('hr_documents_management/gpd/print/' . ($v['document_type']) . '/' . ($user_type) . '/' . ($user_sid) . '')) . '" target="_blank" class="btn btn-info btn-orange" style="margin-right: 5px;">Print</a>';
                                                        $downloadBTN = '<a href="' . (base_url('hr_documents_management/gpd/print/' . ($v['document_type']) . '/' . ($user_type) . '/' . ($user_sid) . '')) . '" target="_blank" class="btn btn-info btn-black">Download</a>';
                                                    ?>
                                                        <tr>
                                                            <td class="">
                                                                <?php
                                                                echo ucwords(str_replace('_', ' ', $v['document_type']));
                                                                if ($v['is_required'] == 1) {
                                                                    echo ' <i class="fa fa-asterisk text-danger"></i>';
                                                                }
                                                                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $v['updated_at'], '_this' => $this));
                                                                ?>
                                                                <div class="hidden-sm hidden-lg hidden-md">
                                                                    <?= $printBTN; ?>
                                                                    <?= $downloadBTN; ?>
                                                                    <a href="<?php echo $docURL; ?>" class="btn btn-info">View Sign</a>
                                                                </div>
                                                            </td>
                                                            <td class="col-lg-4 hidden-xs text-center">
                                                                <?= $printBTN; ?>
                                                                <?= $downloadBTN; ?>
                                                                <a href="<?php echo $docURL; ?>" class="btn btn-info">View Sign</a>
                                                            </td>
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

                    <?php $this->load->view("hr_documents_management/partials/tabs/my_completed_state_forms"); ?>

                    <!-- Performance Documents Start -->
                    <?php if (checkIfAppIsEnabled('performancemanagement') && $assignPerformanceDocument && !$pendingPerformanceSection) { ?>
                        <?php $this->load->view('employee_performance_evaluation/document_center_blue'); ?>
                    <?php } ?>
                    <!-- Performance Documents End -->
                </div>
            </div>
            <!-- Completed Document End -->

            <!-- No Action Required Document Start -->
            <div id="no_action_required_doc_details" class="tab-pane fade in hr-innerpadding">
                <div>
                    <!-- Category No Action Required Document Start -->
                    <?php if (!empty($categories_no_action_documents)) { ?>
                        <h3 class="tab-title" style="color: #0000EE;">No Action Required Document Detail</h3>
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
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            <div class="pull-right total-records"><b><?php echo 'Total: ' . $total_record; ?></b></div>
                                                        </a>

                                                    </h4>
                                                </div>

                                                <div id="collapse_no_action<?php echo $category_document['category_sid']; ?>" class="panel-collapse collapse">
                                                    <div class="table-responsive">
                                                        <table class="table table-plane">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-sm-8">Document Name</th>
                                                                    <th class="col-sm-4 text-center hidden-xs" colspan="2">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if (count($category_document['documents']) > 0) {
                                                                    foreach ($category_document['documents'] as $document) {
                                                                ?>
                                                                        <?php if ($document['archive'] != 1 && $document['manual_document_type'] != 'offer_letter') { ?>
                                                                            <?php if ($document['status'] != 0) { ?>
                                                                                <?php $pdBtn = getPDBTN($document, 'btn-info'); ?>
                                                                                <tr>
                                                                                    <td class="">
                                                                                        <?php
                                                                                        echo $document['document_title'] . '&nbsp;';
                                                                                        echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                                                        echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                                                        echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';

                                                                                        if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                            echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], 'format' => 'M d Y, D', '_this' => $this));
                                                                                        }

                                                                                        if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                                                            echo "<br><b>Signed On: </b>" . reset_datetime(array('datetime' => $document['signature_timestamp'], 'format' => 'M d Y, D',  '_this' => $this));
                                                                                        } else {
                                                                                            echo "<br><b>Signed On: </b> N/A";
                                                                                        }

                                                                                        ?>
                                                                                        <div class="hidden-lg hidden-md hidden-sm">
                                                                                            <?= $pdBtn['pm'] . $pdBtn['dm']; ?>
                                                                                            <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td class="text-center hidden-xs">

                                                                                        <?php if ($document['document_type'] == 'hybrid_document') {
                                                                                            echo $pdBtn['pwnew'] . $pdBtn['dwnew'];
                                                                                        } else {
                                                                                            echo $pdBtn['pw'] . $pdBtn['dw'];
                                                                                        } ?>
                                                                                        <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                                        <?php if ($document['isdoctolibrary'] == 1) { ?>
                                                                                            <a href="javascript:void(0);" class="btn btn-danger jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $document['sid']; ?>">Revoke</a>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        <?php } ?>


                                                                    <?php } ?>

                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td colspan="7" class="col-lg-12 text-center"><b>No Documents Found!</b></td>
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
                        <td colspan="7" class="col-lg-12 text-center"><b>No Document(s) Found!</b></td>
                    <?php
                    } ?>

                    <?php if (sizeof($no_action_required_payroll_documents)) { ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <br />
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_no_action_required_payroll_documents">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <?php echo 'Payroll Documents'; ?>
                                                <div class="pull-right total-records"><b>Total: <?php echo count($no_action_required_payroll_documents); ?></b></div>
                                            </a>
                                        </h4>
                                    </div>

                                    <div id="collapse_no_action_required_payroll_documents" class="panel-collapse collapse">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-8">Document Name</th>
                                                        <th class="col-lg-4 text-center hidden-xs">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($no_action_required_payroll_documents)) { ?>
                                                        <?php foreach ($no_action_required_payroll_documents as $document) {
                                                            if (!$document["status"]) {
                                                                continue;
                                                            } ?>
                                                            <?php $pdBtn = getPDBTN($document, 'btn-info'); ?>
                                                            <?php $ncd++; ?>
                                                            <tr>
                                                                <td class="col-lg-10">
                                                                    <?php
                                                                    echo $document['document_title'] . '&nbsp;';
                                                                    echo $document['status'] ? '' : '<b>(revoked)</b>';

                                                                    if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                        echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], 'format' => 'M d Y, D', '_this' => $this));
                                                                    }
                                                                    ?>
                                                                    <div class="hidden-lg hidden-md hidden-sm">
                                                                        <?php if ($document['document_type'] == 'offer_letter') { ?>
                                                                            <div class="">
                                                                                <?php
                                                                                if ($document['user_consent'] == 1) {
                                                                                    $btn_name = 'View Offer Letter';
                                                                                } else {
                                                                                    $btn_name = 'View Sign';
                                                                                }
                                                                                ?>
                                                                                <?= $pdBtn['pm'] . $pdBtn['dm']; ?>
                                                                                <a href="<?php echo $document_offer_letter_base . '/' . $document['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <div class="">
                                                                                <?= $pdBtn['pm'] . $pdBtn['dm']; ?>
                                                                                <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                            </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                </td>
                                                                <?php if ($document['document_type'] == 'offer_letter') { ?>
                                                                    <td class=" hidden-xs text-center">
                                                                        <?php
                                                                        if ($document['user_consent'] == 1) {
                                                                            $btn_name = 'View Offer Letter';
                                                                        } else {
                                                                            $btn_name = 'View Sign';
                                                                        }
                                                                        ?>
                                                                        <?= $pdBtn['pw'] . $pdBtn['dw']; ?>
                                                                        <a href="<?php echo $document_offer_letter_base . '/' . $document['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                                        <?php if ($document['isdoctolibrary'] == 1) { ?>
                                                                            <a href="javascript:void(0);" class="btn btn-danger jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $document['sid']; ?>">Revoke</a>
                                                                        <?php } ?>
                                                                    </td>
                                                                <?php } else { ?>
                                                                    <td class=" hidden-xs text-center">
                                                                        <?= $pdBtn['pw'] . $pdBtn['dw']; ?>
                                                                        <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                        <?php if ($document['isdoctolibrary'] == 1) { ?>
                                                                            <a href="javascript:void(0);" class="btn btn-danger jsRevokeDocumentLibrary" title="Revoke Library Document" data-asid="<?= $document['sid']; ?>">Revoke</a>
                                                                        <?php } ?>
                                                                    </td>
                                                                <?php } ?>
                                                            </tr>
                                                            <?php //} 
                                                            ?>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <tr>
                                                            <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Completed!</b></td>
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
                    <!-- Category No Action Required Document End -->
                </div>
            </div>
            <!-- No Action Required Document End -->

        </div>
    </div>
</div>

<!-- Preview Latest Document Modal Start -->
<div id="fillable_history_document_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="history_document_modal_title">
                    Fillable Verification History
                </h4>
            </div>
            <div class="modal-body">
                <div id="history_document_preview" style="display:none;">

                </div>
            </div>
            <div class="modal-footer" id="history_document_modal_footer">

            </div>
        </div>
    </div>
</div>
<!-- Preview Latest Document Modal Modal End -->

<!-- Loader Start -->
<div id="document_loader" class="text-center my_loader" style="display: none; z-index: 1234;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i aria-hidden="true" class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" id="loader_text_div" style="display:block; margin-top: 35px;">
        </div>
    </div>
</div>
<!-- Loader End -->

<!-- Preview Latest Document Modal Start -->
<div id="show_latest_preview_document_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="latest_document_modal_title"></h4>
            </div>
            <div class="modal-body">
                <div id="latest-iframe-container" style="display:none;">
                    <div class="embed-responsive embed-responsive-4by3">
                        <div id="latest-iframe-holder" class="embed-responsive-item">
                        </div>
                    </div>
                </div>
                <div id="latest_assigned_document_preview" style="display:none;">

                </div>
            </div>
            <div class="modal-footer" id="latest_document_modal_footer">

            </div>
        </div>
    </div>
</div>
<!-- Preview Latest Document Modal Modal End -->

<?php $this->load->view('hr_documents_management/show_document_history'); ?>
<style>
    {
        width: 100%;
        margin-top: 10px;
    }

    @media (max-width: 576px) {
        .total-records {
            float: none !important;

        }

    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        //
        $('.jsRevokeDocumentLibrary').click((e) => {
            //
            e.preventDefault();
            //
            let sid = $(e.target).data('asid');
            //
            alertify.confirm(
                'Are you Sure?',
                'Do you really want to revoke this library document?',
                function() {
                    $('#loader').show();
                    var myRequest;
                    var myData = {
                        'action': 'revoke_library_document',
                        'document_sid': sid
                    };
                    var myUrl = '<?php echo base_url("hr_documents_management/handler"); ?>';



                    myRequest = $.ajax({
                        url: myUrl,
                        type: 'POST',
                        data: myData,
                        dataType: 'json'
                    });

                    myRequest.done(function(response) {
                        alertify.alert("Success", 'Document Library Revoke Successfully', function() {
                            window.location.reload();
                        });
                    });
                },
                function() {
                    alertify.alert("Warning", 'Cancelled!');
                }).set('labels', {
                ok: 'I Consent and Accept!',
                cancel: 'Cancel'
            });
        });
    })

    $('#show_latest_preview_document_modal').on('hidden.bs.modal', function() {
        $("#latest-iframe-holder").html('');
        $("#latest_document_iframe").remove();
        $("#latest_image_tag").remove();
        $('#latest-iframe-container').hide();
        $('#latest_assigned_document_preview').html('');
        $('#latest_assigned_document_preview').hide();
    });

    function preview_latest_generic_function(source) {
        var letter_type = $(source).attr('date-letter-type');
        var request_type = $(source).attr('data-on-action');
        var document_title = '';

        if (request_type == 'assigned') {
            document_title = 'Assigned Document';
        } else if (request_type == 'submitted') {
            document_title = 'Submitted Document';
        } else if (request_type == 'company') {
            document_title = 'Company Document';
        }

        if (letter_type == 'uploaded') {
            var preview_document = 1;
            var model_contant = '';
            var preview_iframe_url = '';
            var preview_image_url = '';
            var document_print_url = '';
            var document_download_url = '';

            var document_sid = $(source).attr('data-doc-sid');
            var file_s3_path = $(source).attr('data-preview-url');
            var file_s3_name = $(source).attr('data-s3-name');

            var file_extension = file_s3_name.substr(file_s3_name.lastIndexOf('.') + 1, file_s3_name.length);
            var document_file_name = file_s3_name.substr(0, file_s3_name.lastIndexOf('.'));
            var document_extension = file_extension.toLowerCase();


            switch (file_extension.toLowerCase()) {
                case 'pdf':
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.pdf';
                    break;
                case 'csv':
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.csv';
                    break;
                case 'doc':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Edoc&wdAccPdf=0';
                    break;
                case 'docx':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Edocx&wdAccPdf=0';
                    break;
                case 'ppt':
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.ppt';
                    break;
                case 'pptx':
                    dpreview_iframe_url = 'https://docs.google.com/gview?url=' + file_s3_path + '&embedded=true';
                    document_print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' + document_file_name + '.pptx';
                    break;
                case 'xls':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    ocument_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Exls';
                    break;
                case 'xlsx':
                    preview_iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + encodeURI(file_s3_path);
                    document_print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' + document_file_name + '%2Exlsx';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'JPG':
                case 'JPE':
                case 'JPEG':
                case 'PNG':
                case 'GIF':
                    preview_document = 0;
                    preview_image_url = file_s3_path;
                    document_print_url = '<?php echo base_url("hr_documents_management/print_s3_image"); ?>' + '/' + file_s3_name;
                    break;
                default: //using google docs
                    preview_iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    break;
            }

            document_download_url = '<?php echo base_url("hr_documents_management/download_upload_document"); ?>' + '/' + file_s3_name;

            $('#show_latest_preview_document_modal').modal('show');
            $("#latest_document_modal_title").html(document_title);
            $('#latest-iframe-container').show();

            if (preview_document == 1) {
                model_contant = $("<iframe />")
                    .attr("id", "latest_document_iframe")
                    .attr("class", "uploaded-file-preview")
                    .attr("src", preview_iframe_url);
            } else {
                model_contant = $("<img />")
                    .attr("id", "latest_image_tag")
                    .attr("class", "img-responsive")
                    .css("margin-left", "auto")
                    .css("margin-right", "auto")
                    .attr("src", preview_image_url);
            }


            $("#latest-iframe-holder").append(model_contant);
            //
            if (preview_document == 1) {
                loadIframe(
                    preview_iframe_url,
                    '#latest_document_iframe',
                    true
                );
            }

            footer_content = '<a target="_blank" class="btn btn-success" href="' + document_print_url + '">Print</a>';
            footer_content += '<a target="_blank" class="btn btn-success" href="' + document_download_url + '">Download</a>';
            $("#latest_document_modal_footer").html(footer_content);
        } else {
            var request_sid = $(source).attr('data-doc-sid');
            var request_from = $(source).attr('data-from');

            $.ajax({
                'url': '<?php echo base_url('hr_documents_management/get_document_content'); ?>' + '/' + request_sid + '/' + request_type + '/' + request_from,
                'type': 'GET',
                success: function(contant) {
                    var obj = jQuery.parseJSON(contant);
                    var requested_content = obj.requested_content;
                    var document_view = obj.document_view;
                    var form_input_data = obj.form_input_data;
                    var is_iframe_preview = obj.is_iframe_preview;

                    var print_url = '<?php echo base_url('hr_documents_management/perform_action_on_document_content'); ?>' + '/' + request_sid + '/' + request_type + '/' + request_from + '/print';
                    var download_url = '<?php echo base_url('hr_documents_management/perform_action_on_document_content'); ?>' + '/' + request_sid + '/' + request_type + '/' + request_from + '/download';

                    $('#show_latest_preview_document_modal').modal('show');
                    $("#latest_document_modal_title").html(document_title);

                    if (request_type == 'submitted') {
                        if (is_iframe_preview == 1) {
                            var model_contant = '';

                            $('#latest-iframe-container').show();
                            $('#latest_assigned_document_preview').hide();

                            var model_contant = $("<iframe />")
                                .attr("id", "latest_document_iframe")
                                .attr("class", "uploaded-file-preview")
                                .attr("src", requested_content);

                            $("#latest-iframe-holder").append(model_contant);

                            loadIframe(
                                requested_content,
                                '#latest_document_iframe',
                                true
                            );
                        } else {
                            $('#latest-iframe-container').hide();
                            $('#latest_assigned_document_preview').show();
                            $("#latest_assigned_document_preview").html(document_view);

                            form_input_data = Object.entries(form_input_data);

                            if ($('#latest_assigned_document_preview').find('select').length >= 0) {
                                $('#latest_assigned_document_preview').find('select').map(function(i) {
                                    //
                                    $(this).addClass('js_select_document');
                                    $(this).prop('name', 'selectDD' + i);
                                });
                            }

                            $.each(form_input_data, function(key, input_value) {
                                if (input_value[0] == 'signature_person_name') {
                                    var input_field_id = input_value[0];
                                    var input_field_val = input_value[1];
                                    $('#' + input_field_id).val(input_field_val);
                                } else {
                                    var input_field_id = input_value[0] + '_id';
                                    var input_field_val = input_value[1];
                                    var input_type = $('#' + input_field_id).attr('data-type');

                                    if (input_type == 'text') {
                                        $('#' + input_field_id).val(input_field_val);
                                        $('#' + input_field_id).prop('disabled', true);
                                    } else if (input_type == 'checkbox') {
                                        if (input_field_val == 'yes') {
                                            $('#' + input_field_id).prop('checked', true);;
                                        }
                                        $('#' + input_field_id).prop('disabled', true);

                                    } else if (input_type == 'textarea') {
                                        $('#' + input_field_id).hide();
                                        $('#' + input_field_id + '_sec').show();
                                        $('#' + input_field_id + '_sec').html(input_field_val);
                                    } else if (input_value[0].match(/select/) !== -1) {
                                        if (input_value[1] != null) {
                                            let cc = get_select_box_value(input_value[0], input_value[1]);
                                            $(`select.js_select_document[name="${input_value[0]}"]`).hide(0);
                                            $(`select.js_select_document[name="${input_value[0]}"]`).after(`<strong style="font-size: 20px;">${cc}</strong>`)
                                        }
                                    }
                                }
                            });
                        }
                    } else {

                        model_contant = requested_content;
                        $('#latest-iframe-container').hide();
                        $('#latest_assigned_document_preview').show();
                        $("#latest_assigned_document_preview").html(document_view);
                    }

                    footer_content = '<a target="_blank" class="btn btn-success" href="' + print_url + '">Print</a>';
                    footer_content += '<a target="_blank" class="btn btn-success" href="' + download_url + '">Download</a>';
                    $("#latest_document_modal_footer").html(footer_content);
                }
            });
        }
    }
</script>