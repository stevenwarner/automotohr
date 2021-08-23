<?php 
    if($load_view){
        ?>
         <!-- Main Page Box -->
         <div class="csPageWrap" style="margin: 20px auto;">
            <!--  -->
            <?php $this->load->view("{$pp}left_sidebar"); ?>
            <!-- Right Content Area -->
            <?php $this->load->view("{$pp}add_employee_blue"); ?>
        </div>
        <?php
    }
?>