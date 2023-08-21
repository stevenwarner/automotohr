<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style type="text/css">
    .caption h3 {
        margin-top: 0px !important;
        color: #fff !important;
    }

    .caption h4 {
        margin-bottom: 0px !important;
        color: #fff !important;
    }

    .success-block {
        background: #28a745 !important;
    }

    .error-block {
        background: #dc3545 !important;
    }

    .post-block {
        background: #007bff !important;
    }

    .put-block {
        background: #674ead !important;
    }

    pre {
        background: #000 !important;
        color: #fff !important;
    }

    .vam {
        vertical-align: middle !important;
    }

    .thumbnail {
        border-radius: 5px;
        box-shadow: 0 0 5px 1px #eee;
    }
</style>

<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $title; ?></h1>
                                    </div>
                                    <br />
                                    <br />
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="hr-search-criteria opened">
                                                <strong>Click to modify search criteria</strong>
                                            </div>

                                            <form method="post" action="<?php echo current_url(); ?>">
                                                <div class="hr-search-main search-collapse-area" style="display: block;">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-md-5">
                                                            <div class="field-row">
                                                                <label>Keyword:</label>
                                                                <input class="invoice-fields" id="jsKeyword" name="keyword" value="<?php echo $keyword; ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-md-2">
                                                            <div class="field-row">
                                                                <label>Status</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" id="status" name="status">
                                                                        <option value="all">All</option>
                                                                        <option value="Active">Active</option>
                                                                        <option value="Inactive">Inactive</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-md-5">
                                                            <div class="field-row">
                                                                <label>Companies</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields" id="js_companies" name="companies[]" multiple>
                                                                        <option value="0">All</option>
                                                                        <?php foreach ($complynet_companies as $companyRow) { ?>
                                                                            <option value="<?php echo $companyRow['company_sid']; ?>"><?php echo $companyRow['company_name'] ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-md-3">

                                                        </div>
                                                        <div class="col-xs-12 col-md-3">

                                                        </div>
                                                        <div class="col-xs-12 col-md-3">
                                                            <div class="field-row">
                                                                <label>&nbsp;</label>
                                                                <button type="submit" class="btn btn-success btn-block btn-equalizer">Search</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-md-3">
                                                            <div class="field-row">
                                                                <label>&nbsp;</label>
                                                                <a href="<?= base_url('manage_admin/reports/complynetoverview'); ?>" class="btn btn-default btn-block btn-equalizer">Clear Filter</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-xs-4">
                                            <canvas id="Chartdonutoverview"></canvas>

                                        </div>
                                        <div class="col-xs-8">
                                            sdfsdf
                                            Bar
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
</div>

<script>
    $("#status").val("<?php echo $status ?>");

    $('#js_companies').select2({
        closeOnSelect: false
    });

    $('#js_companies').select2('val', [<?php echo $companies!=''? $companies:'0'; ?>]);

    //============ doughnut =====

    var ctx = document.getElementById("Chartdonutoverview").getContext("2d");
    var data = {
        datasets: [{
            data: [<?php echo $overview_data['companiesTotalEmployeesOnComplynet']; ?>, <?php echo $overview_data['companiesTotalEmployees'] - $overview_data['companiesTotalEmployeesOnComplynet']; ?>],
            backgroundColor: [
                "#3366cc",
                "#fd7a2a"
            ],
        }],

        labels: [
            'OnComplyNet: <?php echo $overview_data['companiesTotalEmployeesOnComplynet']; ?>',
            'OfComplynet: <?php echo $overview_data['companiesTotalEmployees'] - $overview_data['companiesTotalEmployeesOnComplynet']; ?>'
        ]
    };

    //options
    var options = {
        title: {
            display: true,
            position: "top",
            text: "Total Employees: <?php echo $overview_data['companiesTotalEmployees'] ?>",
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
</script>