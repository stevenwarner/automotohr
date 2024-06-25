<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if ($this->session->flashdata('message')) { ?>
  <div class="flash_error_message">
    <div class="alert alert-info alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <?php echo $this->session->flashdata('message'); ?>
    </div>
  </div>
<?php } ?>
<?php if ($this->session->flashdata('comply_message')) { ?>
  <div class="flash_error_message">
    <div class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <?php echo $this->session->flashdata('comply_message'); ?>
    </div>
  </div>
<?php } ?>
<?php if ($this->session->flashdata('indeed_error')) { ?>
  <div class="flash_error_message">
    <div class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <?php echo $this->session->flashdata('indeed_error'); ?>
    </div>
  </div>
<?php } ?>
<?php if ($this->session->flashdata('indeed_success')) { ?>
  <div class="flash_error_message">
    <div class="alert alert-success alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <?php echo $this->session->flashdata('indeed_success'); ?>
    </div>
  </div>
<?php } ?>