<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <?php if($is_print == 0) { ?>
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php } ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                            <?php if($is_print == 0) { ?>
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                                <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                            <?php } ?>

                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <?php if($is_print == 0) { ?>
                                    <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i><?php echo $return_title_heading; ?></a>
                                    <?php } ?>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">

                                <?php if($is_print == 0) { ?>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <a target="_blank" href="<?php echo base_url('interview_questionnaire/print_interview/' . $applicant_sid . '/' . $questionnaire_sid); ?>" class="btn btn-success pull-right">Print</a>
                                        </div>
                                    </div>
                                    <hr />
                                    <?php if($is_already_scored == false) { ?>
                                        <div class="alert alert-info alert-dismissible">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong>Info :</strong> You have not conducted this interview yet!
                                        </div>
                                    <?php } ?>
                                <?php } ?>




                                <?php $this->load->view('interview_questionnaires/already_scored_by_partial'); ?>

                                <?php $this->load->view('interview_questionnaires/candidate_profile_partial'); ?>

                                <form id="form_questionnaire" enctype="multipart/form-data" action="<?php echo current_url();?>" method="post">
                                    <?php $this->load->view('interview_questionnaires/manage_questionnaire_partial'); ?>
                                </form>

                                <form id="form_evaluation" enctype="multipart/form-data" action="<?php echo current_url() ?>" method="post">
                                    <input type="hidden" name="candidate_sid" id="candidate_sid" value="<?php echo $candidate_sid; ?>" />
                                    <input type="hidden" name="candidate_type" id="candidate_type" value="<?php echo $candidate_type; ?>" />
                                    <input type="hidden" name="questionnaire_sid" id="questionnaire_sid" value="<?php echo $questionnaire_sid; ?>" />

                                    <?php $this->load->view('interview_questionnaires/questionnaire_evaluation_partial'); ?>
                                </form>

                            </div>
                        </div>
                    </div>
                    <?php if($is_print == 0) { ?>
                </div>

                <?php $this->load->view($left_navigation); ?>
            </div>
        <?php } ?>
        </div>
    </div>
</div>

<?php if($is_print == 1) { ?>
    <script type="text/javascript">

        $(document).ready(function() {
            window.print();

            window.onafterprint = function(){
                window.close();
            }
        });


    </script>
<?php } ?>

<script>




    function func_convert_form_to_json_object(form_id) {
        var form_data = $('#' + form_id).serializeArray();

        var my_return = {};

        $.each(form_data, function () {
            if (my_return[this.name] !== undefined) {
                if (!my_return[this.name].push) {
                    my_return[this.name] = [my_return[this.name]];
                }
                my_return[this.name].push(this.value || '');
            } else {
                my_return[this.name] = this.value || '';
            }
        });

        return my_return;
    }
</script>