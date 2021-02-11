<?php $this->load->view('main/static_header'); ?>
<body>
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
<!--                    <div class="top-logo text-center">
                        <img src="<?php echo base_url('assets/images/form-logo.jpg') ?>">
                    </div>-->
                    <div class="end-user-agreement-wrp">
                        <div class="thankyou-page-wrp">
                            <div class="thanks-page-icon">
                                <div class="icon-circle"><i class="fa fa-check"></i></div>
                            </div>
                            <div class="thank-you-text">
                                <h1>THANK YOU</h1>
                                <span>
                                    <?php if($this->session->flashdata('message')){ ?>
                                        <?php echo $this->session->flashdata('message');?>
                                    <?php } ?>
                                </span>
<!--                                <div class="home-link"><a href="<?php echo base_url();?>">
                                        <i class="fa fa-long-arrow-left"></i> back to home</a>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>