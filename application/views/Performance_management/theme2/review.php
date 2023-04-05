<?php 
    if($load_view){
        ?>
         <!-- Main Page Box -->
         <div class="csPageWrap" style="margin: 20px auto;">
            <!--  -->
            <?php //$this->load->view("{$pp}left_sidebar"); ?>
            <?php //$this->load->view("{$pp}review_sidebar"); ?>
            <!-- Right Content Area -->
            <?php $this->load->view("{$pp}review_list_bl"); ?>
        </div>
        <?php
    } else{
        // Load green view
        ?>
        <!-- Main Page Box -->
        <div class="csPageWrap" style="margin: 20px auto;">
            <!--  -->
            <?php //$this->load->view("{$pp}review_sidebar"); ?>
            <!-- Right Content Area -->
            <?php $this->load->view("{$pp}review_list"); ?>
        </div>
        <?php
    }
?>
