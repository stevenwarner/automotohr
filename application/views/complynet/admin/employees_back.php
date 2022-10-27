<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.4.0/dist/chartjs-plugin-datalabels.min.js"></script>

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
                            <div class="row">
                               <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                                <canvas id="myChartdonut" width="400" height="300"></canvas>
                            </div>
                        </div>
                            <br />
                         
                            <!-- Details -->
                            <div class="jsCompanies">
                                <br>
                                <div class="panel panel-success">
                                    
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Employee Name</th>
                                                                <th scope="col">E-mail</th>
                                                                <th scope="col">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php foreach ($employeesData as $rowData) {                                                            


                                                            ?>
                                                                <tr>
                                                                    <td class="csVm">
                                                                        <strong><?php echo getUserNameBySID($rowData['sid'], $remake = true) ; ?></strong> <br>
                                                                    </td>
                                                                    <td class="csVm">
                                                                        <strong><?php echo $rowData['email']; ?></strong> <br>
                                                                    </td>
                                                                    <td class="csVm">
                                                                      
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
        float: right;
        cursor: pointer;


    }

    .viewdetail {
        float: right;
        text-align: right;
        cursor: pointer;
        text-decoration: underline;

    }
</style>


<script>
    $('.divdetail').hide();
    $(".viewdetail").click(function() {
        $(this).find('.divdetail').toggle();
    });


    $(document).ready(function() {
        $('#jsParentCompany').select2();
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

<script>

    //============ doughnut =====

    var ctx = document.getElementById("myChartdonut").getContext("2d");
    var data = {
        datasets: [{
            data: [12, 144],
            backgroundColor: [
                "#3366cc",
                "#fd7a2a"
                //"#fd7a2a"
            ],
        }],

        labels: [
            'ComplyNet: 12',
            'NocomplyNet: 44'
            // 'Not Available'
        ]
    };

    //options
    var options = {
        title: {
            display: true,
            position: "top",
            text: "Total Employee <?php echo $recordsfor; ?> 56",
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


<!--  -->
<script src="<?= base_url(_m('assets/2022/js/complynet/report', 'js', time())); ?>"></script>