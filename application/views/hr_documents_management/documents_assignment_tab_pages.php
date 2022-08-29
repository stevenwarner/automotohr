<?php
    $ncd = $pp = $cd = $nad = 0;
    $canAccessDocument = hasDocumentsAssigned($session['employer_detail']);

    $action_btn_flag = true;
    if ($pp_flag == 1 || $canAccessDocument) {
        $action_btn_flag = false;
    }

    $document_all_permission = false;
    if($session['employer_detail']['access_level_plus'] == 1 || $canAccessDocument) {
        $document_all_permission = true;
    } 

    //
    $completedDocumentsList = [];
    $notCompletedDocumentsList = [];
    $noActionRequiredDocumentsList = [];

    $job_list_sid = !empty($job_list_sid) ? $job_list_sid : 0;
?>
<style>
    .download_document_note{
        display: block;
        margin-top: 20px;
    }
    .jsCategoryManagerBTN{ display: none;}
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
            <div id="in_complete_doc_details" class="tab-pane fade in active hr-innerpadding">
                <div class="panel-body">
                    <h2 class="tab-title">Not Completed Document Detail</h2>
                    <?php if(sizeof($assigned_documents)){?>
                        <div class="table-responsive full-width">
                            <table class="table table-plane js-uncompleted-docs">
                                <thead>
                                    <tr>
                                        <th class="col-lg-8">Document Name</th>
                                        <th class="col-lg-4 text-center" colspan="4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($assigned_documents)) { ?>
                                        <?php $assigned_documents = array_reverse($assigned_documents);  ?>
                                        <?php foreach ($assigned_documents as $document) { ?>
                                            <?php if (!in_array($document['sid'], $payroll_documents_sids)) { ?>
                                                <?php if ($document['archive'] != 1) { ?>
                                                    <?php if ($document['status'] != 0) { ?>
                                                        <?php $ncd++; $notCompletedDocumentsList[] = $document; ?>
                                                        <tr>
                                                            <td class="col-lg-6">
                                                                <?=getDocumentReadableInfo($document, 'not_completed') ?>
                                                            </td>
                                                            <td class="col-lg-1">
                                                                <?=getDocumentTabPagesButton($document, 'print', ['document_tab' => 'uncompleted']) ?>

                                                                <?=getDocumentTabPagesButton($document, 'download', ['document_tab' => 'uncompleted']) ?>
                                                            </td>
                                                            <td class="col-lg-1">
                                                                <?=getDocumentTabPagesButton($document, 'preview_assigned') ?>

                                                                <?=getDocumentTabPagesButton($document, 'modify_document', ['data_type' => 'notCompletedDocuments','permissions' => $document_all_permission]) ?>   
                                                            </td>
                                                            <td class="col-lg-1">
                                                                <?=getDocumentTabPagesButton($document, 'manage_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid, 'permissions' => $document_all_permission]) ?>
                                                        
                                                                <?=getDocumentTabPagesButton($document, 'email_reminder', ['user_type' => $user_type, 'employee_info' => $session['employer_detail'], 'permissions' => $document_all_permission]) ?>

                                                                <?=getDocumentTabPagesButton($document, 'view_approver', ['user_type' => $user_type, 'user_sid' => $user_sid, 'permissions' => $document_all_permission]) ?>  
                                                            </td>    
                                                            <td class="col-lg-1">
                                                                <?=getDocumentTabPagesButton($document, 'manage_category', ['permissions' => $document_all_permission]) ?>

                                                                <?=getDocumentTabPagesButton($document, 'revoke_library_document', ['permissions' => $document_all_permission]) ?> 

                                                                <?=getDocumentTabPagesButton($document, 'employer_section', ['current_user_signature' => $current_user_signature, 'permissions' => $document_all_permission]) ?>    
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php }?>
                                    <?php if(!sizeof($assigned_documents)) { ?>
                                        <tr>
                                            <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Found!</b></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                    <!--  -->
                    <?php if (sizeof($uncompleted_offer_letter) && 
                        has_approval(
                            $uncompleted_offer_letter[0]['allowed_roles'],
                            $uncompleted_offer_letter[0]['allowed_departments'],
                            $uncompleted_offer_letter[0]['allowed_teams'],
                            $uncompleted_offer_letter[0]['allowed_employees'], [
                                'user_id' => $session['employer_detail']['sid'],
                                'access_level_plus' => $session['employer_detail']['access_level_plus'],
                                'pay_plan_flag' => $session['employer_detail']['pay_plan_flag'],
                                'access_level' => $session['employer_detail']['access_level']
                            ],
                            $uncompleted_offer_letter[0]['visible_to_payroll']
                        )
                    ) {?>
                        <div class="row">
                            <div class="col-xs-12">
                                <br />
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle open_not_completed_doc" data-toggle="collapse" data-parent="#accordion" href="#collapse_uncompleted_offer_letter">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <?php echo 'Offer Letter / Pay Plan'; ?>
                                                <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($uncompleted_offer_letter); ?></b></div>
                                            </a>

                                        </h4>
                                    </div>

                                    <div id="collapse_uncompleted_offer_letter" class="panel-collapse collapse in">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-8">Document Name</th>
                                                        <th class="col-lg-2 text-right">Actions</th>
                                                        <th class="col-lg-2 text-center">&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($uncompleted_offer_letter)) { ?>
                                                        <?php foreach ($uncompleted_offer_letter as $document) { ?>
                                                        <?php 
                                                            $GLOBALS['uofl'][] = $document;
                                                            $ncd++; ?>
                                                            <tr>
                                                                <td class="col-lg-8">
                                                                    <?=getDocumentReadableInfo($document, 'not_completed') ?>
                                                                </td>
                                                                <td class="col-lg-1">
                                                                    <?=getDocumentTabPagesButton($document, 'print', ['document_tab' => 'uncompleted']) ?>

                                                                    <?=getDocumentTabPagesButton($document, 'download', ['document_tab' => 'uncompleted']) ?>
                                                                </td>
                                                                <td class="col-lg-1">
                                                                    <?=getDocumentTabPagesButton($document, 'preview_assigned') ?>

                                                                    <?=getDocumentTabPagesButton($document, 'modify_document', ['data_type' => 'notCompletedOfferLetters','permissions' => $document_all_permission]) ?>   
                                                                </td>
                                                                <td class="col-lg-1">
                                                                    <?=getDocumentTabPagesButton($document, 'manage_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid, 'permissions' => $document_all_permission]) ?>

                                                                    <?=getDocumentTabPagesButton($document, 'view_approver', ['user_type' => $user_type, 'user_sid' => $user_sid, 'permissions' => $document_all_permission]) ?>  
                                                                </td>
                                                                <td class="col-lg-1">

                                                                    <?=getDocumentTabPagesButton($document, 'archive_offer_letter', ['permissions' => $document_all_permission]) ?> 

                                                                    <?=getDocumentTabPagesButton($document, 'employer_section', ['current_user_signature' => $current_user_signature, 'permissions' => $document_all_permission]) ?>    
                                                                </td> 
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
                    <!--  -->
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
                                                <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($uncompleted_payroll_documents); ?></b></div>
                                            </a>

                                        </h4>
                                    </div>

                                    <div id="collapse_uncompleted_payroll_documents" class="panel-collapse collapse in">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-8">Document Name</th>
                                                        <th class="col-lg-2 text-right">Actions</th>
                                                        <th class="col-lg-2 text-center">&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($uncompleted_payroll_documents)) { ?>
                                                        <?php foreach ($uncompleted_payroll_documents as $document) { ?>
                                                        <?php
                                                            $GLOBALS['ad'][] = $documents; $ncd++;  $notCompletedDocumentsList[] = $document; 
                                                            if (!empty($documents)) { 
                                                                $GLOBALS['ad'][] = $documents; 
                                                            }    
                                                            $ncd++; 
                                                        ?>
                                                            <tr>
                                                                <td class="col-lg-8">
                                                                    <?=getDocumentReadableInfo($document, 'not_completed') ?>
                                                                </td> 
                                                                <td class="col-lg-1">
                                                                    <?=getDocumentTabPagesButton($document, 'print', ['document_tab' => 'uncompleted']) ?>

                                                                    <?=getDocumentTabPagesButton($document, 'download', ['document_tab' => 'uncompleted']) ?>
                                                                </td>
                                                                <td class="col-lg-1">
                                                                    <?=getDocumentTabPagesButton($document, 'preview_assigned') ?>

                                                                    <?=getDocumentTabPagesButton($document, 'modify_document', ['data_type' => 'notCompletedDocuments','permissions' => $document_all_permission]) ?>   
                                                                </td>
                                                                <td class="col-lg-1">
                                                                    <?=getDocumentTabPagesButton($document, 'manage_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid, 'permissions' => $document_all_permission]) ?>

                                                                    <?=getDocumentTabPagesButton($document, 'view_approver', ['user_type' => $user_type, 'user_sid' => $user_sid, 'permissions' => $document_all_permission]) ?>  
                                                                </td>    
                                                                <td class="col-lg-1">
                                                                    <?=getDocumentTabPagesButton($document, 'manage_category', ['permissions' => $document_all_permission]) ?>

                                                                    <?=getDocumentTabPagesButton($document, 'revoke_library_document', ['permissions' => $document_all_permission]) ?> 

                                                                    <?=getDocumentTabPagesButton($document, 'employer_section', ['current_user_signature' => $current_user_signature, 'permissions' => $document_all_permission]) ?>    
                                                                </td>
                                                            </tr>
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
            </div>
            <!-- Not Completed Document End -->

            <!-- Signed Document Start -->
            <div id="signed_doc_details" class="tab-pane fade in hr-innerpadding">
                <div class="panel-body">
                    <!-- Zip download button -->
                    <?=getDocumentTabPagesButton($document, 'download_previous_zip', ['user_type' => $user_type, 'downloadDocumentData' => $downloadDocumentData, 'permissions' => $document_all_permission]) ?>   
                    <!-- Category Completed Document Start -->
                    <h2 class="tab-title">Completed Document Detail
                        <span class="pull-right">
                            <?=getDocumentTabPagesButton($document, 'download_all_documents', ['user_type' => $user_type, 'user_sid' => $user_sid, 'type' => "completed", 'permissions' => $document_all_permission]) ?>
                        </span>
                        <hr /> 
                    </h2>
                    <?php if (!empty($categories_documents_completed)) { ?>
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
                                                            <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($category_document['documents']); ?></b></div>
                                                        </a>

                                                    </h4>
                                                </div>

                                                <div id="collapse_completed<?php echo $category_document['category_sid']; ?>" class="panel-collapse collapse">
                                                    <div class="table-responsive full-width">
                                                        <table class="table table-plane">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-lg-8">Document Name</th>
                                                                    <th class="col-lg-2 text-right">Actions</th>
                                                                    <th class="col-lg-2 text-center">&nbsp;</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($category_document['documents'])) { ?>
                                                                    <?php foreach ($category_document['documents'] as $document) { ?>
                                                                        <?php 
                                                                            $completedDocumentsList[] = $document;
                                                                            $GLOBALS['ad'][] = $document;
                                                                            $cd++; 
                                                                        ?>
                                                                        <?php if ($document["is_history"] == 0) { ?>
                                                                            <tr>
                                                                                <td class="col-lg-8">
                                                                                    <?=getDocumentReadableInfo($document, 'completed') ?>
                                                                                </td>
                                                                                <td class="col-lg-1">
                                                                                    <?=getDocumentTabPagesButton($document, 'print', ['document_tab' => 'completed']) ?>

                                                                                    <?=getDocumentTabPagesButton($document, 'download', ['document_tab' => 'completed']) ?>

                                                                                    <?=getDocumentTabPagesButton($document, 'view_auth_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid, 'permissions' => $document_all_permission]) ?> 
                                                                                </td>
                                                                                <td class="col-lg-1">
                                                                                    <?=getDocumentTabPagesButton($document, 'preview_assigned') ?>

                                                                                    <?=getDocumentTabPagesButton($document, 'modify_document', ['data_type' => 'completedDocuments','permissions' => $document_all_permission]) ?>   
                                                                                </td>
                                                                                <td class="col-lg-1">
                                                                                    <?=getDocumentTabPagesButton($document, 'preview_submitted') ?>

                                                                                    <?=getDocumentTabPagesButton($document, 'manage_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid, 'permissions' => $document_all_permission]) ?>

                                                                                    <?=getDocumentTabPagesButton($document, 'view_approver', ['user_type' => $user_type, 'user_sid' => $user_sid, 'permissions' => $document_all_permission]) ?>  
                                                                                </td>
                                                                                <td class="col-lg-1">

                                                                                    <?=getDocumentTabPagesButton($document, 'manage_category', ['permissions' => $document_all_permission]) ?>

                                                                                    <?=getDocumentTabPagesButton($document, 'revoke_library_document', ['permissions' => $document_all_permission]) ?>

                                                                                    <?=getDocumentTabPagesButton($document, 'employer_section', ['current_user_signature' => $current_user_signature, 'permissions' => $document_all_permission]) ?>    
                                                                                </td> 
                                                                            </tr>
                                                                        <?php } else { ?>
                                                                            <?php $this->load->view('hr_documents_management/document_assign_history_row', ['user_sid' => $user_sid, 'user_type' => $user_type, 'history_document' => $document, "document_all_permission" => $document_all_permission]); ?>
                                                                        <?php } ?>
                                                                        <?php if (!empty($document["history"])) { ?>
                                                                            <?php foreach ($document["history"] as $history_document) { ?>
                                                                                <?php $cd++; ?>
                                                                                <?php $this->load->view('hr_documents_management/document_assign_history_row', ['user_sid' => $user_sid, 'user_type' => $user_type, 'history_document' => $history_document, "document_all_permission" => $document_all_permission]); ?>
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
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?>
                        <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Found!</b></td>
                    <?php } ?>

                    <?php if (sizeof($completed_offer_letter)  && 
                        has_approval(
                            $completed_offer_letter[0]['allowed_roles'],
                            $completed_offer_letter[0]['allowed_departments'],
                            $completed_offer_letter[0]['allowed_teams'],
                            $completed_offer_letter[0]['allowed_employees'], 
                            [
                                'user_id' => $session['employer_detail']['sid'],
                                'access_level_plus' => $session['employer_detail']['access_level_plus'],
                                'pay_plan_flag' => $session['employer_detail']['pay_plan_flag'],
                                'access_level' => $session['employer_detail']['access_level']
                            ],
                            $completed_offer_letter[0]['visible_to_payroll']
                        )) { ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <br />
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_completed_offer_letter">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <?php //echo $category_document['name']; ?>
                                                <?php echo 'Offer Letter / Pay Plan'; ?>
                                                <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($completed_offer_letter); ?></b></div>
                                            </a>

                                        </h4>
                                    </div>

                                    <div id="collapse_completed_offer_letter" class="panel-collapse collapse">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-8">Document Name</th>
                                                        <th class="col-lg-2 text-right">Actions</th>
                                                        <th class="col-lg-2 text-center">&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($completed_offer_letter)) { ?>
                                                        <?php foreach ($completed_offer_letter as $document) { ?>
                                                        <?php $GLOBALS['uofl'][] = $document; $cd++; ?>
                                                            <tr>
                                                                <td class="col-lg-8">
                                                                    <?=getDocumentReadableInfo($document, 'completed') ?>
                                                                </td>
                                                                <td class="col-lg-1">
                                                                    <?=getDocumentTabPagesButton($document, 'print', ['document_tab' => 'completed']) ?>

                                                                    <?=getDocumentTabPagesButton($document, 'download', ['document_tab' => 'completed']) ?>

                                                                    <?=getDocumentTabPagesButton($document, 'view_auth_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid, 'permissions' => $document_all_permission]) ?>
                                                                </td>
                                                                <td class="col-lg-1">
                                                                    <?=getDocumentTabPagesButton($document, 'preview_assigned') ?>

                                                                    <?=getDocumentTabPagesButton($document, 'modify_document', ['data_type' => 'completedOfferLetters','permissions' => $document_all_permission]) ?>   
                                                                </td>
                                                                <td class="col-lg-1">
                                                                    <?=getDocumentTabPagesButton($document, 'preview_submitted') ?>

                                                                    <?=getDocumentTabPagesButton($document, 'manage_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid, 'permissions' => $document_all_permission]) ?>

                                                                    <?=getDocumentTabPagesButton($document, 'view_approver', ['user_type' => $user_type, 'user_sid' => $user_sid, 'permissions' => $document_all_permission]) ?>  
                                                                </td>
                                                                <td class="col-lg-1">

                                                                    <?=getDocumentTabPagesButton($document, 'archive_offer_letter', ['permissions' => $document_all_permission]) ?> 

                                                                    <?=getDocumentTabPagesButton($document, 'employer_section', ['current_user_signature' => $current_user_signature, 'permissions' => $document_all_permission]) ?>    
                                                                </td>
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
                                                <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($completed_payroll_documents); ?></b></div>
                                            </a>

                                        </h4>
                                    </div>

                                    <div id="collapse_completed_payroll_documents" class="panel-collapse collapse">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-8">Document Name</th>
                                                        <th class="col-lg-2 text-right">Actions</th>
                                                        <th class="col-lg-2 text-center">&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($completed_payroll_documents)) { ?>
                                                        <?php foreach ($completed_payroll_documents as $document) { ?>
                                                            <?php
                                                                $GLOBALS['ad'][] = $document; 
                                                                $completedDocumentsList[] = $document;
                                                                $cd++; 
                                                            ?>
                                                            <?php if ($document["is_history"] == 0) { ?>
                                                                <tr>
                                                                    <td class="col-lg-8">
                                                                        <?=getDocumentReadableInfo($document, 'completed') ?>
                                                                    </td>
                                                                    <td class="col-lg-1">
                                                                        <?=getDocumentTabPagesButton($document, 'print', ['document_tab' => 'completed']) ?>

                                                                        <?=getDocumentTabPagesButton($document, 'download', ['document_tab' => 'completed']) ?>

                                                                        <?=getDocumentTabPagesButton($document, 'view_auth_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid, 'permissions' => $document_all_permission]) ?>
                                                                    </td>
                                                                    <td class="col-lg-1">
                                                                        <?=getDocumentTabPagesButton($document, 'preview_assigned') ?>
                                                                    </td>
                                                                    <td class="col-lg-1">
                                                                        <?=getDocumentTabPagesButton($document, 'preview_submitted') ?>

                                                                        <?=getDocumentTabPagesButton($document, 'manage_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid, 'permissions' => $document_all_permission]) ?>

                                                                        <?=getDocumentTabPagesButton($document, 'view_approver', ['user_type' => $user_type, 'user_sid' => $user_sid, 'permissions' => $document_all_permission]) ?>  
                                                                    </td>    
                                                                    <td class="col-lg-1">
                                                                        <?=getDocumentTabPagesButton($document, 'manage_category', ['permissions' => $document_all_permission]) ?>

                                                                        <?=getDocumentTabPagesButton($document, 'revoke_library_document', ['permissions' => $document_all_permission]) ?> 

                                                                        <?=getDocumentTabPagesButton($document, 'employer_section', ['current_user_signature' => $current_user_signature, 'permissions' => $document_all_permission]) ?>    
                                                                    </td>
                                                                </tr>
                                                            <?php } else { ?>
                                                                <?php $this->load->view('hr_documents_management/document_assign_history_row', ['user_sid' => $user_sid, 'user_type' => $user_type, 'history_document' => $document, "document_all_permission" => $document_all_permission]); ?>
                                                            <?php } ?>
                                                            <?php if (!empty($document["history"])) { ?>
                                                                <?php foreach ($document["history"] as $history_document) { ?>
                                                                    <?php $cd++; ?>
                                                                    <?php $this->load->view('hr_documents_management/document_assign_history_row', ['user_sid' => $user_sid, 'user_type' => $user_type, 'history_document' => $history_document, "document_all_permission" => $document_all_permission]); ?>
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

                    <?php if (!empty($completed_w4) || !empty($completed_w9) || !empty($completed_i9)) { ?>
                        <?php $cvd = count($completed_w4) + count($completed_w9) + count($completed_i9); ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="panel panel-default hr-documents-tab-content">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_completed-1">
                                                <span class="glyphicon glyphicon-plus"></span>
                                                Employment Eligibility Verification Document
                                                <div class="pull-right total-records">
                                                    <b>&nbsp;Total: <span class="js-cdi"><?php echo $cvd; ?></span> </b>
                                                </div>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse_completed-1" class="panel-collapse collapse">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane">
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
                                                                <?php echo date('M d Y, D', strtotime($w4_form['sent_date']));?>
                                                            </td>
                                                            <td class="col-lg-2">
                                                                <?php echo $w4_form['form_status']; ?>
                                                            </td>
                                                            <td class="col-lg-2">
                                                                <?= getdocumenttabpagesbutton('', 'view_W4', ['form_status' => $w4_form['form_status'], 'form_sid' => $w4_form['sid']]) ?>
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
                                                                <?php echo date('M d Y, D', strtotime($w9_form['sent_date']));?>
                                                            </td>
                                                            <td class="col-lg-2">
                                                                <?php echo $w9_form['form_status']; ?>
                                                            </td>
                                                            <td class="col-lg-2">
                                                                <?= getdocumenttabpagesbutton('', 'view_W9', ['form_status' => $w9_form['form_status'], 'form_sid' => $w9_form['sid']]) ?>
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
                                                                <?php echo date('M d Y, D', strtotime($i9_form['sent_date']));?>
                                                            </td>
                                                            <td class="col-lg-2">
                                                                <?php echo $i9_form['form_status']; ?>
                                                            </td>
                                                            <td class="col-lg-2">
                                                                <?= getdocumenttabpagesbutton('', 'view_I9', ['form_status' => $i9_form['form_status'], 'form_sid' => $i9_form['sid']]) ?>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
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
            <!-- Signed Document End -->

            <!-- No Action Required Document Start -->
            <div id="no_action_required_doc_details" class="tab-pane fade in hr-innerpadding">
                <div class="panel-body">
                    <!-- Category No Action Required Document Start -->
                    <?php if (!empty($categories_no_action_documents)) { ?>
                        <h2 class="tab-title">No Action Required Document Detail
                        <span class="pull-right">
                            <?=getDocumentTabPagesButton($document, 'download_all_documents', ['user_type' => $user_type, 'user_sid' => $user_sid, 'type' => "noActionRequired", 'permissions' => true]) ?>
                        </span>
                        <hr /> 
                    </h2>
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
                                                            <div class="pull-right total-records"><b><?php echo '&nbsp;Total: ' . $total_record; ?></b></div>
                                                        </a>

                                                    </h4>
                                                </div>

                                                <div id="collapse_no_action<?php echo $category_document['category_sid']; ?>" class="panel-collapse collapse">
                                                    <div class="table-responsive full-width">
                                                        <table class="table table-plane">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-lg-6">Document Name</th>
                                                                    <th class="col-lg-6 text-center" colspan="4">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (count($category_document['documents']) > 0) { ?>
                                                                    <?php foreach ($category_document['documents'] as $document) { ?>
                                                                        <?php if ($document['archive'] != 1 && $document['manual_document_type'] != 'offer_letter') { ?>
                                                                            <?php $noActionRequiredDocumentsList[] = $document; ?>
                                                                            <?php $nad++; ?>
                                                                            <tr>
                                                                                <td class="col-lg-8">
                                                                                    <?=getDocumentReadableInfo($document, 'completed') ?>
                                                                                </td>
                                                                                <td class="col-lg-1">
                                                                                    <?=getDocumentTabPagesButton($document, 'print', ['document_tab' => 'no_action']) ?>

                                                                                    <?=getDocumentTabPagesButton($document, 'download', ['document_tab' => 'no_action']) ?>
                                                                                </td>
                                                                                <td class="col-lg-1">
                                                                                    <?=getDocumentTabPagesButton($document, 'preview_assigned') ?>

                                                                                    <?=getDocumentTabPagesButton($document, 'view_approver', ['user_type' => $user_type, 'user_sid' => $user_sid, 'permissions' => $document_all_permission]) ?>  

                                                                                </td>
                                                                                <td class="col-lg-1">
                                                                                    <?=getDocumentTabPagesButton($document, 'manage_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid, 'permissions' => $document_all_permission]) ?>

                                                                                    <?=getDocumentTabPagesButton($document, 'manage_category', ['permissions' => $document_all_permission]) ?>
                                                                                </td> 
                                                                                <td class="col-lg-1">
                                                                                    <?=getDocumentTabPagesButton($document, 'edit_manual_document', ['action_btn_flag' => $action_btn_flag, 'permissions' => $document_all_permission, 'data_type' => 'noActionDocuments', 'no_action_document_categories' => $no_action_document_categories]) ?>

                                                                                    <?=getDocumentTabPagesButton($document, 'archive_manual_upload_document', ['action_btn_flag' => $action_btn_flag, 'permissions' => $document_all_permission, 'security_details' => $security_details, "current_url" => current_url()]) ?>

                                                                                    <?=getDocumentTabPagesButton($document, 'revoke_library_document', ['permissions' => $document_all_permission]) ?> 
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
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
                    <?php }else{
                        ?>
                        <td colspan="7" class="col-lg-12 text-center"><b class="js-error">No Document(s) Found!</b></td>
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
                                                <div class="pull-right total-records"><b>&nbsp;Total: <?php echo count($no_action_required_payroll_documents); ?></b></div>
                                            </a>

                                        </h4>
                                    </div>

                                    <div id="collapse_no_action_required_payroll_documents" class="panel-collapse collapse">
                                        <div class="table-responsive full-width">
                                            <table class="table table-plane">
                                                <thead>
                                                    <tr>
                                                        <th class="col-lg-8">Document Name</th>
                                                        <th class="col-lg-2 text-right">Actions</th>
                                                        <th class="col-lg-2 text-center">&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($no_action_required_payroll_documents)) { ?>
                                                        <?php foreach ($no_action_required_payroll_documents as $document) { ?>
                                                        <?php $nad++; $noActionRequiredDocumentsList[] = $document; ?>
                                                            <tr>
                                                                <td class="col-lg-8">
                                                                    <?=getDocumentReadableInfo($document, 'completed') ?>
                                                                </td>
                                                                <td class="col-lg-1">
                                                                    <?=getDocumentTabPagesButton($document, 'print', ['document_tab' => 'no_action']) ?>

                                                                    <?=getDocumentTabPagesButton($document, 'download', ['document_tab' => 'no_action']) ?>
                                                                </td>
                                                                <td class="col-lg-1">
                                                                    <?=getDocumentTabPagesButton($document, 'preview_assigned') ?>

                                                                    <?=getDocumentTabPagesButton($document, 'view_approver', ['user_type' => $user_type, 'user_sid' => $user_sid, 'permissions' => $document_all_permission]) ?>  
                                                                </td>
                                                                <td class="col-lg-1">
                                                                    <?=getDocumentTabPagesButton($document, 'manage_document', ['user_type' => $user_type, 'user_sid' => $user_sid, 'job_list_sid' => $job_list_sid, 'permissions' => $document_all_permission]) ?>

                                                                    <?=getDocumentTabPagesButton($document, 'manage_category', ['permissions' => $document_all_permission]) ?>
                                                                </td> 
                                                                <td class="col-lg-1">
                                                                    <?=getDocumentTabPagesButton($document, 'edit_manual_document', ['action_btn_flag' => $action_btn_flag, 'permissions' => $document_all_permission, 'data_type' => 'noActionDocuments', 'no_action_document_categories' => $no_action_document_categories]) ?>

                                                                    <?=getDocumentTabPagesButton($document, 'archive_manual_upload_document', ['action_btn_flag' => $action_btn_flag, 'permissions' => $document_all_permission, 'security_details' => $security_details, "current_url" => current_url()]) ?>

                                                                    <?=getDocumentTabPagesButton($document, 'revoke_library_document', ['permissions' => $document_all_permission]) ?> 
                                                                </td>
                                                            </tr>   
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

<script>
    $('.js-ncd').text(<?=$ncd;?>);
    $('.js-pp').text(<?=$pp;?>);
    $('.js-cd').text(<?=$cd;?>);
    $('.js-nad').text(<?=$nad;?>);
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
                    alertify.alert("Success", 'Document Library Revoke Successfully', function () {
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


    //
    function sendDocumentByEmail(
        assignedDocumentSid
    ){
        $.post("<?=base_url('hr_documents_management/send_document_to_sign');?>", {
            assignedDocumentSid: assignedDocumentSid
        }, (resp) => {
            //
            $('#my_loader').hide(0);
            $('#my_loader .loader-text').html('Please wait while we generate your E-Signature...');
            $('body').css('overflow-y', 'auto');
            //
            if(resp.Status === false){
                alertify.alert('WARNING!', resp.Response, () => {});
                return;
            }
            //
            alertify.alert('SUCCESS!', resp.Response, () => {});
            return;
        });
    }

    //
    function offer_letter_archive (document_sid) {
        
        var baseURI = "<?=base_url('hr_documents_management/handler');?>";

        var formData = new FormData();
        formData.append('document_sid', document_sid);
        formData.append('action', 'change_offer_letter_archive_status');

        $.ajax({
            url: baseURI,
            data: formData,
            method: 'POST',
            processData: false,
            contentType: false
        }).done(function(resp){
            var successMSG = 'Offer letter archived successfully.';
            alertify.alert('SUCCESS!', successMSG, function(){
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