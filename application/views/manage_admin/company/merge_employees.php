<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$active_companies = '';
$active_companies .= '<option value="0">[Select Company]</option>';
foreach ($companies as $company)
    $active_companies .= '<option value="' . ($company['sid']) . '">' . ($company['CompanyName']) . '</option>';
?>
<style>
    .jsRow {
        display: none;
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
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title" style="width: 100%;"><i class="fa fa-users" aria-hidden="true"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <!-- Main Page -->
                                    <div id="js-main-page">
                                        <div class="hr-setting-page">
                                            <?php echo form_open(base_url('manage_admin/copy_applicants/'), array('id' => 'copy-form')); ?>
                                            <ul>
                                                <li>
                                                    <label>Select Company <span class="cs-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-from-company">
                                                            <option value="0">Select Company</option>
                                                            <?php
                                                            foreach ($companies as $key => $company) {
                                                                echo '<option id="' . ($company['sid']) . '" value="' . ($company['sid']) . '">' . ($company['CompanyName']) . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li class="jsRow">
                                                    <label>Primary Employee <span class="cs-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-primary-employees"></select>
                                                    </div>
                                                </li>
                                                <li class="jsRow">
                                                    <label>Secondary Employee <span class="cs-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-secondary-employees"></select>
                                                    </div>
                                                </li>
                                                <li class="jsRow">
                                                    <a class="site-btn" id="js-merge-employees" href="#">Merge Employees</a>
                                                </li>
                                            </ul>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>

                                    <!-- Employees listing Block -->
                                    <div id="js-enployees-list-block">
                                        <h4 class="js-hide-fetch"><b>Total</b>: <span><span id="js-total-employees">0</span> employees found</span></h4>
                                        <div class="hr-box js-hide-fetch">
                                            <div class="hr-box-header">
                                                <h4>Copy Specific Employees</h4>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <div class="table-responsive">
                                                    <form action="javascript:void(0)" id="js-employee-form" method="POST">
                                                        <button type="button" class="btn btn-success pull-right js-copy-employees-btn" style="margin-bottom: 10px;">Copy Selected Employees</button>
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th><input type="checkbox" class="js-check-all" /></th>
                                                                    <th class="text-center">ID</th>
                                                                    <th>Employee Name</th>
                                                                    <th>Email</th>
                                                                    <th>Employee Type</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="js-employees-list-show-area"></tbody>
                                                        </table>
                                                        <input type="hidden" name="copy_to" id="form-copy" />
                                                        <input type="hidden" name="form_action" />
                                                        <button type="button" class="btn btn-success pull-right js-copy-employees-btn">Copy Selected Employees</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <style>
        .my_loader {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 99;
            background-color: rgba(0, 0, 0, .7);
        }

        .loader-icon-box {
            position: absolute;
            top: 50%;
            left: 50%;
            width: auto;
            z-index: 9999;
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        .loader-icon-box i {
            font-size: 14em;
            color: #81b431;
        }

        .loader-text {
            display: inline-block;
            padding: 10px;
            color: #000;
            background-color: #fff !important;
            border-radius: 5px;
            text-align: center;
            font-weight: 600;
        }
    </style>

    <!-- Loader -->
    <div id="js-loader" class="text-center my_loader" style="display: none;">
        <div id="file_loader" class="file_loader cs-loader-file" style="display: none; height: 1353px;"></div>
        <div class="loader-icon-box cs-loader-box">
            <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
            <div class="loader-text cs-loader-text" id="js-loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...</div>
        </div>
    </div>
    <style>
        #js-enployees-list-block {
            display: none;
        }

        .cs-required {
            font-weight: bolder;
            color: #cc0000;
        }

        /* Alertify CSS */
        .ajs-header {
            background-color: #81b431 !important;
            color: #ffffff !important;
        }

        .ajs-ok {
            background-color: #81b431 !important;
            color: #ffffff !important;
        }

        .ajs-cancel {
            background-color: #81b431 !important;
            color: #ffffff !important;
        }
    </style>


    <script>
        $(function MergeEmployees() {
            //
            var xhr = null;
            var companyId = null;
            // Select 2
            $('#js-from-company').select2();
            //
            $('#js-from-company').change(function() {
                //
                companyId = $(this).val();
                //
                if (companyId === 0) {
                    $('.jsRow').hide();
                    return;
                }
                //
                GetCompanyEmployees();
            });

            $('#js-primary-employees').change(function() {
                $('#js-secondary-employees').find('option').prop('disabled', false)
                $('#js-secondary-employees').find('option[value="' + ($(this).val()) + '"]').prop('disabled', true)
                $('#js-secondary-employees').select2();
            })

            $('#js-secondary-employees').change(function() {
                $('#js-primary-employees').find('option').prop('disabled', false)
                $('#js-primary-employees').find('option[value="' + ($(this).val()) + '"]').prop('disabled', true)
                $('#js-primary-employees').select2();
            })

            //
            $('#js-merge-employees').click(function(event) {
                //
                event.preventDefault();
                //
                let
                    primaryId = $('#js-primary-employees').val(),
                    secondaryId = $('#js-secondary-employees').val();
                secondaryEmail = $('#js-secondary-employees').find('option[value="' + (secondaryId) + '"]').data('email');
                //
                if (primaryId === '0') {
                    return alertify.alert('Error!', 'Please select a primary employee', function() {});
                }
                //
                if (secondaryId === '0') {
                    return alertify.alert('Error!', 'Please select a secondary employee', function() {});
                }
                //
                StartMerge(primaryId, secondaryId, secondaryEmail);
            });

            //
            function GetCompanyEmployees() {
                //
                if (xhr !== null) {
                    xhr.abort();
                }
                //
                xhr = $.get(
                    "<?= base_url("manage_admin/merge_employees/employees"); ?>/" + companyId,
                    function(resp) {
                        //
                        let rows = '<option value="0"></option>';
                        //
                        resp.map(function(emp) {
                            rows += '<option value="' + (emp['id']) + '" data-email="' + (emp['email']) + '">' + (emp['name']) + '</option>';
                        });
                        //
                        $('#js-primary-employees').html(rows).select2()
                        $('#js-secondary-employees').html(rows).select2()
                        //
                        $('.jsRow').show();
                    });
            }

            //
            function StartMerge(primaryId, secondaryId, secondaryEmail) {
                //
                var ref = alertify.alert(
                        'Merging',
                        'Please wait while we are merging employees.',
                        function() {
                            return false;
                        }
                    )
                    .set('closable', false)
                    .set('movable', false)
                //
                $.ajax({
                        url: "<?= base_url("manage_admin/merge_employees/merge"); ?>",
                        method: "POST",
                        data: {
                            company_sid: companyId,
                            primary_employee_sid: primaryId,
                            secondary_employee_sid: secondaryId,
                            secondary_employee_email: secondaryEmail
                        }
                    })
                    .always(function() {
                        ref.close();
                    })
                    .fail(function(xhr, code) {
                        // alert(
                        //     code
                        // );
                    })
                    .done(function() {
                        alertify.alert('Success', 'You have successfully merged the employees.', function() {
                            window.location.reload();
                        });
                    });

            }
        });
    </script>