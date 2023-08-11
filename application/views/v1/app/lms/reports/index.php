<?php if (isset($PageScripts)) {
    echo GetScripts($PageScripts);
} ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('manage_employer/settings_left_menu_reporting'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>Courses Report</span>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <a id="btn_apply_filters" class="btn btn-success" href="#">Filters</a>
                            <a class="btn btn-success" href="#">Export CSV</a>
                        </div>
                    </div>

                    <div class="hr-box">
                        <div class="hr-box-header bg-header-green">
                            <span class="pull-left">
                                <h1 class="hr-registered">Details</h1>
                            </span>
                        </div>

                        <div class="hr-innerpadding">
                            <div class="row">
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    <canvas id="coursesChartdonut" width="400" height="300"></canvas>
                                </div>

                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    <canvas id="employeeChartdonut" width="400" height="300"></canvas>
                                </div>

                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    <canvas id="departmentChart" width="400" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="hr-box">
                                <div class="hr-box-header bg-header-green">
                                    <span class="pull-left">
                                        <h1 class="hr-registered">Employees With Pending Courses</h1>
                                    </span>
                                    <button class="btn btn-success pull-right">View All</button>

                                </div>
                                <div class="hr-innerpadding">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Employee Name</th>
                                                    <th>Status</th>
                                                    <th># of Courses</th>
                                                    <th class="col-xs-2 text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><b>Jef</b></td>
                                                    <td class="text-danger"><strong><?php echo strtoupper('Pending'); ?></strong></td>
                                                    <td><strong>10</strong></td>
                                                    <td><button class="btn btn-success csF16 pull-right">
                                                            <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                            &nbsp;View
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Ana</b></td>
                                                    <td class="text-danger"><strong><?php echo strtoupper('Pending'); ?></strong></td>
                                                    <td><strong>3<strong></td>
                                                    <td><button class="btn btn-success csF16 pull-right">
                                                            <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                            &nbsp;View
                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Jen Jo</b></td>
                                                    <td class="text-danger"><strong><?php echo strtoupper('Pending'); ?></strong></td>
                                                    <td><strong>8</strong></td>
                                                    <td> <button class="btn btn-success csF16 pull-right">
                                                            <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                            &nbsp;View
                                                        </button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="hr-box">
                                <div class="hr-box-header bg-header-green">
                                    <span class="pull-left">
                                        <h1 class="hr-registered">Employees With Completed Courses</h1>
                                    </span>
                                    <button class="btn btn-success pull-right">View All</button>

                                </div>
                                <div class="hr-innerpadding">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Employee Name</th>
                                                    <th>Status</th>
                                                    <th># of Courses</th>
                                                    <th class="col-xs-2 text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><b>Jef</b></td>
                                                    <td class="text-success"><strong><?php echo strtoupper('Completed'); ?></strong></td>
                                                    <td><strong>12</strong></td>
                                                    <td><button class="btn btn-success csF16 pull-right">
                                                            <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                            &nbsp;View
                                                        </button></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Jef</b></td>
                                                    <td class="text-success"><strong><?php echo strtoupper('Completed'); ?></strong></td>
                                                    <td><strong>10</strong></td>
                                                    <td><button class="btn btn-success csF16 pull-right">
                                                            <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                            &nbsp;View
                                                        </button></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Jef</b></td>
                                                    <td class="text-success"><strong><?php echo strtoupper('Completed'); ?></strong></td>
                                                    <td><strong>1</strong></td>
                                                    <td><button class="btn btn-success csF16 pull-right">
                                                            <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                            &nbsp;View
                                                        </button></td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="hr-box">
                                <div class="hr-box-header bg-header-green">
                                    <span class="pull-left">
                                        <h1 class="hr-registered">Department A </h1>
                                    </span>
                                </div>
                                <div class="hr-innerpadding">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="col-xs-3 text-left">Name</th>
                                                    <th class="col-xs-3 text-left">Type</th>
                                                    <th class="col-xs-3 text-left">Completion Status</th>
                                                    <th class="col-xs-3 text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="vam" colspan="4">

                                                        <div class="col-xs-3 col-sm-12 col-md-3 col-lg-3" style="padding-left: 0px;">
                                                            <b>Jef</b>
                                                        </div>
                                                        <div class="col-xs-3 col-sm-12 col-md-3 col-lg-3 text-left" style="padding-left: 0px;">
                                                            <b>Department</b>
                                                        </div>

                                                        <div class="col-xs-3 col-sm-12 col-md-3 col-lg-3" style="padding-left: 0px;">
                                                            <div class="progress">
                                                                <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                                                                    70%
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-3 col-sm-12 col-md-3 col-lg-3 text-right" style="padding-right: 0px;">

                                                            <button class="btn btn-success csF16">
                                                                <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                                &nbsp;Show Details
                                                            </button>
                                                        </div>
                                                    </td>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="hr-box">
                                <div class="hr-box-header bg-header-green">
                                    <span class="pull-left">
                                        <h1 class="hr-registered">Department B </h1>
                                    </span>
                                </div>
                                <div class="hr-innerpadding">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="col-xs-3 text-left">Name</th>
                                                    <th class="col-xs-3 text-left">Type</th>
                                                    <th class="col-xs-3 text-left">Completion Status</th>
                                                    <th class="col-xs-3 text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="vam" colspan="4">

                                                        <div class="col-xs-3 col-sm-12 col-md-3 col-lg-3" style="padding-left: 0px;">
                                                            <b>Jef</b>
                                                        </div>
                                                        <div class="col-xs-3 col-sm-12 col-md-3 col-lg-3 text-left" style="padding-left: 0px;">
                                                            <b>Department</b>
                                                        </div>

                                                        <div class="col-xs-3 col-sm-12 col-md-3 col-lg-3" style="padding-left: 0px;">
                                                            <div class="progress">
                                                                <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%">
                                                                    70%
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-3 col-sm-12 col-md-3 col-lg-3 text-right" style="padding-right: 0px;">

                                                            <button class="btn btn-success csF16 jsshowdetails">
                                                                <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                                &nbsp;Show Details
                                                            </button>
                                                        </div>
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
    </div>
</div>

<script>
    //horizontalBar
    var ctx = document.getElementById("departmentChart").getContext("2d");
    var data = {
        labels: ["HR", "IT", "Developent", "Accounts"],
        datasets: [{
            label: "Total: 60",
            backgroundColor: "blue",
            data: [10, 15, 15, 20]
        }]
    };

    var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            barValueSpacing: 20,

            title: {
                display: true,
                position: "top",
                text: "Departments",
                fontSize: 18,
                fontColor: "#111"
            },
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            plugins: {
                datalabels: {
                    formatter: (value, ctx) => {},
                    color: '#fff',
                }
            }


        }
    });


    //============ doughnut =====

    var ctx = document.getElementById("coursesChartdonut").getContext("2d");
    var data = {
        datasets: [{
            data: [12, 24],
            backgroundColor: [
                "#3366cc",
                "#fd7a2a"
                //"#fd7a2a"
            ],
        }],

        labels: [
            'English: 12',
            'Traning: 24'
            // 'Not Available'
        ]
    };

    //options
    var options = {
        title: {
            display: true,
            position: "top",
            text: "Courses",
            fontSize: 18,
            fontColor: "#111"
        },
        tooltips: {
            enabled: false
        },
        plugins: {
            datalabels: {
                formatter: (value, ctx) => {
                    let datasets = ctx.chart.data.datasets;
                    if (datasets.indexOf(ctx.dataset) === datasets.length - 1) {
                        let sum = datasets[0].data.reduce((a, b) => a + b, 0);
                        let percentage = Math.round((value / sum) * 100) + '%';
                        return percentage;
                    } else {
                        return percentage;
                    }
                },
                color: '#fff',
            }
        }
    };



    var coursesChartdonut = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: options
    });



    var ctx = document.getElementById("employeeChartdonut").getContext("2d");
    var data = {
        datasets: [{
            data: [12],
            backgroundColor: [
                "#3366cc"
            ],
        }],

        labels: [
            'Employees: 12'
            // 'Not Available'
        ]
    };

    //options
    var options = {
        title: {
            display: true,
            position: "top",
            text: "Employees",
            fontSize: 18,
            fontColor: "#111"
        },
        tooltips: {
            enabled: false
        },
        plugins: {
            datalabels: {
                formatter: (value, ctx) => {
                    let datasets = ctx.chart.data.datasets;
                    if (datasets.indexOf(ctx.dataset) === datasets.length - 1) {
                        let sum = datasets[0].data.reduce((a, b) => a + b, 0);
                        let percentage = Math.round((value / sum) * 100) + '%';
                        return percentage;
                    } else {
                        return percentage;
                    }
                },
                color: '#fff',
            }
        }
    };

    var employeeChartdonut = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: options
    });




    $(document).on('click', '.jsshowdetails', function(e) {
        e.preventDefault();
        alert('sdfsd sdfsdfsdf');
      //  $(this).parent().parent().next('tr').toggle();



    });


</script>