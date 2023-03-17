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
                                <div class="col-sm-6 text-left">
                                    <a data-id="<?= $adp_company_data['sid']; ?>" data-status="<?= $adp_company_data['status']; ?>" class="site-btn <?= $adp_company_data['status'] == 1 ? 'btn-danger' : ''; ?>  js-dynamic-module-adp-btn"><?= $adp_company_data['status'] == 1 ? 'Disable' : 'Enable'; ?></a>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <button class="btn btn-success  jsADPSaveBtn">Link Employee With ADP</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
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
                                                <?php if ($employees) {
                                                    foreach ($employees as $employee) {
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
                                </div>
                            </div>

                            <hr>
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


<!-- Loader Start -->
<div id="document_loader" class="text-center my_loader" style="display: none; z-index: 1234;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i aria-hidden="true" class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" id="loader_text_div" style="display:block; margin-top: 35px;"></div>
    </div>
</div>
<!-- Loader End -->




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
                    _this.text(megaOBJ.Status === 0 ? 'Disable' : 'Enable');
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
</script>