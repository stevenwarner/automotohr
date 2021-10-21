<style>
    body{
        background-color: #f1f1f1;
    }
</style>
<?php if($load_view): ?>
<div class="csPageWrap">
    <br>
    <!-- Sidebar -->
    <?php $this->load->view('employee_engagement_survey/sidebar'); ?>
    <!-- Main Content Area -->
    <?php $this->load->view('employee_engagement_survey/pages/overview'); ?>
</div>
<?php else: ?>
<?php endif;?>