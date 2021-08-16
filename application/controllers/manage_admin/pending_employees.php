
<style>
.popover-content p{
    padding: 10px;
}
.popover-content p:nth-child(odd){
    background: #eee;
}
</style>


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


        <?php
        if ( isset($employees) && count($employees) > 0) {
            ?>
            <div class="table-responsive">
                <h3>Employees with Pending Document Actions 
                    <span class="pull-right">
                        <button class="btn btn-success jsSendEmailReminder">
                            Send Email Reminder
                        </button>
                        <button class="btn btn-success js-action-btn" data-type="print">Print</button>
                        <button class="btn btn-success js-action-btn" data-type="export">Export</button>
                    </span>
                </h3>
                <div class="hr-document-list">
                    <table class="hr-doc-list-table">
                        <caption></caption>
                        <thead>
                        <tr>
                            <th scope="col">
                                <label class="control control--checkbox">
                                    <input type="checkbox" id="jsSelectAll" />
                                    <div class="control__indicator"></div>
                                </label>
                            </th>
                            <th scope="col">Employee Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Document(s)</th>
                            <th scope="col" style="text-align: right" >View Document(s)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        function dateSorter($a, $b){
                            return $a['AssignedOn'] < $b['AssignedOn'];
                        }
                        foreach ($employees as $employee) {
                            $icount = sizeof($employee['Documents']);
                            $itext = '';
                            if(sizeof($employee['Documents'])){
                                //
                                usort($employee['Documents'], 'dateSorter');
                                foreach ($employee['Documents'] as $ke => $v) {
                                    //
                                    $assignedByText = '';
                                    //
                                    if(isset($v['AssignedBy'])){
                                        $assignedBy = getUserNameBySID($v['AssignedBy']);
                                        $assignedByText = '<br /> <em>Assigned By: '.( $assignedBy ).'</em>';

                                    }
                                    $itext .= '<p>';
                                    $itext .= ' <strong>'.( $v['Title'] ).'</strong> ('.($v['Type']).')';
                                    $itext .= ' <br /> <em>Assigned On: '.($v['AssignedOn']).'';
                                    if(!empty($v['Days'])){
                                        $itext .= ' ('.$v['Days'].' day'.($v['Days'] == 1 ? '' : 's').' ago)';
                                    }
                                    $itext .= ' </em>';
                                    $itext .= $assignedByText;
                                    $itext .= '</p>';
                                }
                            }
                            ?>
                            <tr data-id="<?=$employee['sid'];?>">
                                <td>
                                    <label class="control control--checkbox">
                                        <input type="checkbox" class="jsSelectSingle" />
                                        <div class="control__indicator"></div>
                                    </label>
                                </td>
                                <td><?=remakeEmployeeName($employee);?></td>
                                <td><?php echo $employee['email']; ?></td>
                                <td
                                    style="cursor: pointer"
                                        data-container="body" 
                                        data-toggle="cpopover"
                                        data-placement="left" 
                                        data-title="<?=$icount;?> Document(s)"
                                        data-content="<?=$itext;?>">
                                    <strong
                                        
                                    ><?=$icount;?></strong> Document(s)
                                </td>
                                <td class="text-right">
                                    <a data-toggle="tooltip" title="View" data-placement="left" href="<?php echo base_url('hr_documents_management/employee_document'); ?>/<?php echo $employee['sid']; ?>" class=" btn-sm btn-default">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="table-responsive">
                <h3>Employees with Pending Document Actions</h3>
                <div class="hr-document-list">
                    <table class="hr-doc-list-table">
                        <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Email</th>
                            <th style="text-align: right" >View Document(s)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-center">
                            <td colspan="3">No employee with pending document(s)</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        }?>


    </div>
