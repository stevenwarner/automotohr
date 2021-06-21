<?php 
    if($load_view){
        ?>
        <!-- Main Page Box -->
        <div class="csPageWrap" style="margin: 20px auto;">
            <!-- Left Sidebar -->
            <?php $this->load->view("{$pp}left_sidebar"); ?>
            <!-- Right Content Area -->
            <?php $this->load->view("{$pp}report/report"); ?>
        </div>
        <?php
    } else{
        // Load green view
<<<<<<< HEAD
        $this->load->view("{$pp}report/gp_report");
=======
>>>>>>> fee239a4... Added PM report for blue screen
    }
?>
