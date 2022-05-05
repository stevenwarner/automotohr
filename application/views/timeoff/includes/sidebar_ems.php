<!-- Sidebar -->
<div class="col-sm-3">
    <div class="csSidebar csRadius5">
        <!-- Sidebar head -->
        <div class="csSidebarHead csRadius5 csRadiusBL0 csRadiusBR0">
            <figure>
                
                <?php 
                    $emp_info = get_employee_profile_info($employee['sid']); 
                    $profile_pic = !empty($emp_info['profile_picture']) ? $emp_info['profile_picture'] : 'img-applicant.jpg';
                ?>
                <img src="https://automotohrattachments.s3.amazonaws.com/<?php echo $profile_pic; ?>" class="csRadius50" />
                <div class="csTextBox">
                    <p><?=$employee['first_name'] . ' ' . $employee['last_name'];?></p>
                    <p class="csTextSmall"><?=remakeEmployeeName($employee, false);?></p>
                    <?php if(!empty($employee['PhoneNumber'])): ?>
                    <p class="csTextSmall"><?=$employee['PhoneNumber'];?></p>
                    <?php endif; ?>
                    <?php if(!empty($employee['email'])): ?>
                    <p class="csTextSmall"><?=$employee['email'];?></p>
                    <?php endif; ?>
                    <p><?php echo $session['company_detail']['CompanyName']; ?></p>
                </div>
                <div class="csFixBox">
                    <a href="<?=base_url('my_profile');?>" class="btn btn-orange csRadius50" title="Edit my profile"
                        placement="top"><i class="fa fa-pencil"></i> Edit</a>
                </div>
            </figure>
            <div class="clearfix"></div>
        </div>
        <div class="csSidebarApproverSection">
            <h4>Approvers</h4>
            <?php if(!empty($approvers)): ?>
            <ul>
                <?php foreach($approvers as $approver): ?>
                <li  <?php echo $this->agent->is_mobile() ? 'style="display: block;"' : '';?>>
                    <?php 
                        $approver_info = get_employee_profile_info($approver['userId']); 
                        $profile_pic = !empty($approver_info['profile_picture']) ? $approver_info['profile_picture'] : 'img-applicant.jpg';
                    ?>
                    <a
                        href="Javascript:void(0);"
                        target="_blank"
                        data-content="
                            <p><?=ucwords($approver['first_name'].' '.$approver['last_name']);?><br />
                            <?=remakeEmployeeName($approver, false);?></p>
                        " 
                        class="jsPopover"
                        <?php echo $this->agent->is_mobile() ? 'style="display: inline-block; height: auto;"' : '';?>
                    >
                        <img 
                        src="<?=getImageURL($profile_pic);?>" 
                        class="csRadius50" 
                        alt=""
                        />
                    </a>
                        <?php if($this->agent->is_mobile()) { ?>
                            <p  <?php echo $this->agent->is_mobile() ? 'style="display: inline-block;"' : '';?>><?=ucwords($approver['first_name'].' '.$approver['last_name']);?><br />
                            <?=remakeEmployeeName($approver, false);?></p>
                        <?php }?>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
                <p class="alert alert-info text-center">No approvers found.</p>
            <?php endif; ?>
        </div>
        <!-- Sidebar who is off today section   -->
        <div class="csSidebarApproverSection jsOffOutSideMenu"></div>
    </div>
</div>