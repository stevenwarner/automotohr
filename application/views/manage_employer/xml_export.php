<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_config'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>XML Jobs Feed</span>
                        </div>
                        <?php if ($count > 0) { ?>
                            <form action="<?= base_url() ?>xml_export/export_all_jobs" method="POST" id="xml_form">
                                <div style="display:none;">
                                    <div class="xml-container">
                                        <div class="xml-header">
                                            <h4>Select Attribute(s):</h4>
                                        </div>
                                        <ul>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="sid" checked>
                                                </div>
                                                <span>Job Id</span>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="user_sid" checked>
                                                </div>
                                                <span>Employer Id</span>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="active" checked>
                                                </div>
                                                <span>Job Status</span>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="views" checked>
                                                </div>
                                                <span>Job Views</span>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="activation_date" checked>
                                                </div>
                                                <span>Activation Date</span>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="Title" checked>
                                                </div>
                                                <span>Job Title</span>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="JobType" checked>
                                                </div>
                                                <span>Job Type</span>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="JobCategory" checked>
                                                </div>
                                                <span>Job Category</span>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="Location_Country" checked>
                                                </div>
                                                <span>Country</span>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="Location_State" checked>
                                                </div>
                                                <span>State</span>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="Location_ZipCode" checked>
                                                </div>
                                                <span>ZipCode</span>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="JobDescription" checked>
                                                </div>
                                                <span>Job Description</span>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="JobRequirements" checked>
                                                </div>
                                                <span>Job Requirements</span>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="SalaryType" checked>
                                                </div>
                                                <span>Salary Type</span>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="Location_City" checked>
                                                </div>
                                                <span>City</span>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="Salary" checked>
                                                </div>
                                                <span>Salary</span>
                                            </li>
                                            <li>
                                                <div class="checkbox">
                                                    <input type="checkbox" name="ej_filter[]" value="YouTube_Video"  checked>
                                                </div>
                                                <span>Youtube Link </span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="xml-header">
                                        <h4>Date Range:</h4>
                                    </div>
                                    <div class="date-picker-wrap">
                                        <ul>
                                            <li>
                                                <span>from</span>
                                                <input type="text" name="start_date" id="start_date" value="" placeholder="Start Date">
                                            </li>
                                            <li>
                                                <span>to</span>
                                                <input type="text" name="end_date" id="end_date" value="" placeholder="End Date">
                                            </li>
                                        </ul>
                                    </div>
<!--                                    <div class="xml-header">
                                        <h4>File Type:</h4>
                                    </div>
                                    <div class="date-picker-wrap">
                                        <ul>
                                            <li>
                                                <span>XML</span>
                                                <input type="radio" name="type" value="xml"  checked="true" id="xml">
                                            </li>
                                            <li>
                                                <span>CSV</span>
                                                <input type="radio" name="type" value="csv" id="csv">
                                            </li>
                                        </ul>
                                    </div>-->
                                    <input type="hidden" name="type" id="type" value="xml"/>
                                    <div class="btn-panel">
                                        <input type="submit" style="border:none;"  class="delete-all-btn active-btn" value="Export">
                                    </div>
                                </div>
                                <div class="btn-panel" id="xml_button">
                                        <input type="button" style="border:none;"  class="delete-all-btn active-btn js-export-xml" value="Download XML">
                                        <input type="button" style="border:none;"  class="delete-all-btn active-btn js-export-csv" value="Download CSV">
                                        <a href="<?php echo $xml_feed_url; ?>" target="_blank" class="delete-all-btn active-btn">Public XML Feed URL</a>
                                </div>
                                <div class="btn-panel">
                                    <b>Public XML Feed URL : </b><a href="<?php echo $xml_feed_url; ?>" target="_blank"><?php echo $xml_feed_url; ?></a>
                                </div>
                            </form>
                        </div>
                    <?php } else { ?>
                        <div class="applicant-notes">
                            <span class="notes-not-found ">No Jobs found! </span>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript" src="<?= base_url() ?>assets/js/zebra_datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/metallic.css" type="text/css">
<script type="text/javascript">
    $(document).ready(function () {
        $('form').submit(function () {
            if ($("#xml_form input:checkbox:checked").length > 0)
            {
                return true;
            }
            else
            {
                alertify.error('Please select Attribute(s)');
                return false;
            }

        });

        $('.js-export-xml').click(function(){
            $('#type').val('xml');
            $('form').submit();
        })
        $('.js-export-csv').click(function(){ 
            $('#type').val('csv');
            $('form').submit();
        })

        $('#start_date').Zebra_DatePicker({
            format: 'm-d-Y',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
            pair: $('#end_date')
        });

        $('#end_date').Zebra_DatePicker({
            format: 'm-d-Y',
            direction: 1
        });

        $("#xml_button").mouseover(function(){
            $('#type').val('xml');
        });
        $("#csv_button").mouseover(function(){
            $('#type').val('csv');
        });
    });
</script>
