<?php $this->load->view('timeoff/includes/header'); ?>

<div class="csPageMain">
    <div class="container-fluid">
        <div class="csPageWrap csRadius5 csShadow">
            <!-- Loader -->
            <div class="csIPLoader jsIPLoader" data-page="type"><i class="fa fa-circle-o-notch fa-spin"></i></div>

            <!-- View  -->
            <?php $this->load->view('timeoff/partials/types/new_view'); ?>

            <!-- Add  -->
            <?php $this->load->view('timeoff/partials/types/new_add'); ?>
            
            <!-- Edit  -->
            <?php $this->load->view('timeoff/partials/types/new_edit'); ?>
        </div>
    </div>
</div>