<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php if($this->session->flashdata('message')){ ?>
    <div class="flash_error_message">
        <div class="alert alert-info alert-dismissible" role="alert">
          <?php echo $this->session->flashdata('message');?>
        </div>
    </div>
<?php } ?>
<?php if($this->session->flashdata('comply_message')){ ?>
    <div class="flash_error_message">
        <div class="alert alert-danger alert-dismissible" role="alert">
          <?php echo $this->session->flashdata('comply_message');?>
        </div>
    </div>
<?php } ?>