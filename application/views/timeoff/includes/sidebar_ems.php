<!-- Sidebar -->
<div class="col-sm-3">
    <div class="csSidebar csRadius5">
        <!-- Sidebar head -->
        <div class="csSidebarHead csRadius5 csRadiusBL0 csRadiusBR0">
            <figure>
                <img src="https://automotohrattachments.s3.amazonaws.com/test_file_01.png" class="csRadius50" />
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
                    <a
                        href="<?=base_url('employee_profile/'.($approver['userId']).'');?>"
                        target="_blank"
                        data-content="
                            <p><?=ucwords($approver['first_name'].' '.$approver['last_name']);?><br />
                            <?=remakeEmployeeName($approver, false);?></p>
                        " 
                        class="jsPopover"
                        <?php echo $this->agent->is_mobile() ? 'style="display: inline-block; height: auto;"' : '';?>
                    >
                        <img 
                        src="<?=AWS_S3_BUCKET_URL.(!empty($approver['picture']) ? $approver['picture'] : 'test_file_01.png');?>" 
                        class="csRadius50" 
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
            <?php endif; ?>
        </div>
        <!-- Sidebar who is off today section   -->
        <div class="csSidebarApproverSection jsOffOutSideMenu"></div>
    </div>
</div>