<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$adpEmployeeOptions = '<option value="0">[Select ADP Employee]</option>';
//
if ($adpEmployees) {
    foreach ($adpEmployees->workers as $employee) {
        //  _e((array)$employee, true);
        //   die;
        $adpEmployeeOptions .= '<option value="' . ($employee->associateOID) . '#' . ($employee->workerID->idValue) . '">' . ($employee->person->legalName->formattedName) . '</option>';
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
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo ucwords($company_name); ?> - <?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Manage Company</a>
                                    </div>

                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <a data-id="<?= $adp_company_data['sid']; ?>" data-status="<?= $adp_company_data['status']; ?>" class="site-btn <?= $adp_company_data['status'] == 1 ? 'btn-danger' : ''; ?>  js-dynamic-module-adp-btn"><?= $adp_company_data['status'] == 1 ? 'Disable Company On ADP' : 'Enable Company On ADP'; ?></a>
                                </div>
                            </div>

                            <br>
                            <div class="row">

                                <div class="col-sm-4">
                                    <canvas id="jsEmployeeCanvas"></canvas>
                                    <br>
                                    <p class="text-center"><em><strong class="text-danger">Employee Progress</strong></em></p>
                                </div>
                            </div>


                            <div class="row">

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <strong> Employees</strong>
                                    </div>

                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#onadp"><b>On ADP</b></a></li>
                                        <li><a data-toggle="tab" href="#offadp"><b>Off ADP</b></a></li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="onadp" class="tab-pane fade in active">
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Name / Email</th>
                                                                <th scope="col">Associate ID</th>
                                                                <th scope="col">DateTime</th>
                                                                <th scope="col">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (empty($employees)) {
                                                            ?>
                                                                <tr>
                                                                    <td colspan="4">
                                                                        <p class="alert alert-info text-center">
                                                                            No employees on ADP yet.
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            } else { ?>

                                                                <?php foreach ($employees as $employee) {
                                                                ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?php
                                                                            echo '<strong>' . remakeEmployeeName($employee) . '</strong><br />';
                                                                            echo $employee['email'];
                                                                            ?>
                                                                        </td>
                                                                        <td><?= $employee['associate_oid']; ?></td>
                                                                        <td><?= formatDateToDB($employee['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                                                        </td>

                                                                        <td>
                                                                            <button class="btn btn-success jsShowADPEmployeeDetails" data-id="<?= $employee['associate_oid'] ?>"><b>ADP Details</b></button>
                                                                            <button class="btn btn-danger jsADPEmployeeDelete" data-id="<?= $employee['sid'] ?>"><b>Unlink With ADP</b></button>
                                                                        </td>

                                                                    </tr>
                                                                <?php
                                                                } ?>
                                                            <?php }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="offadp" class="tab-pane fade">
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="col-sm-8">Employees</th>
                                                                <th scope="col" class="col-sm-4">ADP Employees</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if ($offADPEmployees) {
                                                                foreach ($offADPEmployees as $employee) {
                                                                    if ($employee['is_executive_admin'] != 1) {
                                                            ?>
                                                                        <tr data-id="<?= $employee['sid'] ?>" class="jsEmployeeRow">
                                                                            <td>
                                                                                <strong><?= remakeEmployeeName($employee); ?></strong>
                                                                                <p>Employee Status: <?= GetEmployeeStatus($employee['last_status_text'], $employee['active']); ?></p>
                                                                            </td>
                                                                            <td>
                                                                                <select class="jsSelect2" style="width: 100%"><?= $adpEmployeeOptions; ?></select>
                                                                            </td>
                                                                        </tr>
                                                                <?php  }
                                                                }
                                                            } else {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <div class="alert alert-info text-center">
                                                                            <strong>No employees found.</strong>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12 text-right">
                                                        <button class="btn btn-success jsADPSaveBtn">Link Employee With ADP</button>
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

<link rel="stylesheet" href="<?=base_url('assets/css/SystemModel.css')?>">
<script src="<?=base_url('assets/js/SystemModal.js')?>"></script>

<script>
    $(function() {
        $('.jsSelect2').select2();
        // 
        $('.jsADPSaveBtn').click(function(event) {
            //
            event.preventDefault();
            //
            var holdEmployeeLinks = [];
            //
            $('.jsEmployeeRow').map(function() {
                //
                var hasAdpEmployee = $(this).find('select option:selected').val();
                //
                if (hasAdpEmployee == '0') {
                    return false;
                }
                //
                holdEmployeeLinks.push({
                    employeeId: $(this).data('id'),
                    AdpEmployeeAssociateId: hasAdpEmployee.split('#')[0],
                    AdpEmployeeWorkerId: hasAdpEmployee.split('#')[1]
                })
            });
            //
            if (holdEmployeeLinks.length === 0) {
                alertify.alert('Please select ADP employee');
            } else {


                url = "<?= base_url() ?>manage_admin/saveadpemployees";
                alertify.confirm('Confirmation', "Are you sure you want to save?",
                    function() {
                        $('#loader_text_div').text('Processing');
                        $('#document_loader').show();
                        $.post(url, {
                                holdEmployeeLinks
                            })
                            .done(function(data) {
                                $('#loader_text_div').text('');
                                $('#document_loader').hide();
                                alertify.alert('ADP employees added successfully');
                                location.reload();
                            });
                    },
                    function() {});

            }
        });
    })

    //
    $(function() {
        $('.js-dynamic-module-adp-btn').click(function(e) {
            e.preventDefault();
            let megaOBJ = {};
            var _this = $(this);
            megaOBJ.Status = $(this).data('status');
            megaOBJ.Id = $(this).data('id');
            megaOBJ.CompanyId = <?= $company_sid; ?>;
            //
            alertify.confirm('Do you really want to ' + (megaOBJ.Status === 1 ? 'disable' : 'enable') + ' ADP Settings ?', function() {
                //
                $.post("<?= base_url('manage_admin/statusupdate'); ?>", megaOBJ, function(resp) {
                    if (resp.Status === false) {
                        alertify.alert('ERROR!', resp.Response);
                        return;
                    }
                    alertify.alert('SUCCESS!', 'ADP Settings has been ' + (megaOBJ.Status === 1 ? 'Disabled' : 'Enabled') + '.');
                    _this.text(megaOBJ.Status === 0 ? 'Disable Compay On ADP' : 'Enable Company On ADP');
                    //
                    if (megaOBJ.Status === 0) {
                        _this.data('status', 1);
                        _this.addClass('btn-danger');
                        _this.parent().parent().parent().find('.exclueded-state').removeClass('exclueded-state').addClass('inclueded-state');
                        _this.parent().parent().parent().find('.inclueded-state div').attr('style', 'color:green;').text('Enabled');
                    } else {
                        _this.data('status', 0);
                        _this.removeClass('btn-danger');
                        _this.parent().parent().parent().find('.inclueded-state').removeClass('inclueded-state').addClass('exclueded-state');
                        _this.parent().parent().parent().find('.exclueded-state div').attr('style', 'color:red;').text('Disabled');
                    }
                });
            }).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        });
    })

    //
    $('.jsADPEmployeeDelete').click(function(e) {
        e.preventDefault();
        let megaOBJ = {};
        var _this = $(this);
        megaOBJ.Id = $(this).data('id');
        //
        alertify.confirm('Do you really want to unlink this employee on ADP ?', function() {
            $('#loader_text_div').text('Processing');
            $('#document_loader').show();
            //
            $.post("<?= base_url('manage_admin/deleteadpemployee'); ?>", megaOBJ, function(resp) {
                if (resp.Status === false) {
                    alertify.alert('ERROR!', resp.Response);
                    return;
                }

            }).done(function(data) {
                $('#loader_text_div').text('');
                $('#document_loader').hide();
                alertify.alert('SUCCESS!', 'Employee Unlinked Sucessfully');
                location.reload();
            });
        });
    });



    $(document).on("click", ".jsShowADPEmployeeDetails", function(event) {
        //
        event.preventDefault();
        //
        startDetailProcessShow($(this).data("id"));
    });


    //
    function startDetailProcessShow(associateId) {
        //

        Modal({
                Id: "jsModalEmployeeDetail",
                Loader: "jsModalEmployeeDetailLoader",
                Title: "Employee Details",
                Body: '<div class="container"><div id="jsModalEmployeeDetailBody"></div></div>',
            },
            function() {
                //
                xhr = $.get(baseURI + "manage_admin/adpemployeedetail/" + associateId)
                    .success(function(resp) {
                         //
                         if (resp.hasOwnProperty("errors")) {
                            return alertify.alert(
                                "ERROR",
                                resp.errors.join("<br />"),
                                function () { }
                            );
                        }

                        $("#jsModalEmployeeDetailBody").html(resp.view);
                        ml(false, "jsModalEmployeeDetailLoader");

                    })
                    .fail(handleFailure);
            }
        );
    }
</script>

<script>
    $(function() {

        // Employees
        loadHourGraph('jsEmployeeCanvas', {
            data: {
                labels: ['On ADP', 'Off ADP'],
                datasets: [{
                    label: 'Dataset 1',
                    data: [
                        <?= count($employees); ?>,
                        <?= count($offADPEmployees); ?>,
                    ],
                    backgroundColor: [
                        '#fd7a2a',
                        '#3554dc',
                    ],
                }]
            },
            textToShow: "Employees"
        });
        //
        function loadHourGraph(ref, options) {

            const config = {
                type: 'pie',
                data: options.data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: options.textToShow
                        }
                    }
                },
            };
            new Chart(document.getElementById(ref), config);
        }
    })
</script>