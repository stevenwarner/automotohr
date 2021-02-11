<!-- Main Content Area -->
<div class="csPageMain">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php $this->load->view('timeoff/includes/sidebar_ems'); ?>
            <!-- Main -->
            <?php $this->load->view('timeoff/lms/'.($this->agent->is_mobile() ?  'm_' : '').'main_ems'); ?>
        </div>
    </div>
</div>