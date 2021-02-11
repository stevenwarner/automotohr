<?php $this->load->view('main/static_header'); ?>
<body>
    <!-- Wrapper Start -->
    <div class="wrapper">
        <!-- Header Start -->
        <header class="header header-position">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
                        <h2 style="color: #fff; text-align: center;"><?php echo $page_title; ?></h2>
                    </div>
                </div>
            </div>
        </header>
        <!-- Header End -->
        <div class="clear"></div>
        <!-- Main Start -->	
        <div class="main" style="margin-top: 50px;">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="">
                            <div class="clear"></div>

                                <?php $this->load->view('reference_checks/reference_checks_edit_public'); ?>

                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main End -->		
        <!-- Footer Start -->
        <!--
        <footer class="footer">
            <!-- CopyRight Start
            <div class="copyright">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="copy-right-text">
                                <p>&copy; 2016 <?php echo STORE_NAME; ?>. All Rights Reserved..</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- CopyRight End
        </footer>
        -->
        <!-- Footer End -->
        <div class="clear"></div>
    </div>
    <!-- Wrapper End -->

</body>
</html>
<script>


</script>