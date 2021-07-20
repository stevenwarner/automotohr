<div class="col-md-3 col-xa-12">
    <div class="csSidebar csRadius5">
        <!-- Sidebar head -->
        <div class="csSidebarHead csRadius5 csRadiusBL0 csRadiusBR0 pa0">
            <figure>
                <img src="<?=getImageURL($employee['profile_picture']);?>" alt=""/>
                <div class="csTextBox">
                    <p class="csF16 csB7"><?=ucwords($employee['first_name'].' '.$employee['last_name']);?></p>
                    <p class="csTextSmall csF14"> <?=remakeEmployeeName($employee, false);?></p>
                    <p class="csTextSmall csF14"><?=empty($employee['PhoneNumber']) ? '-' : $employee['PhoneNumber'];?></p>
                    <p class="csTextSmall csF14"><?=$employee['email'];?></p>
                </div>
                <div class="csFixBox">
                    <a href="<?=base_url("my_profile");?>" class="btn btn-orange csF16" title=""
                        placement="top" data-original-title="Edit my profile"><i class="fa fa-pencil" aria-hidden="true"></i> My Profile</a>
                </div>
            </figure>
            <div class="clearfix"></div>
        </div>
        <!-- Teams -->
        <div class="csSidebarApproverSection">
            <h4 class="csF16 csB7">Team(s)</h4>
        </div>
        <div class="csSidebarApproverSection jsOffOutSideMenu">
            <ul class="csUl">
            <?php 
                if(!empty($employee_dt)):
                    foreach($employee_dt as $dt):
                        ?>
                        <li><p class="pl10 csF14"><?=$dt['team_name'].', '.$dt['department_name'];?></p></li>
                        <?php
                    endforeach;
                else:
                    ?>
                    <li><p class="pl10 csF14">No team(s)</p></li>
                    <?php
            endif;
            ?>
            </ul>
        </div>
    </div>
    <!--  -->
    <?php
        if(
            strpos($this->uri->uri_string(), 'reviews') !== false
            ){
                ?>
            <!-- Help Box -->
            <div class="panel panel-theme">
                <div class="panel-body">
                    <p class="csF14">
                        <button class="btn btn-black csF14 btn-xs">
                            <i class="fa fa-play csF14" aria-hidden="true"></i>
                        </button>
                        Manually starts the review.
                    </p>
                    <p class="csF14">
                        <button class="btn btn-black csF14 btn-xs">
                            <i class="fa fa-stop csF14" aria-hidden="true"></i>
                        </button>
                        Manually stops the review.
                    </p>
                    <p class="csF14">
                        <button class="btn btn-black csF14 btn-xs">
                            <i class="fa fa-eye csF14" aria-hidden="true"></i>
                        </button>
                        View reviewers and review details.
                    </p>
                    <p class="csF14">
                        <button class="btn btn-black csF14 btn-xs">
                            <i class="fa fa-plus-circle csF14" aria-hidden="true"></i>
                        </button>
                        Add new reviewers.
                    </p>
                    <p class="csF14">
                        <button class="btn btn-black csF14 btn-xs">
                            <i class="fa fa-archive csF14" aria-hidden="true"></i>
                        </button>
                        The review will be archived
                    </p>
                </div>
            </div>
            <?php
        }
    ?>
    <!--  -->
    <?php $this->load->view("{$pp}help_box"); ?>
</div>