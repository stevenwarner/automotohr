<?php 
    if($load_view){
        ?>
        <!-- Main Page Box -->
        <div class="csPageWrap" style="margin: 20px auto;">
            <!-- Left Sidebar -->
            <?php //$this->load->view("{$pp}left_sidebar"); ?>
            <!-- Right Content Area -->
            <?php $this->load->view("{$pp}all_completed_reviews_blue"); ?>
        </div>
        <?php
    } else{
        // Load green view
    }
?>
