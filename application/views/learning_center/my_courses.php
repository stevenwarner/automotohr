<style>
        .completed {
                background: #ffc107 !important;
        }

        .pending {
                background: #444 !important;
        }

        .badge {
                padding: 8px 10px !important;
                margin-left: 5px;
        }

        .note {
                font-size: 14px !important;
        }

        .badge-success {
                margin-bottom: 5px;
                background: #fd7a2a !important;
        }

        .badge-warning {
                margin-bottom: 5px;
                background: #444 !important;
        }

        .video_time_log {
                display: block !important;
        }

        .video_time_log:after {
                content: "" !important;
                margin: 0 3px 0 6px;
        }


        .heading-sec {
                padding: 10px 0 20px;
                margin-top: 30px;
        }

        .heading-sec h1 {
                font-style: normal;
                font-weight: 500;
                font-size: 40px;
                line-height: 60px;
                text-transform: capitalize;
                color: #081131;
                
        }

        .heading-sec h1 span {
                font-style: normal;
                font-weight: 700;
                font-size: 50px;
                line-height: 75px;
                text-transform: capitalize;
                color: #FD752A;
                font-family: 'Poppins';
        }


        .progress-bar {
                display: flex;
                flex-direction: column;
                justify-content: center;
                overflow: hidden;
                color: var(--bs-progress-bar-color);
                text-align: center;
                white-space: nowrap;
                background-color: var(--bs-progress-bar-bg);
                transition: var(--bs-progress-bar-transition);
        }

        .assignments .progress-set .progress.mt-3 {
                margin-top: .6rem !important;
        }

        .progress.mt-3 {
                background: #dcecfa;
        }

        h5.card-title.progress-set {
                padding-right: 70px;
                position: relative;

                margin-top: 50px;
                margin-bottom: 10px;
        }


        .assignments .progress-set {
                margin-top: 25px;
        }


        .card-title {
                padding: 20px 0 15px 0;
                padding-right: 0px;
                font-size: 16px;
                font-weight: 500;
                color: #012970;
                font-family: "Poppins", sans-serif;
        }


        .card-title.progress-set span {
                color: #081131;
                font-size: 14px;
                font-weight: 400;
                float: right;
                position: absolute;
                right: 4px;
                top: 21px;
                font-size: 24px;
                font-weight: 500;
        }

        .assignments .progress-set .progress.mt-3 {
                margin-top: .6rem !important;
        }


        .divround {
                border: 1px solid rgba(51, 51, 51, 0.07);
                border-radius: 4px;
                color: #fff;
                min-height: 95px;
                width: 100%;
        }

        .divround strong {
                font-style: normal;
                font-weight: 600;
                font-size: 18px;
                line-height: 22px;
                float: left;
                width: 100%;
                margin-bottom: 7px;
        }

        .divround strong span {
                color: #FD752A;
                font-weight: bold;
        }

        .completeddiv {
                background: #5cb85c;
        }

        .pendingdiv {
                background: #3554dc;
        }

        .assigneddiv {
                background: #fd7a2a;
        }


        .table-sec {
                margin-left: 10px;
                margin-right: 10px;
                background: rgba(44, 70, 93, 0.05);
                border-radius: 10px;
                padding: 28px 27px 13px;
                margin-bottom: 10px;
                overflow-x: auto;
        }


        table#example td {
                font-style: normal;
                font-size: 12px;
                line-height: 21px;
                color: #F0722D;
                font-weight: bold;
        }

        .table>tbody {
                vertical-align: inherit;
        }

        table#example td span {
                color: #2C465D;
                float: left;
                width: 100%;
        }


        table#example {
                margin-top: 30px;
        }

        .table-header {
                padding: 37px 40px 42px;
                margin-bottom: 25px;
                border-bottom: 1px solid rgba(51, 51, 51, 0.08);
        }

        table.table.table-nostriped {
                border-right: 1px solid #E4E4E4;
        }

        .table> :not(caption)>*>* {
                padding: 0 .5rem;
        }

        .table> :not(caption)>*>* {
                border-bottom: none !important;
        }

        .table> :not(caption)>*>* {
                border-top: none !important;
        }


        .table-sec h1 {
                font-style: normal;
                font-weight: 600;
                font-size: 18px;
                line-height: 27px;
                color: #2C465D;
                padding-left: 6px;
        }

        .launchcoursebtn {
                padding: 30px 0px 0px 30px !important;
        }

        
</style>

<?php
if (!$load_view) {
?>

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
                                                <h1 class="section-ttile">Courses <div style="float: right;"><a class="btn btn-info csRadius5" href="#">Filter</a></div>
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

<?php } else {
        $this->load->view('learning_center/my_courses_blue');
}
