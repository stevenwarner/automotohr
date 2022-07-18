<?php $this->load->view('timeoff/includes/header'); ?>

<div class="csPageMain">
    <div class="container-fluid">
        <div class="csPageWrap csRadius5 csShadow">
            <!-- Loader -->
            <div class="csIPLoader jsIPLoader" data-page="requests"><i class="fa fa-circle-o-notch fa-spin"></i></div>

            <!-- View  -->
            <?php $this->load->view('timeoff/partials/requests/'.( $this->agent->is_mobile() ? 'm_' : '').'new_view'); ?>
        </div>
    </div>
</div>

<?php $this->load->view('timeoff/add_edit_note'); ?>