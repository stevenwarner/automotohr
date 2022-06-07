<?php
$totalRecords = 0;
$trs = '';
// For I9
if (isset($verificationDocuments['I9'])) :
    $totalRecords++;
    //
    $ft = 'primary';
    $txt = 'NOT INITIATED';
    $txt2 = 'Initiate';
    $cl = 'success';
    //
    if ($verificationDocuments['I9']['status'] == 2) {
        $ft = 'success';
        $txt = 'COMPLETED';
        $txt2 = 'Re-Initiate';
        $cl = 'warning';
    } else if ($verificationDocuments['I9']['status'] == 1) {
        $ft = 'warning';
        $txt = 'INITIATED';
        $txt2 = 'Complete';
        $cl = 'success';
    }
    $trs .= '<tr class="jsDoc" data-id="' . ($verificationDocuments['I9']['sid']) . '" data-type="I9" data-status="'.($verificationDocuments['I9']['status']).'">';
    $trs .= '   <td class="col-lg-2">';
    $trs .= '       <strong>I9</strong>';
    $trs .= '   </td>';
    $trs .= '   <td class="col-lg-1">';
    $trs .= '       <span class="text-' . ($ft) . '">' . ($txt) . '</span>';
    $trs .= '   </td>';
    $trs .= '   <td class="col-lg-2">';
    $trs .= '       <span>' . ($verificationDocuments['I9']['assigned_on'] ? formatDateToDB($verificationDocuments['I9']['assigned_on'], DB_DATE_WITH_TIME, DATE_WITH_TIME) : '-') . '</span>';
    $trs .= '   </td>';
    $trs .= '   <td class="col-lg-2">';
    $trs .= '       <span>' . ($verificationDocuments['I9']['completed_on'] ? formatDateToDB($verificationDocuments['I9']['completed_on'], DB_DATE_WITH_TIME, DATE_WITH_TIME) : '-') . '</span>';
    $trs .= '   </td>';
    $trs .= '   <td></td>';
    $trs .= '   <td></td>';
    $trs .= '   <td>';
    $trs .= '       <button class="btn btn-block csRadius5 btn-' . ($cl) . ' jsInitiateVerificationDocuments">';
    $trs .=         $txt2;
    $trs .= '       </button>';
    $trs .= '   </td>';
    $trs .= '</tr>';
endif;
// For W9
if (isset($verificationDocuments['W9'])) :
    $totalRecords++;
    //
    $ft = 'primary';
    $txt = 'NOT INITIATED';
    $txt2 = 'Initiate';
    $cl = 'success';
    //
    if ($verificationDocuments['W9']['status'] == 2) {
        $ft = 'success';
        $txt = 'COMPLETED';
        $txt2 = 'Re-Initiate';
        $cl = 'warning';
    } else if ($verificationDocuments['W9']['status'] == 1) {
        $ft = 'warning';
        $txt = 'INITIATED';
        $txt2 = 'Complete';
        $cl = 'success';
    }
    $trs .= '<tr class="jsDoc" data-id="' . ($verificationDocuments['W9']['sid']) . '" data-type="W9">';
    $trs .= '   <td class="col-lg-2">';
    $trs .= '       <strong>W9</strong>';
    $trs .= '   </td>';
    $trs .= '   <td class="col-lg-1">';
    $trs .= '       <span class="text-' . ($ft) . '">' . ($txt) . '</span>';
    $trs .= '   </td>';
    $trs .= '   <td class="col-lg-2">';
    $trs .= '       <span>' . ($verificationDocuments['W9']['assigned_on'] ? formatDateToDB($verificationDocuments['W9']['assigned_on'], DB_DATE_WITH_TIME, DATE_WITH_TIME) : '-') . '</span>';
    $trs .= '   </td>';
    $trs .= '   <td class="col-lg-2">';
    $trs .= '       <span>' . ($verificationDocuments['W9']['completed_on'] ? formatDateToDB($verificationDocuments['W9']['completed_on'], DB_DATE_WITH_TIME, DATE_WITH_TIME) : '-') . '</span>';
    $trs .= '   </td>';
    $trs .= '   <td></td>';
    $trs .= '   <td></td>';
    $trs .= '   <td>';
    $trs .= '       <button class="btn btn-block csRadius5 btn-' . ($cl) . ' jsInitiateVerificationDocuments">';
    $trs .=         $txt2;
    $trs .= '       </button>';
    $trs .= '   </td>';
    $trs .= '</tr>';
endif;
// For W4
if (isset($verificationDocuments['W4'])) :
    $totalRecords++;
    //
    $ft = 'primary';
    $txt = 'NOT INITIATED';
    $txt2 = 'Initiate';
    $cl = 'success';
    //
    if ($verificationDocuments['W4']['status'] == 2) {
        $ft = 'success';
        $txt = 'COMPLETED';
        $txt2 = 'Re-Initiate';
        $cl = 'warning';
    } else if ($verificationDocuments['W4']['status'] == 1) {
        $ft = 'warning';
        $txt = 'INITIATED';
        $txt2 = 'Complete';
        $cl = 'success';
    }
    $trs .= '<tr class="jsDoc" data-id="' . ($verificationDocuments['W4']['sid']) . '" data-type="W4">';
    $trs .= '   <td class="col-lg-2">';
    $trs .= '       <strong>W4</strong>';
    $trs .= '   </td>';
    $trs .= '   <td class="col-lg-1">';
    $trs .= '       <span class="text-' . ($ft) . '">' . ($txt) . '</span>';
    $trs .= '   </td>';
    $trs .= '   <td class="col-lg-2">';
    $trs .= '       <span>' . ($verificationDocuments['W4']['assigned_on'] ? formatDateToDB($verificationDocuments['W4']['assigned_on'], DB_DATE_WITH_TIME, DATE_WITH_TIME) : '-') . '</span>';
    $trs .= '   </td>';
    $trs .= '   <td class="col-lg-2">';
    $trs .= '       <span>' . ($verificationDocuments['W4']['completed_on'] ? formatDateToDB($verificationDocuments['W4']['completed_on'], DB_DATE_WITH_TIME, DATE_WITH_TIME) : '-') . '</span>';
    $trs .= '   </td>';
    $trs .= '   <td>  <a title="Print the original document" class="btn  btn-info btn-orange csRadius5" target="_blank" href="'.$print_original_url.'"><i class="fa fa-print" aria-hidden="true"></i></a>
    <a title="Download the original document" class="btn  btn-black csRadius5" target="_blank" href="'.$download_original_url.'"><i class="fa fa-download" aria-hidden="true"></i></a>  <a title="View the assigned document" class="btn  btn-success csRadius5" target="_blank" href="'.base_url('hr_documents_management/preview_verification_document') . '/company/' . $document['sid'].'">
    <i class="fa fa-eye" aria-hidden="true"></i>
</a>
  </td>';
    $trs .= '   <td></td>';
    $trs .= '   <td>';
    $trs .= '       <button class="btn btn-block csRadius5 btn-' . ($cl) . ' jsInitiateVerificationDocuments">';
    $trs .=         $txt2;
    $trs .= '       </button>';
    $trs .= '   </td>';
    $trs .= '</tr>';
endif;
?>

<?php if ($trs) : ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default hr-documents-tab-content">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse_no_action_vd">
                            <span class="glyphicon glyphicon-plus"></span> Verification Documents
                            <div class="pull-right total-records"><b><?php echo '&nbsp;Total: ' . $totalRecords; ?></b></div>
                        </a>
                    </h4>
                </div>

                <div id="collapse_no_action_vd" class="panel-body panel-collapse collapse in">
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
                                <?php echo $trs; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  -->
    <script>
        $(function VDDL() {
            //
            $('.jsInitiateVerificationDocuments').click(function(event) {
                //
                event.preventDefault();
                //
                $('#document_loader').show();
                //
                var obj = {
                    companyId: <?=$company_sid;?>,
                    userId: <?=$employer_sid;?>,
                    userType: 'employee',
                    documentType: $(this).closest('.jsDoc').data('type')
                };
                //
                var url = "<?=base_url();?>/form_"+(obj.documentType.toLowerCase());
                //
                if($(this).closest('.jsDoc').data('status') == 1){
                    return window.location.href = url;
                }
                //
                $.post(
                    "<?=base_url("assign_vd");?>", 
                    obj
                )
                .done(function(resp){
                    //
                    $('#document_loader').hide();
                    window.location.href = url;
                })
                .fail(function(){
                    $('#document_loader').hide();
                    console.log('Some error happened')
                });

                console.log(obj)
            });
        });
    </script>
<?php endif; ?>