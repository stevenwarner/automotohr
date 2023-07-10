<div class="main jsmaincontent">
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
                    <h1 class="section-ttile">Courses <div style="float: right;"><a class="btn btn-black csRadius5" href="#">Filter</a></div>
                    </h1>
                </div>

                <div class="section-inner">
                    <div class="heading-sec">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <h1>Employee Health Score: <br><span class="healthscore" alt="" title="">0</span>
                                    <div class="progress-meter-detail-button" alt="Show Score Details" title="Show Score Details" onclick="$('#heathpopup').modal('show');setTimeout(calculatechsdata, 500);" style="display: inline; position: absolute; "><img src="/assets/img/tooltip.svg" alt=""></div>
                                </h1>
                            </div>

                            <div class="col-md-6 col-xs-12">
                                <h5 class="card-title progress-set" alt="" title="">
                                    <div class="progress mt-3">
                                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" id="barchart_progressmeter" div=""><span id="totalscore">0%</span>
                                        </div>
                                    </div>
                                </h5>
                            </div>
                        </div>


                        <div class="row">
                            <div class="nav-item col-md-3 col-xs-12 ">
                                <button class="divround completeddiv" id="training-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-training" type="button" role="tab" aria-controls="profile" aria-selected="true">
                                    <strong>Completed: <span>5</span></strong>
                                </button>
                            </div>

                            <div class="nav-item col-md-3 col-xs- ">
                                <button class="divround pendingdiv" id="training-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-training" type="button" role="tab" aria-controls="profile" aria-selected="true">
                                    <strong>Pending: <span>3</span></strong>

                                </button>
                            </div>

                            <div class="nav-item col-md-3 col-xs-12 ">
                                <button class="divround assigneddiv" id="training-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-training" type="button" role="tab" aria-controls="profile" aria-selected="true">
                                    <strong>Assigned: <span style="color: #fff;">8</span></strong>
                                </button>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 20px;"></div>

                        <div class="row">
                            <div class="table-sec" style="overflow-x: auto;">
                                <h1>Discrimination for Employees</h1>
                                <table id="example" class="table" style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>ASSIGNED DATE <span>5/2/2023</span></td>
                                            <td>DUE DATE <span>6/1/2023</span></td>
                                            <td>STATUS <span>Past Due</span></td>
                                            <td>ASSIGNED TO <span>puma pu</span></td>
                                        </tr>
                                        <tr>
                                            <td>TIME REMAINING/TOTAL <span>15 min / 15 min</span></td>
                                            <td>STARTED DATE <span></span></td>
                                            <td>LANGUAGE
                                                <select class="form-control " id="">
                                                    <option value="Englis">English</option>
                                                    <option value="Spanish">Spanish</option>
                                                </select>

                                            </td>
                                            <td class="launchcoursebtn" colspan="2">
                                                <button class="btn btn-info csRadius5" onclick="">Launch Content</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="table-sec">
                                <h1>Discrimination for Employees</h1>
                                <table id="example" class="table" style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>ASSIGNED DATE <span>5/2/2023</span></td>
                                            <td>DUE DATE <span>6/1/2023</span></td>
                                            <td>STATUS <span>Past Due</span></td>
                                            <td>ASSIGNED TO <span>puma pu</span></td>
                                        </tr>
                                        <tr>
                                            <td>TIME REMAINING/TOTAL <span>15 min / 15 min</span></td>
                                            <td>STARTED DATE <span></span></td>
                                            <td>LANGUAGE
                                                <select class="form-control " id="">
                                                    <option value="Englis">English</option>
                                                    <option value="Spanish">Spanish</option>
                                                </select>

                                            </td>
                                            <td class="launchcoursebtn" colspan="2">
                                                <button class="btn btn-info csRadius5" onclick="">Launch Content</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="table-sec">
                                <h1>Discrimination for Employees</h1>
                                <table id="example" class="table" style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>ASSIGNED DATE <span>5/2/2023</span></td>
                                            <td>DUE DATE <span>6/1/2023</span></td>
                                            <td>STATUS <span>Past Due</span></td>
                                            <td>ASSIGNED TO <span>puma pu</span></td>
                                        </tr>
                                        <tr>
                                            <td>TIME REMAINING/TOTAL <span>15 min / 15 min</span></td>
                                            <td>STARTED DATE <span></span></td>
                                            <td>LANGUAGE
                                                <select class="form-control " id="">
                                                    <option value="Englis">English</option>
                                                    <option value="Spanish">Spanish</option>
                                                </select>

                                            </td>
                                            <td class="launchcoursebtn" colspan="2">
                                                <button class="btn btn-info csRadius5" onclick="">Launch Content</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>