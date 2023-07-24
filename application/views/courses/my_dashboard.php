<?php if ($load_view) { ?>
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                            <a href="<?php echo $employee['access_level'] == 'Employee' ? base_url('dashboard') : base_url('employee_management_system'); ?>" class="btn btn-info csRadius5"><i class="fa fa-arrow-left"></i> Dashboard</a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3"></div>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="page-header">
                        <h1 class="section-ttile">Courses <div style="float: right;"></div>
                        </h1>
                    </div>

                    <div class="section-inner">
                        <div class="heading-sec">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <h1>Employee Health Score: <span class="healthscore" alt="" title="">0</span>
                                        <div class="progress-meter-detail-button" alt="Show Score Details" title="Show Score Details" onclick="$('#heathpopup').modal('show');setTimeout(calculatechsdata, 500);" style="display: inline; position: absolute; "><img src="/assets/img/tooltip.svg" alt=""></div>
                                    </h1>
                                </div>

                                <div class="col-md-6 col-xs-12 ma30">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                            60%
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <!-- Assign courses -->
                                <div class="col-xs-12 col-md-3">
                                    <div class="thumbnail black-block ">
                                        <div class="caption">
                                            <h3>55</h3>
                                            <h4><strong>Assigned</strong></h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pending courses -->
                                <div class="col-xs-12 col-md-3">
                                    <div class="thumbnail csPendingBlock">
                                        <div class="caption">
                                            <h3>66</h3>
                                            <h4><strong>Pending</strong></h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- Completed courses -->
                                <div class="col-xs-12 col-md-3">
                                    <div class="thumbnail success-block">
                                        <div class="caption">
                                            <h3>5</h3>
                                            <h4><strong>Completed</strong></h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- Failed courses -->
                                <div class="col-xs-12 col-md-3">
                                    <div class="thumbnail error-block">
                                        <div class="caption">
                                            <h3>5</h3>
                                            <h4><strong>Failed</strong></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 20px;"></div>

                            <div class="row mb10">
                                <div class="col-xs-12 col-md-12 text-right">
                                    <a class="btn btn-black csRadius5" href="#"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp;Filter</a>
                                </div>    
                            </div>

                            <article class="article-sec">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 text-right">
                                    <span class="csF16 text-danger"><strong><i class="fa fa-ban" aria-hidden="true"></i> FAILED</strong></span>
                                    </div>
                                </div>
                                <h1>
                                    Discrimination for Employees
                                </h1>
                                <br>
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>ASSIGNED DATE</strong></p>
                                        <p>5/2/2023</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>DUE DATE</strong></p>
                                        <p>5/2/2023</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>STATUS</strong></p>
                                        <p>Past Due</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>ASSIGNED TO</strong></p>
                                        <p>puma pu</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>TIME REMAINING/TOTAL</strong></p>
                                        <p>15 min / 15 min</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>STARTED DATE </strong></p>
                                        <p>5/2/2023</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>LANGUAGE</strong></p>
                                        <select name="" id="" class="form-control">
                                            <option value="eng">English</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-xs-12 text-right">
                                        <p>&nbsp;</p>
                                        <button class="btn btn-info csRadius5 csF16" onclick="">Launch Content</button>
                                    </div>
                                </div>
                            </article>

                            <article class="article-sec">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 text-right">
                                        <span class="csF16 text-success"><strong><i class="fa fa-trophy" aria-hidden="true"></i> PASSED</strong></span>
                                    </div>
                                </div>
                                <br>
                                <h1>Discrimination for Employees</h1>
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>ASSIGNED DATE</strong></p>
                                        <p>5/2/2023</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>DUE DATE</strong></p>
                                        <p>5/2/2023</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>STATUS</strong></p>
                                        <p>Past Due</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>ASSIGNED TO</strong></p>
                                        <p>puma pu</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>TIME REMAINING/TOTAL</strong></p>
                                        <p>15 min / 15 min</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>STARTED DATE </strong></p>
                                        <p>5/2/2023</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>LANGUAGE</strong></p>
                                        <p>English</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12 text-right">
                                        <p></p>
                                        <button class="btn btn-info csRadius5 csF16" onclick="">Launch Content</button>
                                    </div>
                                </div>
                            </article>

                            <article class="article-sec">
                                <h1>Discrimination for Employees</h1>

                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>ASSIGNED DATE</strong></p>
                                        <p>5/2/2023</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>DUE DATE</strong></p>
                                        <p>5/2/2023</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>STATUS</strong></p>
                                        <p>Past Due</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>ASSIGNED TO</strong></p>
                                        <p>puma pu</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>TIME REMAINING/TOTAL</strong></p>
                                        <p>15 min / 15 min</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>STARTED DATE </strong></p>
                                        <p>5/2/2023</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <p class="csColumSection"><strong>LANGUAGE</strong></p>
                                        <p>English</p>
                                    </div>
                                    <div class="col-md-3 col-xs-12 text-right">
                                        <p></p>
                                        <button class="btn btn-info csRadius5 csF16" onclick="">Launch Content</button>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    $this->load->view('learning_center/my_courses_blue');
}
