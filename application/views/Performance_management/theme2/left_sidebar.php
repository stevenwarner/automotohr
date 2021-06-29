<div class="col-md-3 col-sm-12">
    <div class="csSidebar csRadius5">
        <!-- Sidebar head -->
        <div class="csSidebarHead csRadius5 csRadiusBL0 csRadiusBR0 pa0">
<<<<<<< HEAD
            <figure>
                <img src="<?=getImageURL($employee['profile_picture']);?>" class="" alt=""/>
        <div class="csSidebarHead csRadius5 csRadiusBL0 csRadiusBR0">
=======
>>>>>>> d5bced39... Added creatae review step 1 on blue screen
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
<<<<<<< HEAD
                    <a href="<?=base_url("my_profile");?>" class="btn btn-orange csRadius50" title=""
                        placement="top" data-original-title="Edit my profile"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
=======
>>>>>>> d5bced39... Added creatae review step 1 on blue screen
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
    <?php $this->load->view("{$pp}help_box"); ?>
</div>