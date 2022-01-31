<?php
    $requiredMessage = 'You must complete this document to finish the onboarding process.';
    $ncd = $cd = 0;
    if (isset($applicant)) {
        $i9_url = base_url('onboarding/form_i9/'.$unique_sid);
        $w4_url = base_url('onboarding/form_w4/'.$unique_sid);
        $w9_url = base_url('onboarding/form_w9/'.$unique_sid);
    } else if (isset($employee)) {
        $i9_url = base_url('form_i9');
        $w4_url = base_url('form_w4');
        $w9_url = base_url('form_w9');
    }
    if(isset($eev_i9) && sizeof($eev_i9)) { $i9_form['user_consent'] = $i9_form['status'] = 1 ; $i9_status = 1;}
    else $i9_status = isset($i9_form) && sizeof($i9_form) && $i9_form['status'] != 0 && !empty($i9_form['user_consent']) && $i9_form['user_consent'] ? 1 : 0;

    if(isset($eev_w4) && sizeof($eev_w4)) { $w4_form['user_consent'] = $w4_form['status'] = 1 ; $w4_status = 1;}
    else $w4_status = isset($w4_form) && sizeof($w4_form) && $w4_form['status'] != 0 && $w4_form['user_consent'] ? 1 : 0 ;

    if(isset($eev_w9) && sizeof($eev_w9)) { $w9_form['user_consent'] = $w9_form['status'] = 1 ; $w9_status = 1;}
    else $w9_status = isset($w9_form) && sizeof($w9_form) && $w9_form['status'] != 0 && !empty($w9_form['user_consent']) && $w9_form['user_consent'] ? 1 : 0;


    $nc = 0;
    if($i9_status == 0 && sizeof($i9_form)) $nc++;
    if($w4_status == 0 && sizeof($w4_form)) $nc++;
    if($w9_status == 0 && sizeof($w9_form)) $nc++;
    
    if(isset($eeoc_form) && !empty($eeoc_form) && $eeoc_form["is_expired"] == 0) { $nc++; }

?>
<style>
    #tab-nav > .active > a,
    #tab-nav > .active > a:hover,
    #tab-nav > .active > a:focus {
        color: #fff;
        background: #3554dc;
        font-weight: bold;
        font-size: 20px;
    }

    .nav-tabs.nav-justified > li > a {
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
                                <?php if(sizeof($assigned_documents) || sizeof($uncompleted_offer_letter) || sizeof($uncompleted_payroll_documents)) { ?>
                                    <?php $assigned_documents = array_reverse($assigned_documents);  ?>
                                    <?php foreach ($assigned_documents as $document) { ?>
                                        <?php if ($document['archive'] != 1) { ?>
                                            <?php if ($document['status'] != 0) { ?>
                                                <?php $pdBtn = getPDBTN($document, 'btn-info'); ?>
                                                <tr>
                                                    <td class="">
                                                        <?php
                                                            echo $document['document_title'].( $document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="'.($requiredMessage).'"></i>' : '' ).'';
                                                            echo $document['user_consent'] == 1 ? '<b> (Waiting Authorize Signature)</b>' : '';
                                                            echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                            echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                            echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';
                                                            if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
                                                            }
                                                        ?>
                                                        <div class="hidden-lg hidden-md hidden-sm">
                                                            <?=$pdBtn['pm'].$pdBtn['dm'];?>
                                                           <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                        </div>
                                                    </td>
                                                    <td class="text-center hidden-xs">
                                                        <?=$pdBtn['pw'].$pdBtn['dw'];?>
                                                        <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
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
                    <?php if(sizeof($uncompleted_offer_letter)){ ?>
                        <?php $pdBtn = getPDBTN($uncompleted_offer_letter, 'btn-info'); ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <br />
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_uncompleted_offer_letter">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <?php echo 'Offer Letter / Pay Plan'; ?>
                                                <div class="pull-right total-records"><b>&nbsp;Total: 1</b></div>
                                            </a>
                                        </h4>
                                    </div>

                                    <div id="collapse_uncompleted_offer_letter" class="panel-collapse collapse">
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
                                                        <?php //foreach ($uncompleted_offer_letter as $document) { ?>
                                                        <?php $ncd++; ?>
                                                        <tr>
                                                            <td class="">
                                                                <?php
                                                                    echo $uncompleted_offer_letter['document_title'].( $uncompleted_offer_letter['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="'.($requiredMessage).'"></i>' : '' ).'';
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
                                                                            <?=$pdBtn['pm'].$pdBtn['dm'];?>
                                                                            <a href="<?php echo $document_offer_letter_base . '/' . $uncompleted_offer_letter['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <div class="">
                                                                            <?=$pdBtn['pm'].$pdBtn['dm'];?>
                                                                            <a href="<?php echo $document_d_base . '/' . $uncompleted_offer_letter['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                        </div>
                                                                    <?php } ?>
                                                               </div>
                                                            </td>
                                                                <?php if ($uncompleted_offer_letter['document_type'] == 'offer_letter') { ?>
                                                                    <td class="hidden-xs text-center" >
                                                                        <?php
                                                                            if ($uncompleted_offer_letter['user_consent'] == 1) {
                                                                                $btn_name = 'View Offer Letter';
                                                                            } else {
                                                                                $btn_name = 'View Sign';
                                                                            }
                                                                        ?>
                                                                        <?=$pdBtn['pw'].$pdBtn['dw'];?>
                                                                        <a  href="<?php echo $document_offer_letter_base . '/' . $uncompleted_offer_letter['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                                    </td>
                                                                <?php } else { ?>
                                                                    <td class=" hidden-xs text-center">
                                                                        <?=$pdBtn['pw'].$pdBtn['dw'];?>
                                                                        <a  href="<?php echo $document_d_base . '/' . $uncompleted_offer_letter['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                    </td>
                                                                <?php } ?>
                                                        </tr>
                                                            <?php //} ?>
                                                        <?php //} ?>
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
                    <?php if(sizeof($uncompleted_payroll_documents)){ ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <br />
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_uncompleted_payroll_documents">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <?php echo 'Payroll Documents'; ?>
                                                <div class="pull-right total-records"><b>Total: <?php echo count($uncompleted_payroll_documents); ?></b></div>
                                            </a>
                                        </h4>
                                    </div>

                                    <div id="collapse_uncompleted_payroll_documents" class="panel-collapse collapse">
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
                                                                    echo $document['document_title'].( $document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="'.($requiredMessage).'"></i>' : '' ).'';
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
                                                                            <?=$pdBtn['pm'].$pdBtn['dm'];?>
                                                                            <a href="<?php echo $document_offer_letter_base . '/' . $document['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <div class="">
                                                                            <?=$pdBtn['pm'].$pdBtn['dm'];?>
                                                                            <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                        </div>
                                                                    <?php } ?>
                                                               </div>
                                                            </td>
                                                                <?php if ($document['document_type'] == 'offer_letter') { ?>
                                                                    <td class="hidden-xs text-center" >
                                                                        <?php
                                                                            if ($document['user_consent'] == 1) {
                                                                                $btn_name = 'View Offer Letter';
                                                                            } else {
                                                                                $btn_name = 'View Sign';
                                                                            }
                                                                        ?>
                                                                        <?=$pdBtn['pw'].$pdBtn['dw'];?>
                                                                        <a  href="<?php echo $document_offer_letter_base . '/' . $document['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                                    </td>
                                                                <?php } else { ?>
                                                                    <td class=" hidden-xs text-center">
                                                                        <?=$pdBtn['pw'].$pdBtn['dw'];?>
                                                                        <a  href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                    </td>
                                                                <?php } ?>
                                                        </tr>
                                                            <?php //} ?>
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
                <!-- Verification Documents -->
                <?php if($nc > 0) { ?>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-default hr-documents-tab-content">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_uncompleted_fillable" >
                                        <span class="glyphicon glyphicon-plus"></span>
                                          Verification Documents
                                        <div class="pull-right total-records"><b><?php echo 'Total:' .$nc; ?></b></div>
                                    </a>

                                </h4>
                            </div>

                            <div id="collapse_uncompleted_fillable" class="panel-collapse collapse">
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
                                                    echo 'W4 Fillable'.( $w4_form['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="'.($requiredMessage).'"></i>' : '' ).'';
                                                    echo $w4_form['status'] ? '' : '<b>(revoked)</b>';

                                                    if (isset($w4_form['sent_date']) && $w4_form['sent_date'] != '0000-00-00 00:00:00') {
                                                        echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $w4_form['sent_date'], '_this' => $this));
                                                    }
                                                    ?>
                                                   <div class="hidden-sm hidden-lg text-center hidden-md">
                                                       <a  href="<?php echo $w4_url; ?>" class="btn btn-info">View Sign</a>
                                                   </div>
                                                </td>
                                                <td class="col-lg-2 hidden-xs text-center">
                                                    <a href="<?php echo $w4_url; ?>" class="btn btn-info">View Sign</a>
                                                </td>
                                            </tr>
                                        <?php }?>
                                        <?php if (isset($w9_form) && sizeof($w9_form) && $w9_form['status'] != 0 && (empty($w9_form['user_consent']) || $w9_form['user_consent'] == 0)) { ?>
                                            <tr>
                                                <td class="col-lg-10">
                                                    <?php
                                                    echo 'W9 Fillable'.( $w9_form['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="'.($requiredMessage).'"></i>' : '' ).'';
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
                                        <?php }?>
                                        <?php if (isset($i9_form) && sizeof($i9_form) && $i9_form['status'] != 0 && (empty($i9_form['user_consent']) || $i9_form['user_consent'] == 0)) { ?>
                                            <tr>
                                                <td class="col-lg-10">
                                                    <?php
                                                    echo 'I9 Fillable'.( $i9_form['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="'.($requiredMessage).'"></i>' : '' ).'';
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
                                        <?php }?>
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
                                                    <a href="<?php echo base_url("my_eeoc_form").'/'.$user_sid.'/'.$user_type; ?>" class="btn btn-info">View Sign</a>
                                                </td>
                                            </tr>
                                        <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <!-- General Documents -->
                <?php if(!isset($GID) && isset($NotCompletedGeneralDocuments) && count($NotCompletedGeneralDocuments) > 0) { ?>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-default hr-documents-tab-content">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#jsGeneralNotCompletedDocuments" >
                                        <span class="glyphicon glyphicon-plus"></span>
                                          General Documents
                                        <div class="pull-right total-records"><b><?php echo 'Total: ' .count($NotCompletedGeneralDocuments); ?></b></div>
                                    </a>

                                </h4>
                            </div>

                            <div id="jsGeneralNotCompletedDocuments" class="panel-collapse collapse">
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
                                            <?php foreach($NotCompletedGeneralDocuments as $v) { 
                                                $docURL = base_url('general_info/'.( $v['document_type'] ).'');
                                                $printBTN = '<a href="'.( base_url('hr_documents_management/gpd/print/'.( $v['document_type'] ).'/'.( $user_type ).'/'.( $user_sid ).'') ).'" target="_blank" class="btn btn-info btn-orange" style="margin-right: 5px;">Print</a>';
                                                $downloadBTN = '<a href="'.( base_url('hr_documents_management/gpd/print/'.( $v['document_type'] ).'/'.( $user_type ).'/'.( $user_sid ).'') ).'" target="_blank" class="btn btn-info btn-black">Download</a>';
                                            ?>
                                            <tr>
                                                <td class="">
                                                    <?php
                                                        echo ucwords(str_replace('_', ' ', $v['document_type']));
                                                        echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $v['updated_at'], '_this' => $this));
                                                    ?>
                                                    <div class="hidden-sm hidden-lg hidden-md">
                                                       <a href="<?php echo $docURL; ?>" class="btn btn-info">View Sign</a>
                                                   </div>
                                                </td>
                                                <td class="hidden-xs text-center">
                                                    <?=$printBTN;?>
                                                    <?=$downloadBTN;?>
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
                                    <?php if (!empty($assigned_offer_letters) ) { ?>
                                        <?php $assigned_offer_letters = array_reverse($assigned_offer_letters);  ?>
                                        <?php foreach ($assigned_offer_letters as $document) { ?>
                                            <?php $pdBtn = getPDBTN($document, 'btn-info'); ?> 
                                            <tr>
                                                <td class="col-sm-12 col-xs-12">
                                                    <?php
                                                        echo $document['document_title']. ( $document['is_required'] == 1 ? ' <i class="fa fa-asterisk jsTooltip" style="color: #cc1100;" aria-hidden="true" title="'.($requiredMessage).'"></i>' : '' ).'';
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
                                                                    <?=$pdBtn['pm'].$pdBtn['dm'];?>
                                                               <a href="<?php echo $document_offer_letter_base . '/' . $document['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                             </div>
                                                         <?php } else { ?>
                                                          <div class="">
                                                          <?=$pdBtn['pm'].$pdBtn['dm'];?>
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
                                                        <?=$pdBtn['pw'].$pdBtn['dw'];?>
                                                        <a href="<?php echo $document_offer_letter_base . '/' . $document['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                    </td>
                                                <?php } else { ?>
                                                    <td class="hidden-xs text-center">
                                                    <?=$pdBtn['pw'].$pdBtn['dw'];?>
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
                    $fillable_count = $w4_status + $w9_status + $i9_status;
                    if (!empty($categories_documents_completed)) { ?>
                        <h3 class="tab-title" style="color: #0000EE;">Completed Document Detail</h3>
                        <?php foreach ($categories_documents_completed as $category_document) { ?>
                            <?php if ($category_document['category_sid'] != 27) { ?>
                                <?php if(isset($category_document['documents'])){ ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="panel panel-default hr-documents-tab-content">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_completed<?php echo $category_document['category_sid']; ?>" >
                                                            <span class="glyphicon glyphicon-plus"></span>
                                                            <?php echo $category_document['name']; ?>
                                                         </a>
                                                        <div class="pull-right total-records"><b><?php echo 'Total: '.count($category_document['documents']); ?></b></div>
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
                                                                <?php if (count($category_document['documents']) > 0) { ?>
                                                                    <?php foreach ($category_document['documents'] as $document) { ?>
                                                                        <?php $pdBtn = getPDBTN($document, 'btn-info'); ?> 
                                                                        <tr>
                                                                            <td class="">
                                                                                <?php
                                                                                echo $document['document_title'] . '&nbsp;';
                                                                                echo $document['status'] ? '' : '<b>(revoked)</b>';
                                                                                echo $document['document_sid'] == 0 ? '<b> (Manual Upload)</b>' : '';
                                                                                echo $document['document_type'] == 'offer_letter' ? '<b> (Offer Letter)</b>' : '';

                                                                                if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                                    echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
                                                                                }
                                                                                ?>
                                                                                <div  class="hidden-sm hidden-lg hidden-md">
                                                                                    <?=$pdBtn['pm'].$pdBtn['dm'];?>
                                                                                    <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                                </div>
                                                                            </td>
                                                                            </td>
                                                                            <td class=" text-center hidden-xs">
                                                                                <?=$pdBtn['pw'].$pdBtn['dw'];?>
                                                                                <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                            </td>
                                                                        </tr>
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
                    <?php } if($fillable_count > 0){?>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_completed_fillable" >
                                                <span class="glyphicon glyphicon-plus"></span>
                                                Verification Documents
                                                <div class="pull-right total-records"><b><?php echo 'Total: '.$fillable_count; ?></b></div>
                                            </a>

                                        </h4>
                                    </div>

                                    <div id="collapse_completed_fillable" class="panel-collapse collapse">
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
                                                <?php if (isset($w4_form) && sizeof($w4_form) && $w4_form['status'] != 0 && !empty($w4_form['user_consent']) && $w4_form['user_consent']) { ?>
                                                    <tr>
                                                        <td class="col-lg-10 col-sm-12 col-xs-12">
                                                            <?php
                                                            echo 'W4 Fillable';
                                                            echo $w4_form['status'] ? '' : '<b>(revoked)</b>';

                                                            if (isset($w4_form['sent_date']) && $w4_form['sent_date'] != '0000-00-00 00:00:00') {
                                                                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $w4_form['sent_date'], '_this' => $this));
                                                            }
                                                            ?>
                                                           
                                                            <div class="hidden-sm hidden-lg hidden-md">
                                                                <a href="<?php echo $w4_url; ?>" class="btn btn-info">View Sign</a>
                                                            </div>
                                                        </td>
                                                        <td class="col-lg-2 hidden-xs text-center">
                                                            <a href="<?php echo $w4_url; ?>" class="btn btn-info">View Sign</a>
                                                        </td>
                                                    </tr>
                                                <?php }?>
                                                <?php if (isset($w9_form) && sizeof($w9_form) && $w9_form['status'] != 0 && !empty($w9_form['user_consent']) && $w9_form['user_consent']) { ?>
                                                    <tr>
                                                        <td class="col-lg-10">
                                                            <?php
                                                            echo 'W9 Fillable';
                                                            echo $w9_form['status'] ? '' : '<b>(revoked)</b>';

                                                            if (isset($w9_form['sent_date']) && $w9_form['sent_date'] != '0000-00-00 00:00:00') {
                                                                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $w9_form['sent_date'], '_this' => $this));
                                                            }
                                                            ?>
                                                            <div class="hidden-lg hidden-md hidden-sm">
                                                                <a href="<?php echo $w9_url; ?>" class="btn btn-info">View Sign</a>
                                                            </div>
                                                        </td>
                                                        <td class="col-lg-2 text-center hidden-xs">
                                                            <a href="<?php echo $w9_url; ?>" class="btn btn-info">View Sign</a>
                                                        </td>
                                                    </tr>
                                                <?php }?>
                                                <?php if (isset($i9_form) && sizeof($i9_form) && $i9_form['status'] != 0 && !empty($i9_form['user_consent']) && $i9_form['user_consent']) { ?>
                                                    <tr>
                                                        <td class="col-lg-10">
                                                            <?php
                                                            echo 'I9 Fillable';
                                                            echo $i9_form['status'] ? '' : '<b>(revoked)</b>';

                                                            if (isset($i9_form['sent_date']) && $i9_form['sent_date'] != '0000-00-00 00:00:00') {
                                                                echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $i9_form['sent_date'], '_this' => $this));
                                                            }
                                                            ?>
                                                             <div class="hidden-lg hidden-md hidden-sm">
                                                                <a href="<?php echo $i9_url; ?>" class="btn btn-info">View Sign</a>
                                                            </div>
                                                        </td>
                                                        <td class="col-lg-2 hidden-xs text-center">
                                                            <a href="<?php echo $i9_url; ?>" class="btn btn-info">View Sign</a>
                                                        </td>
                                                    </tr>
                                                <?php }?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } if(empty($categories_documents_completed) && $fillable_count == 0){
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
                                                        <th class="col-lg-4 text-right ">Actions</th>                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="col-lg-8">
                                                            <?php
                                                                echo $completed_offer_letter['document_title']. '&nbsp;';

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
                                                                <?=$pdBtn['pm'].$pdBtn['dm'];?>
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
                                                            <?=$pdBtn['pw'].$pdBtn['dw'];?>
                                                            <a href="<?php echo $document_offer_letter_base . '/' . $completed_offer_letter['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
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

                    <?php if(sizeof($completed_payroll_documents)){ ?>
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
                                                        <tr>
                                                            <td class="">
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
                                                                            <?=$pdBtn['pm'].$pdBtn['dm'];?>
                                                                            <a href="<?php echo $document_offer_letter_base . '/' . $document['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <div class="">
                                                                        <?=$pdBtn['pm'].$pdBtn['dm'];?>
                                                                            <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                        </div>
                                                                    <?php } ?>
                                                               </div>
                                                            </td>
                                                                <?php if ($document['document_type'] == 'offer_letter') { ?>
                                                                    <td class="hidden-xs text-center" >
                                                                        <?php
                                                                            if ($document['user_consent'] == 1) {
                                                                                $btn_name = 'View Offer Letter';
                                                                            } else {
                                                                                $btn_name = 'View Sign';
                                                                            }
                                                                        ?>
                                                                        <?=$pdBtn['pw'].$pdBtn['dw'];?>
                                                                        <a  href="<?php echo $document_offer_letter_base . '/' . $document['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                                    </td>
                                                                <?php } else { ?>
                                                                    <?=$pdBtn['pw'].$pdBtn['dw'];?>
                                                                    <td class="hidden-xs text-center">
                                                                        <a  href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                    </td>
                                                                <?php } ?>
                                                        </tr>
                                                            <?php //} ?>
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
                <?php if(isset($CompletedGeneralDocuments) && count($CompletedGeneralDocuments) > 0) { ?>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-default hr-documents-tab-content">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#jsGeneralCompletedDocuments" >
                                        <span class="glyphicon glyphicon-plus"></span>
                                          General Documents
                                        <div class="pull-right total-records"><b><?php echo 'Total: ' .count($CompletedGeneralDocuments); ?></b></div>
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
                                            <?php foreach($CompletedGeneralDocuments as $v) { 
                                                $docURL = base_url('general_info/'.( $v['document_type'] ).'');
                                                $printBTN = '<a href="'.( base_url('hr_documents_management/gpd/print/'.( $v['document_type'] ).'/'.( $user_type ).'/'.( $user_sid ).'') ).'" target="_blank" class="btn btn-info btn-orange" style="margin-right: 5px;">Print</a>';
                                                $downloadBTN = '<a href="'.( base_url('hr_documents_management/gpd/print/'.( $v['document_type'] ).'/'.( $user_type ).'/'.( $user_sid ).'') ).'" target="_blank" class="btn btn-info btn-black">Download</a>';
                                            ?>
                                            <tr>
                                                <td class="">
                                                    <?php
                                                        echo ucwords(str_replace('_', ' ', $v['document_type']));
                                                        echo "<br><b>Assigned On: </b>" . reset_datetime(array('datetime' => $v['updated_at'], '_this' => $this));
                                                    ?>
                                                    <div class="hidden-sm hidden-lg hidden-md">
                                                        <?=$printBTN;?>
                                                        <?=$downloadBTN;?>
                                                       <a href="<?php echo $docURL; ?>" class="btn btn-info">View Sign</a>
                                                   </div>
                                                </td>
                                                <td class="col-lg-4 hidden-xs text-center">
                                                    <?=$printBTN;?>
                                                    <?=$downloadBTN;?>
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
                                <?php if(isset($category_document['documents'])){ ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="panel panel-default hr-documents-tab-content">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_no_action<?php echo $category_document['category_sid']; ?>" >
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
                                                            <div class="pull-right total-records"><b><?php echo 'Total: '.$total_record; ?></b></div>
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
                                                                                        <?=$pdBtn['pm'].$pdBtn['dm'];?>
                                                                                            <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td class="text-center hidden-xs">
                                                                                    <?=$pdBtn['pw'].$pdBtn['dw'];?>
                                                                                        <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
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
                    <?php }else{
                        ?>
                        <td colspan="7" class="col-lg-12 text-center"><b>No Document(s) Found!</b></td>
                        <?php
                    } ?>

                    <?php if(sizeof($no_action_required_payroll_documents)){ ?>
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
                                                        <?php foreach ($no_action_required_payroll_documents as $document) { ?>
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
                                                                            <?=$pdBtn['pm'].$pdBtn['dm'];?>
                                                                            <a href="<?php echo $document_offer_letter_base . '/' . $document['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <div class="">
                                                                        <?=$pdBtn['pm'].$pdBtn['dm'];?>
                                                                            <a href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                        </div>
                                                                    <?php } ?>
                                                               </div>
                                                            </td>
                                                                <?php if ($document['document_type'] == 'offer_letter') { ?>
                                                                    <td class=" hidden-xs text-center" >
                                                                        <?php
                                                                            if ($document['user_consent'] == 1) {
                                                                                $btn_name = 'View Offer Letter';
                                                                            } else {
                                                                                $btn_name = 'View Sign';
                                                                            }
                                                                        ?>
                                                                        <?=$pdBtn['pw'].$pdBtn['dw'];?>
                                                                        <a  href="<?php echo $document_offer_letter_base . '/' . $document['sid']; ?>" class="btn btn-info"><?php echo $btn_name; ?></a>
                                                                    </td>
                                                                <?php } else { ?>
                                                                    <td class=" hidden-xs text-center">
                                                                    <?=$pdBtn['pw'].$pdBtn['dw'];?>
                                                                        <a  href="<?php echo $document_d_base . '/' . $document['sid']; ?>" class="btn btn-info">View Sign</a>
                                                                    </td>
                                                                <?php } ?>
                                                        </tr>
                                                            <?php //} ?>
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
<style>
    {
        width:100%;margin-top:10px;
    }
    @media (max-width: 576px) { 
        .total-records{
            float:none !important;
            
        }
        
     }
</style>

