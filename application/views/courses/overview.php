<!-- Main page -->
<div class="csPage">
    
    <!--  -->
    <?php $this->load->view('courses/partials/navbar'); ?>
    <!--  -->
    <div class="_csMt20">
        <div class="container-fluid">
            <!--  -->
            <div class="row">
                <!--  -->
                <div class="col-md-3 col-sm-12">
                    <!-- Sidebar -->
                    <?php $this->load->view('2022/sidebar'); ?>
                </div>
                <!--  -->
                <div class="col-md-6 col-sm-12">
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="csPageBox _csB2 _csR5 _csPt20 _csPb20">
                                <h2 class="_csF28"><strong>Are your employees engaged at work?</strong></h2>
                                <p class="_csF14">Info here</p>
                                <a href="<?php echo base_url("lms_courses/create"); ?>" class="btn _csB4 _csF2 _csR5 _csMt20"><i class="fa fa-plus _csF16" aria-hidden="true"></i>&nbsp;Create Cource</a>
                            </div>
                        </div>
                    </div>
                    <!-- Running courses -->
                    <div class="panel panel-default _csMt30">
                        <div class="panel-heading">
                            <h3 class="_csM0 _csF16">Courses List ( <span id="jsOverviewCount">0</span> )</h3>
                        </div>
                        <div class="panel-body" id="jsOverviewListing">
                            <!-- Row 1 -->
                            
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="col-md-3 col-sm-12">
                    <div class="csPageSideBar _csR5 _csPb10">
                        <h3 class="_csM0 _csB4 _csP10 _csF2 _csF16">Statistics</h3>
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('courses/partials/loader'); ?>