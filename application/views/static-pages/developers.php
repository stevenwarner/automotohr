
<!-- Main Start -->
<div class="main-content">
    <div class="container">
        <div class="row">					
            <div class="col-md-12">
                <!-- Info Boxes With Icons Start -->
                <div class="icon-info-boxes developer-page">
                    <article>
                        <!-- AMR Separator Start -->
                        <div class="arm-separator">
                            <span><i class="fa fa-angle-down"></i></span>
                        </div>
                        <!-- AMR Separator End -->
                        <div class="info-box">
                            <h2>Marketplace API</h2>
                            <p>For Job Boards, Assessment vendors, Databases and Apps. Start building using the Marketplace API.</p>
                            <div class="developer-link"><a href="javascript:;">Go to documentation</a></div>
                        </div>
                    </article>
                    <article>
                        <!-- AMR Separator Start -->
                        <div class="arm-separator">
                            <span><i class="fa fa-angle-down"></i></span>
                        </div>
                        <!-- AMR Separator End -->
                        <div class="info-box">
                            <h2>Customer API</h2>
                            <p>Build customized career sites & connect <?php echo STORE_NAME; ?>  platform with your IT.</p>
                            <div class="developer-link"><a href="javascript:;">Go to documentation</a></div>
                        </div>
                    </article>
                    <article>
                        <!-- AMR Separator Start -->
                        <div class="arm-separator">
                            <span><i class="fa fa-angle-down"></i></span>
                        </div>
                        <!-- AMR Separator End -->
                        <div class="info-box">
                            <h2>Developer Blog</h2>
                            <p>Customize Any Career Site with RESTful Job Posting API</p>
                        </div>
                    </article>
                </div>
                <!-- Info Boxes With Icons End -->                  
            </div>
        </div>
    </div>
    <?php if (!$this->session->userdata('logged_in') && $title != 'Register') { ?> 
        <?php $this->load->view('main/demobuttons'); ?>
    <?php } ?>
</div>
<!-- Main End -->		
<!-- Footer Start -->
