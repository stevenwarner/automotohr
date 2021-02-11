<?php $this->load->view('timeoff/includes/header'); ?>

<div class="csPageMain">
    <div class="container-fluid">
        <div class="csPageWrap csRadius5 csShadow">
            <!-- Loader -->
            <div class="csIPLoader jsIPLoader" data-page="approver"><i class="fa fa-circle-o-notch fa-spin"></i></div>

            <!-- View  -->
            <?php $this->load->view('timeoff/partials/approvers/new_view'); ?>
            
            <!-- Add  -->
            <?php $this->load->view('timeoff/partials/approvers/new_add'); ?>
            
            <!-- Edit  -->
            <?php $this->load->view('timeoff/partials/approvers/new_edit'); ?>


        </div>
    </div>
</div>

<?php $this->load->view('timeoff/partials/popups/team_management'); ?>