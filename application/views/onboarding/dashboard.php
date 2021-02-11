<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                <div class="widget-box">
                    <h3 class="text-blue">Your Activities</h3>
                    <ul class="activities-links">
                        <li class="active"><a href="javascript:;">Welcome</a></li>
                        <li><a href="javascript:;">Day 1</a></li>
                        <li><a href="javascript:;">Week 1</a></li>
                        <li><a href="javascript:;">Month 1</a></li>
                        <li><a href="javascript:;">Quarter 1</a></li>
                        <li><a href="javascript:;">Year 1</a></li>
                    </ul>
                </div>
                <div class="widget-box">
                    <h3 class="text-blue">Helpful Links</h3>
                    <ul class="quick-links border-gray">
                        <li class="active"><a href="javascript:;">Ask a Question</a></li>
                        <li><a href="javascript:;">Find Company Policies</a></li>
                        <li><a href="javascript:;">Read Latest News</a></li>
                        <li><a href="javascript:;">Explore People</a></li>
                        <li><a href="javascript:;">Find a Team</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                <div class="welcone-video-box full-width">
                    <h1 class="text-blue">Welcome to your Onboarding Center</h1>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/ePbKGoIGAXY"></iframe>
                    </div>
                    <div class="welcome-text">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea com modo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                <div class="colleague-pics full-width bg-white">
                    <h3 class="bg-blue">Your Colleague</h3>
                    <ul class="colleague-list">
                        <li><img class="img-responsive" src="<?= base_url() ?>assets/employee_panel/images/pic1.png"></li>
                        <li><img class="img-responsive" src="<?= base_url() ?>assets/employee_panel/images/pic2.png"></li>
                        <li><img class="img-responsive" src="<?= base_url() ?>assets/employee_panel/images/pic3.png"></li>
                        <li><img class="img-responsive" src="<?= base_url() ?>assets/employee_panel/images/pic1.png"></li>
                        <li><img class="img-responsive" src="<?= base_url() ?>assets/employee_panel/images/pic2.png"></li>
                        <li><img class="img-responsive" src="<?= base_url() ?>assets/employee_panel/images/pic3.png"></li>
                        <li><img class="img-responsive" src="<?= base_url() ?>assets/employee_panel/images/pic1.png"></li>
                        <li><img class="img-responsive" src="<?= base_url() ?>assets/employee_panel/images/pic2.png"></li>
                        <li><img class="img-responsive" src="<?= base_url() ?>assets/employee_panel/images/pic3.png"></li>
                        <div class="more-colleague text-center">
                            <a href="javascript:;"><i class="fa fa-angle-down"></i></a>
                        </div>
                    </ul>
                </div>
                <div class="widget-box">
                    <a href="javascript:;">
                        <div class="link-box bg-pink full-width">
                            <h2>Learning Center</h2>
                            <div class="status-panel">
                                <h3>Status</h3>
                                <span>pending</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                <a href="javascript:;">
                    <div class="link-box bg-purple full-width">
                        <h2>E-Signature</h2>
                        <div class="status-panel">
                            <h3>Status</h3>
                            <span>completed</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                <a href="javascript:;">
                    <div class="link-box bg-redish full-width">
                        <h2>HR Documents</h2>
                        <div class="status-panel">
                            <h3>Status</h3>
                            <span>completed</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                <a href="<?php echo base_url('onboarding/bank_details/' . $unique_sid); ?>">
                    <div class="link-box bg-blue full-width">
                        <h2>Direct Deposit Information</h2>
                        <div class="status-panel">
                            <h3>Status</h3>
                            <span>Skipped</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                <a href="<?php echo base_url('onboarding/calendar/' . $unique_sid); ?>">
                    <div class="link-box bg-orange full-width">
                        <h2>Calendar</h2>
                        <div class="current-date">
                            <span>16<sub>wed</sub></span>
                        </div>
                        <div class="status-panel">
                            <h3>Status</h3>
                            <span>Skipped</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>