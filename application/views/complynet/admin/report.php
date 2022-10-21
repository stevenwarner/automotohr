<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.4.0/dist/chartjs-plugin-datalabels.min.js"></script>
<?php
$allActiveCompanies = count(db_get_all_active_companies());
$companyComplynetenabled = 0;
$companyComplynetdisabled = 0;

foreach ($onComplaynetCompnies as $rowGraph) {
    if ($rowGraph['status'] == '1') {
        $companyComplynetenabled++;
    }
    if ($rowGraph['status'] == '0') {
        $companyComplynetdisabled++;
    }
}

?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <!-- Heading -->
                            <div class="heading-title page-title">
                                <h1 class="page-title" style="width: 100%;"><i class="fa fa-users" aria-hidden="true"></i><?php echo $page_title; ?></h1>
                            </div>

                            <div class="clearfix"></div>

                            <br />
                            <!-- Filter -->
                            <div class="jsFilter">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <label>Select Companies <span class="text-danger">*</span></label>
                                        <select id="jsParentCompany" style="width: 100%" multiple>
                                            <option value="all">All</option>
                                            <?php if ($companies) :
                                                foreach ($companies as $company) : ?>
                                                    <option value="<?= $company['automotohr_id']; ?>">
                                                        <?= $company['automotohr_name']; ?>
                                                    </option>
                                            <?php endforeach;
                                            endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select id="jsStatusCompany" style="width: 100%">
                                            <option value="all">All</option>
                                            <option value="0">Disabled</option>
                                            <option value="1">Enabled</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 text-right">
                                        <br>
                                        <button class="btn btn-success" onclick="func_apply_filters();">Apply Search</button>
                                        <button class="btn btn-default" onclick="func_reset_filters();">Reset Search</button>

                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                                    <canvas id="myChartdonut" width="400" height="200"></canvas>
                                </div>

                                <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                                    <canvas id="myChartdonut2" width="400" height="200"></canvas>
                                </div>
                            </div>
                            <br>

                            <!-- Details -->
                            <div class="jsCompanies">
                                <br>
                                <div class="panel panel-success">
                                    <div class="panel-heading">

                                        <div class="row">
                                            <div class="col-md-9 col-sm-12">
                                                <h4 style="margin: 0;"><strong>Companies</strong> - <small><?php echo $TotalCompanies; ?> Found</small></h4>
                                            </div>
                                            <div class=" col-md-1 col-sm-12" style="float: right;">
                                                <a href="javascript:;" class="btn btn-success pull-right" onclick="print_page('#print_div');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                                            </div>
                                            <div class=" col-md-2 col-sm-12" style="float: right;">
                                                <button type="button" id="js-export" class="btn btn-success pull-right " onclick="csvExport();">Export CSV</button>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="table-responsive table-outer" id="print_div">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped">
                                                            <caption></caption>
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Company</th>
                                                                    <th scope="col">ComplyNet Company</th>
                                                                    <th scope="col">Onboard Status</th>
                                                                    <th scope="col">Status</th>
                                                                    <th scope="col">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                                <?php foreach ($complaynetCompaniesData as $rowData) {

                                                                    $complynetLocationCount = getComplynetLocationsCount($rowData['automotohr_id']);
                                                                    $complynetDepartmentsCount = getComplynetDepartmentsCount($rowData['automotohr_id']);
                                                                    $complynetjobRoleCount = getComplynetjobRoleCount($rowData['automotohr_id']);

                                                                    if ($complynetLocationCount >= 1) {
                                                                        $complynetLocationCount = 1;
                                                                    }

                                                                    $totalComplynetRequirement = $complynetLocationCount + $complynetDepartmentsCount + $complynetjobRoleCount;

                                                                    //
                                                                    $automotohrLocationCount = 1; //getAutomotohrLocationsCount($rowData['automotohr_id']);
                                                                    $automotohrjobRoleCount = getAutomotohrjobRoleCount($rowData['automotohr_id']);
                                                                    $automotohrDepartmentCount = getAutomotohrDepartmentsCount($rowData['automotohr_id']);
                                                                    $totalAutomotohrRequirement = $automotohrLocationCount + $automotohrjobRoleCount + $automotohrDepartmentCount;
                                                                    //
                                                                    $onbordingStatusCompleted = ($totalComplynetRequirement * 100) / $totalAutomotohrRequirement;

                                                                ?>
                                                                    <tr>
                                                                        <td class="csVm">
                                                                            <strong><?php echo $rowData['automotohr_name']; ?></strong> <br>
                                                                            <span>Id: <?php echo $rowData['automotohr_id']; ?></span> <br>
                                                                        </td>
                                                                        <td class="csVm">
                                                                            <strong><?php echo $rowData['complynet_name']; ?></strong> <br>
                                                                            <span>Id: <?php echo $rowData['complynet_id']; ?></span>
                                                                        </td>
                                                                        <td class="csVm">
                                                                            <div class="progress">
                                                                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $onbordingStatusCompleted ?> " aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $onbordingStatusCompleted . '%' ?>;">
                                                                                </div>
                                                                            </div>
                                                                            <p class="text-center"><?php echo ceil($onbordingStatusCompleted) . '%' ?> Completed
                                                                            <div class="viewdetail" class="glyphicon glyphicon-plus"> <span class="glyphicon glyphicon-plus"></span> <br>

                                                                                <div class="divdetail">
                                                                                    <strong style="float: left;  width=100%;">Locations:</strong><?php if ($complynetLocationCount > 0) {
                                                                                                                                                        echo ceil((100 * $complynetLocationCount) / $automotohrLocationCount);
                                                                                                                                                    } else {
                                                                                                                                                        echo "0";
                                                                                                                                                    } ?>% Completed <br>
                                                                                    <strong style="float: left;  width=100%;">Departments:</strong> <?php if ($complynetDepartmentsCount > 0) {
                                                                                                                                                        echo ceil((100 * $complynetDepartmentsCount) / $automotohrDepartmentCount);
                                                                                                                                                    } else {
                                                                                                                                                        echo "0";
                                                                                                                                                    } ?>% Completed <br>
                                                                                    <strong style="float: left;  width=100%;">Job Roles:</strong> <?php if ($complynetjobRoleCount > 0) {
                                                                                                                                                        echo ceil((100 * $complynetjobRoleCount) / $automotohrjobRoleCount);
                                                                                                                                                    } else {
                                                                                                                                                        echo "0";
                                                                                                                                                    } ?>% Completed
                                                                                </div>
                                                                            </div>
                                                                            </p>


                                                                        </td>

                                                                        <td class="csVm">
                                                                            <strong class="<?php echo $rowData['status'] ? 'text-success"' : 'text-danger"' ?>><?php echo $rowData['status'] ? 'ENABLED' : 'DISABLED' ?></strong>
                                                                    </td>
                                                                    <td class=" csVm">
                                                                                <a class="btn btn-success" href="<?php echo base_url('manage_admin/complynet/employee/' . $rowData['automotohr_id']); ?>">View</a>

                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div id="bulk_email_modal" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header modal-header-bg">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Company Name </h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="compose-message">
                                                <div class="universal-form-style-v2">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Loader Start -->
                            <div id="document_loader" class="text-center my_loader" style="display: none; z-index: 1234;">
                                <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
                                <div class="loader-icon-box">
                                    <i aria-hidden="true" class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
                                    <div class="loader-text" id="loader_text_div" style="display:block; margin-top: 35px;"></div>
                                </div>
                            </div>
                            <!-- Loader End -->

                            <?php if (!empty($links)) { ?>
                                <hr />
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php echo $links; ?>
                                    </div>
                                </div>
                            <?php } ?>

                            <!-- Loader -->
                            <?php //$this->load->view('loader', ['props' => 'id="jsReportComplyNet"']); 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .modal-backdrop {
        z-index: 1;
    }

    .universal-form-style-v2 ul li label,
    .universal-form-style-v2 form label {
        float: none !important;
    }


    .csVm {
        vertical-align: middle !important;
    }

    .divdetail {
        width: 100%;
        float: right;
        cursor: pointer;


    }

    .viewdetail {
        width: 100%;
        float: right;
        text-align: right;
        cursor: pointer;
        text-decoration: underline;

    }
</style>


<script>
    //============ doughnut =====
    var ctx = document.getElementById("myChartdonut").getContext("2d");
    var data = {
        datasets: [{
            data: [<?php echo ($companyComplynetenabled + $companyComplynetdisabled); ?>, <?php echo ($allActiveCompanies - ($companyComplynetenabled + $companyComplynetdisabled)); ?>],
            backgroundColor: [
                "#3366cc",
                "#fd7a2a"
                //"#fd7a2a"
            ],
        }],

        labels: [
            'On ComplyNet: <?php echo ($companyComplynetenabled + $companyComplynetdisabled); ?>',
            'Off ComplyNet: <?php echo ($allActiveCompanies - ($companyComplynetenabled + $companyComplynetdisabled)); ?>'
            // 'Not Available'
        ]
    };

    //options
    var options = {
        title: {
            display: true,
            position: "top",
            text: "Total Companies <?php echo $allActiveCompanies; ?>",
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

    var myDoughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: options
    });

    ///

    var ctx = document.getElementById("myChartdonut2").getContext("2d");
    var data = {
        datasets: [{
            data: [<?php echo $companyComplynetenabled; ?>, <?php echo  $companyComplynetdisabled; ?>],
            backgroundColor: [
                "#3366cc",
                "#fd7a2a"
                //"#fd7a2a"
            ],
        }],

        labels: [
            'ENABLED: <?php echo $companyComplynetenabled; ?>',
            'DISABLED: <?php echo  $companyComplynetdisabled; ?>'
            // 'Not Available'
        ]
    };

    //options
    var options = {
        title: {
            display: true,
            position: "top",
            text: "Total ComplyNet Companies <?php echo ($companyComplynetenabled + $companyComplynetdisabled); ?>",
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

    var myDoughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: options
    });
    //

    function print_page(elem) {
        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>' + 'Advanced Hr Reports - Active New Hire Categories' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="http://automotohr.local/assets/manage_admin/css/style.css" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="http://automotohr.local/assets/manage_admin/css/font-awesome-animation.min.css" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="http://automotohr.local/assets/manage_admin/css/bootstrap.css" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="http://automotohr.local/assets/manage_admin/css/font-awesome.css" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="http://automotohr.local/assets/manage_admin/css/responsive.css" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="http://automotohr.local/assets/manage_admin/css/jquery-ui.css" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="http://automotohr.local/assets/css/jquery.datetimepicker.css" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="http://automotohr.local/assets/images/favi-icon.png" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="http://automotohr.local/assets/alertifyjs/css/alertify.min.css" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="http://automotohr.local/assets/alertifyjs/css/themes/default.min.css" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="http://automotohr.local/assets/manage_admin/css/select2.css" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="http://automotohr.local/assets/manage_admin/css/chosen.css" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="http://automotohr.local/assets/css/chosen.css" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="http://automotohr.local/assets/manage_admin/js/jquery-1.11.3.min.js"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();
    }
</script>

<script>
    $('.divdetail').hide();
    $(".viewdetail").click(function() {
        $(this).find('.divdetail').toggle();
    });


    $(document).ready(function() {
        $('#jsParentCompany').select2({
            closeOnSelect: false
        });

        $('#jsParentCompany').select2('val', <?= json_encode($companySid); ?>);
    });

    function func_apply_filters() {
        var company_sid = $('#jsParentCompany').val();
        var status = $('#jsStatusCompany').val();
        var base_url = '<?php echo base_url('manage_admin/complynet/report'); ?>';

        company_sid = company_sid == 'all' || company_sid == undefined || company_sid == null ? 'all' : encodeURIComponent(company_sid);
        status = status == 'all' || status == undefined || status == null ? 'all' : encodeURIComponent(status);
        var url = base_url + '/' + company_sid + '/' + status;

        window.location = url;
    }


    function csvExport() {
        var company_sid = $('#jsParentCompany').val();
        var status = $('#jsStatusCompany').val();
        var base_url = '<?php echo base_url('manage_admin/complynet/reportcsv'); ?>';

        company_sid = company_sid == 'all' || company_sid == undefined || company_sid == null ? 'all' : encodeURIComponent(company_sid);
        status = status == 'all' || status == undefined || status == null ? 'all' : encodeURIComponent(status);
        var url = base_url + '/' + company_sid + '/' + status;

        window.open(url);
    }

    function func_reset_filters() {
        window.location = '<?php echo base_url('manage_admin/complynet/report'); ?>';

    }

    $('#jsStatusCompany').val('<?php echo $status; ?>');

    function getcompanydata(companyid, companyname) {
        $('#loader_text_div').text('Processing');
        $('#document_loader').show();

        $.ajax({
            'url': '<?php echo base_url('manage_admin/complynet/getcompanyemployees/'); ?>' + companyid,
            'type': 'GET',

            success: function(urls) {

                $('.modal-body').html(urls);
                $('#document_modal .modal-footer').html('footer_content');
                $('.modal-title').html('Company Name: ' + companyname);
                $('#bulk_email_modal').modal("toggle");
                $('#loader_text_div').text('');
                $('#document_loader').hide();

            }
        });

    }
</script>

<!--  -->
<script src="<?= base_url(_m('assets/2022/js/complynet/report', 'js', time())); ?>"></script>