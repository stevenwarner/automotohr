<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.4.0/dist/chartjs-plugin-datalabels.min.js"></script>
<?php 

$employeeOncomplynet=0;
$employeeOffcomplynet=0;
$totalEmployeesGraph=count($complynetEmployees);
foreach ($complynetEmployees as $rowGraph) {
    $rowGraph['complynet_email'] ? $employeeOncomplynet++ : $employeeOffcomplynet++;
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
                                        <label>Select Employees <span class="text-danger">*</span></label>
                                        <select id="jsEmployee" style="width: 100%" multiple>
                                            <option value="all">All</option>
                                            <?php if ($allEmployees) :
                                                foreach ($allEmployees as $employee) : ?>
                                                    <option value="<?= $employee['sid']; ?>">
                                                        <?php echo getUserNameBySID($employee['sid'], $remake = true); ?>
                                                    </option>
                                            <?php endforeach;
                                            endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <label>Status <span class="text-danger">*</span></label>
                                        <select id="jsStatusCompany" style="width: 100%">
                                            <option value="all">All</option>
                                            <option value="1">On ComplyNet</option>
                                            <option value="0">Off ComplyNet</option>
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

                               
                            </div>
                            <br>


                            <!-- Details -->
                            <div class="jsCompanies">
                                <br>
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-md-9 col-sm-12">
                                        <h4 style="margin: 0;"><strong>Employees</strong> - <small><?php echo $TotalCompanies; ?> Found</small></h4>
                                        </div>
                                        <div class=" col-md-1 col-sm-12" style="float: right;">
                                        <a href="javascript:;" class="btn btn-success pull-right" onclick="print_page('#print_div');"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
                                        </div>
                                        <div class=" col-md-2 col-sm-12" style="float: right;" >
                                            <button type="button" id="js-export" class="btn btn-success pull-right " onclick="csvExport();" >Export CSV</button>
                                        </div>
                                        </div>
                                    </div>


                                    <div class="panel-body">

                                        <?php if (!empty($links)) { ?>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <?php echo $links; ?>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                <div class="table-responsive table-outer" id="print_div">
                                                    <table class="table table-striped">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Employee Name</th>
                                                                <th scope="col">ComplyNet ID</th>
                                                                <th scope="col">ComplyNet Status</th>
                                                                <th scope="col">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php foreach ($complaynetCompaniesData as $rowData) {

                                                                $danger_bg = $rowData['complynet_email'] ? '' :  "p-3 mb-2 bg-danger text-white";

                                                            ?>
                                                                <tr>
                                                                    <td class="csVm <?php echo $danger_bg ?>">
                                                                        <strong><?php echo getUserNameBySID($rowData['sid'], $remake = true); ?></strong> <br>
                                                                        <span>Id: <?php echo $rowData['sid'] ?></span>
                                                                    </td>
                                                                    <td class="<?php echo $danger_bg ?>">
                                                                        <p><?php echo $rowData['complynet_email'] ? $rowData['complynet_email'] : ' - ' ?></p> <br>
                                                                    </td>
                                                                    <td class="csVm <?php echo $danger_bg ?>">
                                                                        <strong class="<?php echo $rowData['complynet_email'] ? 'text-success' : 'text-danger' ?>"><?php echo $rowData['complynet_email'] ? 'ON COMPLYNET' : 'OFF COMPLYNET' ?></strong>

                                                                    </td>

                                                                    <td class=" csVm <?php echo $danger_bg ?>">
                                                                        <?php if ($rowData['complynet_email']) { ?>
                                                                            <a class="btn btn-success" href="#">View</a><br><br>
                                                                            <a class="btn btn-danger" href="">Disable</a><br>
                                                                        <?php } else { ?>
                                                                            <a class="btn btn-success" href="">Add</a> <br>
                                                                        <?php } ?> <br>

                                                                    </td>

                                                                </tr>
                                                            <?php } ?>

                                                        </tbody>
                                                    </table>
                                                </div>

                                                </div>
                                            </div>
                                        </div>

                                        <?php if (!empty($links)) { ?>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <?php echo $links; ?>
                                                </div>
                                            </div>
                                        <?php } ?>

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

    .bg-danger {
        /*background-color: #f24a4a !important;*/
    }
</style>


<script>


//

var ctx = document.getElementById("myChartdonut").getContext("2d");
    var data = {
        datasets: [{
            data: [<?php echo $employeeOncomplynet; ?>, <?php echo  $employeeOffcomplynet; ?>],
            backgroundColor: [
                "#3366cc",
                "#fd7a2a"
                //"#fd7a2a"
            ],
        }],

        labels: [
            'ON COMPLYNET: <?php echo $employeeOncomplynet; ?>',
            ' OFF COMPLYNET: <?php echo  $employeeOffcomplynet; ?>'
            // 'Not Available'
        ]
    };

    //options
    var options = {
        title: {
            display: true,
            position: "top",
            text: "Total Employees <?php echo $totalEmployeesGraph; ?>",
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
    $(document).ready(function() {
        $('#jsEmployee').select2({
            closeOnSelect: false
        });

        $('#jsEmployee').select2('val', <?= json_encode($employeeSid); ?>);
    });


    function func_apply_filters() {
        var employee_sid = $('#jsEmployee').val();
        var status = $('#jsStatusCompany').val();
        var base_url = '<?php echo base_url('manage_admin/complynet/employee'); ?>';
        company_sid = '<?php echo $companySid; ?>'
        company_sid = company_sid == 'all' || company_sid == undefined || company_sid == null ? 'all' : encodeURIComponent(company_sid);
        employee_sid = employee_sid == 'all' || employee_sid == undefined || employee_sid == null ? 'all' : encodeURIComponent(employee_sid);
        status = status == 'all' || status == undefined || status == null ? 'all' : encodeURIComponent(status);
        var url = base_url + '/' + company_sid + '/' + employee_sid + '/' + status;

        window.location = url;
    }

    function csvExport() {
        var employee_sid = $('#jsEmployee').val();
        var status = $('#jsStatusCompany').val();
        var base_url = '<?php echo base_url('manage_admin/complynet/employeecsv'); ?>';
        company_sid = '<?php echo $companySid; ?>'
        company_sid = company_sid == 'all' || company_sid == undefined || company_sid == null ? 'all' : encodeURIComponent(company_sid);
        employee_sid = employee_sid == 'all' || employee_sid == undefined || employee_sid == null ? 'all' : encodeURIComponent(employee_sid);
        status = status == 'all' || status == undefined || status == null ? 'all' : encodeURIComponent(status);
        var url = base_url + '/' + company_sid + '/' + employee_sid + '/' + status;

        window.open(url); 
    }


    function func_reset_filters() {
        window.location = '<?php echo base_url('manage_admin/complynet/employee/' . $companySid); ?>';

    }

    $('#jsStatusCompany').val('<?php echo $status; ?>');
</script>


<!--  -->
<script src="<?= base_url(_m('assets/2022/js/complynet/report', 'js', time())); ?>"></script>