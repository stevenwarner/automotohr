<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>Post Jobs To Jobs2Career.com</span>
                        </div>
                        <div class="create-job-wrap">

                                <?php

                                //$temp = jtc_campaign_details_request(JTC_STAGING_CAMPAIGN_ID, JTC_API_MODE);

                                //$job_data = jtc_prediction_generate_json_array(54);

                                //$temp = jtc_prediction_post_prediction_request(JTC_STAGING_CAMPAIGN_ID, $job_data, JTC_API_MODE, JTC_ACCOUNT_ID);
                                //$temp = jtc_prediction_post_prediction_full_request(JTC_STAGING_CAMPAIGN_ID, $job_data, JTC_API_MODE, JTC_ACCOUNT_ID);

                                //$temp = jtc_prediction_post_prediction_range_request(JTC_STAGING_CAMPAIGN_ID, $job_data, JTC_API_MODE, JTC_ACCOUNT_ID);

                                //$temp = jtc_prediction_post_prediction_range_full_request(JTC_STAGING_CAMPAIGN_ID, $job_data, JTC_API_MODE, JTC_ACCOUNT_ID);

                                //$temp = jtc_jobs_fetch_list_request(JTC_STAGING_CAMPAIGN_ID, 3, JTC_API_MODE);

                                //$job_data = jtc_jobs_generate_json_array_for_post(54, mktime(0,0,0, 11,17,2016), mktime(0,0,0, 11,23,2016), 'abc@xyz.com', 600, 'LIFETIME', 25.00);

                                echo '<pre>';
                                //print_r(json_decode($job_data)) ;
                                echo '</pre>';

                                //$temp = jtc_jobs_post_job_request(JTC_STAGING_CAMPAIGN_ID, $job_data, JTC_API_MODE);

                                //$temp = jtc_jobs_fetch_status(JTC_STAGING_CAMPAIGN_ID, 48, JTC_API_MODE);

                                //$temp = jtc_jobs_fetch_budget(JTC_STAGING_CAMPAIGN_ID, 107, JTC_API_MODE);

                                //$temp = jtc_jobs_fetch_details(JTC_STAGING_CAMPAIGN_ID, 107, JTC_API_MODE);

                                //$temp = jtc_jobs_fetch_complete_info(JTC_STAGING_CAMPAIGN_ID, 107, JTC_API_MODE);

                                //$job_data = jtc_jobs_generate_json_array_for_update(JTC_ACCOUNT_ID, 107, 'j.taylor.title@gmail.com', mktime(0, 0, 0, 12, 31, 2016));
                                //print_r($job_data);
                                //$temp = jtc_jobs_update_job_information(JTC_STAGING_CAMPAIGN_ID, 107, $job_data, JTC_API_MODE);

                                //$temp = jtc_jobs_update_job_status(JTC_STAGING_CAMPAIGN_ID, 107, 'LIVE', JTC_API_MODE);

                                //$temp = jtc_jobs_update_job_budget(JTC_STAGING_CAMPAIGN_ID, 107, 600.00, JTC_API_MODE);

                                //$temp = jtc_billing_ping(JTC_API_MODE);

                                 $temp = jtc_billing_get_account_billing(JTC_STAGING_CAMPAIGN_ID, 3, JTC_API_MODE, mktime(0,0,0, 1, 1, 2016), mktime(0,0,0, 12,31,2016));

                                //$temp = jtc_billing_get_campaign_billing(JTC_STAGING_CAMPAIGN_ID, JTC_API_MODE, mktime(0,0,0, 1, 1, 2016), mktime(0,0,0, 12,31,2016));

                                //$temp = jtc_billing_get_job_billing(JTC_STAGING_CAMPAIGN_ID, 107, JTC_API_MODE);

                                //$temp = jtc_billing_get_campaign_report(JTC_STAGING_CAMPAIGN_ID, JTC_API_MODE);

                                //$temp = jtc_billing_get_account_report(JTC_STAGING_CAMPAIGN_ID, 2147, JTC_API_MODE, mktime(0,0,0, 11, 17, 2016), mktime(0,0,0, 12,31,2016));

                                //$temp = jtc_billing_get_job_report(JTC_STAGING_CAMPAIGN_ID, 107, JTC_API_MODE, mktime(0,0,0, 11, 17, 2016), mktime(0,0,0, 12,31,2016));

                                //$temp = jtc_billing_get_campaign_clicks_applications(JTC_STAGING_CAMPAIGN_ID, array('2147', '3'), JTC_API_MODE, mktime(0,0,0, 11, 17, 2016), mktime(0,0,0, 12,31,2016))

                                ?>

                                <pre>
                                    <?php print_r(json_decode($temp)); ?>
                                </pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var test = {"accountID":"2147","referenceID":107,"title":"Senior Software Engineer","description":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque mauris metus, lacinia eget rhoncus id, mattis auctor nisl. Donec venenatis erat ut placerat dictum. <\/p>","companyName":"hassan bokhary Pvt Ltd","emailAddress":"j.taylor.title@gmail.com","stopTime":"2016-12-31T00:00:00+05:00","applyURL":"http:\/\/intranet.dev\/job_details\/107"}                                                            ;
</script>