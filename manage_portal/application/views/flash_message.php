<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php if($this->session->flashdata('message')){ ?>
	<div class="container cs-flash-wrap">
	    <div class="row">
	        <div class="col-sm-12 js-alert-m">
			    <div class="flash_error_message">
			        <div class="alert alert-warning alert-dismissible" role="alert">
			          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			          <?php echo $this->session->flashdata('message');?>
			        </div>
			    </div>
	    	</div>
    	</div>
    </div>

    <script>
    	$(function(){
    		$("html, body").animate({ scrollTop: $('.cs-flash-wrap').offset().top - 100 }, "slow");
    	})
    </script>
<?php } ?>