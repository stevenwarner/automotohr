<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
                                        <h1 class="page-title"><i class="fa fa-briefcase"></i>Add Manual Commission</h1>
                                        <a href="<?= base_url('manage_admin/marketing_agencies/manage_commissions/' . $marketing_agency_sid); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Manage Commissions</a>
                                    </div>
                                    <div class="add-new-company">
                                        <form method="POST" enctype="multipart/form-data" id="form_add_new_employer">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="heading-title page-title">
                                                        <span class="page-title">Marketing Agency:</span>&nbsp;<span class="text-success"><?php echo ucwords($marketing_agency_info['full_name']); ?></span>
                                                        <!--                                                        <h1 class="page-title">Commission Detail</h1>-->
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="company">Company Name</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="company" name="company">
                                                                <option value="">Select Company</option>
                                                                <?php foreach ($companies as $company) {
                                                                    echo '<option value="' . $company['sid'] . '/' . $company['email'] . '/' . $company['CompanyName'] . '">' . $company['CompanyName'] . '</option>';
                                                                } ?>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label class="invoice_number">Applied Invoice Number</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="invoice_number" name="invoice_number">
                                                                <option value="">Select Number</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                    <div class="field-row">
                                                        <label for="date">Date</label>
                                                        <input class="hr-form-fileds datepicker" id="date" name="date" value="" readonly="readonly" />
                                                        <!--                                                        --><?php //echo form_input('date', set_value('date'), 'class="hr-form-fileds datepicker"'); 
                                                                                                                        ?>
                                                        <!--                                                        --><?php //echo form_error('date'); 
                                                                                                                        ?>
                                                    </div>
                                                </div>
                                                <!--                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">-->
                                                <!--                                                    <div class="field-row">-->
                                                <!--                                                        <label for="applied">Commission Applied</label>-->
                                                <!--                                                        <div class="hr-select-dropdown">-->
                                                <!--                                                            <select class="invoice-fields" id="applied" name="applied"  >-->
                                                <!--                                                                <option value="">Select Commission</option>-->
                                                <!--                                                                <option value="secondary">Secondary</option>-->
                                                <!--                                                                <option value="primary">Primary</option>-->
                                                <!--                                                            </select>-->
                                                <!--                                                        </div>-->
                                                <!--                                                    </div>-->
                                                <!--                                                </div>-->

                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-center hr-btn-panel">
                                                    <!--<input type="submit" class="search-btn" value="Register" name="submit">-->
                                                    <input name="action" type="hidden" id="submit_action" value="">
                                                    <input type="button" name="action" value="Add Commission" onclick="return fValidateForm()" class="site-btn">
                                                    <a href="<?= base_url('manage_admin/marketing_agencies/manage_commissions/' . $marketing_agency_sid); ?>" class="black-btn"> Cancel </a>
                                                </div>
                                            </div>
                                        </form>
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
    // get the states
    $(document).ready(function() {
        var myid = $('#state_id').html();

        setTimeout(function() {
            $("#country").change();
        }, 1000);

        if (myid) {
            setTimeout(function() {
                $('#state').val(myid);
            }, 1200);
        }

        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        });

        $('#company').change(function() {
            var cid = $('#company').val();
            if (cid != '') {
                $('#invoice_number').html('');
                $('#invoice_number').append('<option value="">Select Number</option>');
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('manage_admin/marketing_agencies/ajax_responder') ?>',
                    data: {
                        cid: cid,
                        perform_action: 'fetch_invoices'
                    },
                    success: function(data) {
                        var invoices = JSON.parse(data);
                        console.log(data);
                        console.log(invoices);
                        $.each(invoices, function(index, object) {
                            var sid = object.sid;
                            var value = object.value;
                            var invoice_type = object.invoice_type;
                            var number = object.invoice_number;
                            var option = '<option value="' + sid + '/' + value + '/' + invoice_type + '">' + number + '</option>';
                            $('#invoice_number').append(option);

                        });
                        return false;
                    },
                    error: function() {

                    }
                });
            }
        });
    });

    function getStates(val, states) {
        var html = '';
        if (val == '') {
            $('#state').html('<option value="">Select State</option>');
        } else {
            allstates = states[val];
            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                html += '<option value="' + id + '">' + name + '</option>';
            }
            $('#state').html(html);
        }
    }

    function fValidateForm() {
        $('#form_add_new_employer').validate({
            debug: true,
            rules: {
                company: {
                    required: true
                },
                invoice_number: {
                    required: true
                },
                date: {
                    required: true
                },
                applied: {
                    required: true
                }
            },
            messages: {
                company: {
                    required: 'Company Name is Required'
                },
                invoice_number: {
                    required: 'Invoice Number is Required'
                },
                date: {
                    required: 'Date is Required'
                },
                applied: {
                    required: 'Commission Applied (Primary or Secondary) is Required'
                }
            }
        });

        if ($('#form_add_new_employer').valid()) {
            document.getElementById('form_add_new_employer').submit();
        }
    }
</script>