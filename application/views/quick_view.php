<br>
<!--  -->
<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped table-bordered">
            <caption>Employee Details.</caption>
            <tbody>
                <tr>
                    <td class="col-sm-3"><strong>Name</strong></td>
                    <td class="col-sm-9"><?=$Name;?> <?=$Role;?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Email</strong></td>
                    <td class="col-sm-9"><?=$Email;?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Phone</strong></td>
                    <td class="col-sm-9"><?=!empty($Phone) ? $Phone : 'N/A';?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Employment Type</strong></td>
                    <td class="col-sm-9"><?=!empty($EmploymentType) ? $EmploymentType : 'N/A';?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Date Of Birth</strong></td>
                    <td class="col-sm-9"><?=!empty($DOB) ? formatDateToDB($DOB, 'Y-m-d', DATE) : 'N/A';?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Joined On</strong></td>
                    <td class="col-sm-9"><?=formatDateToDB($JoinedDate, 'Y-m-d', DATE);?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!--  -->
<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped table-bordered">
            <caption>The selected employee is managing the following department(s) and team(s).</caption>
            <tbody>
                <tr>
                    <td class="col-sm-3"><strong>Department(s)</strong></td>
                    <td class="col-sm-9"><?=!empty($Managing['Departments']) ? implode('<br>',$Managing['Departments']) : 'N/A';?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Team(s)</strong></td>
                    <td class="col-sm-9"><?=!empty($Managing['Teams']) ? implode('<br>',$Managing['Teams']) : 'N/A';?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!--  -->
<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped table-bordered">
            <caption>The selected employee is part of the following department(s) and team(s).</caption>
            <tbody>
                <tr>
                    <td class="col-sm-3"><strong>Department(s)</strong></td>
                    <td class="col-sm-9"><?=!empty($Departments) ? implode('<br>',$Departments) : 'N/A';?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Team(s)</strong></td>
                    <td class="col-sm-9"><?=!empty($Teams) ? implode('<br>',$Teams) : 'N/A';?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!--  -->
<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped table-bordered">
            <caption>The following employees are in charge of the selected employee.</caption>
            <tbody>
                <tr>
                    <td class="col-sm-3"><strong>Supervisor(s)</strong></td>
                    <td class="col-sm-9"><?=!empty($Supervisors) ? implode('<br>',$Supervisors) : 'N/A';?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Team Lead(s)</strong></td>
                    <td class="col-sm-9"><?=!empty($TeamLeads) ? implode('<br>',$TeamLeads) : 'N/A';?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Approver(s)</strong></td>
                    <td class="col-sm-9">
                        <?php
                            if(!empty($Approvers)){
                                foreach($Approvers as $Approver){
                                    ?>
                                    <p><?=$Approver['user'];?></p>
                                    <?php 
                                        if(!empty($Approver['departments'])){
                                            ?>
                                            <p><strong>Departments:</strong> <br>
                                            <?php
                                                foreach($Approver['departments'] as $dt){
                                                    ?>
                                                    <span><?=$dt['Names'];?> (<?=$dt['CanApprove'];?>)</span>
                                                    <?php
                                                }
                                                ?>
                                                </p>
                                                <?php
                                            }
                                        ?>
                                        <?php 
                                            if(!empty($Approver['teams'])){
                                                ?>
                                                <p><strong>Teams:</strong> <br>
                                                <?php
                                                foreach($Approver['teams'] as $dt){
                                                    ?>
                                                    <span><?=$dt['Names'];?> (<?=$dt['CanApprove'];?>)</span>
                                                    <?php
                                                }
                                                ?>
                                                </p>
                                                <?php
                                            }
                                        ?>
                                    <hr>
                                    <?php
                                }
                            } else{
                                echo 'N/A';
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Reporting Manager(s)</strong></td>
                    <td class="col-sm-9"><?=!empty($ReporitngManagers) ? implode('<br>',$ReporitngManagers) : 'N/A';?></td>
                </tr>
                <tr>
                    <td class="col-sm-3"><strong>Team Member(s)</strong></td>
                    <td class="col-sm-9"><?=!empty($TeamMembers) ? implode('<br>',$TeamMembers) : 'N/A';?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
