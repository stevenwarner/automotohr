<?php if(!empty($position)){ ?>

    <?php $filled_vacancies = $position['filled_vacancies']; ?>

    <?php if(!empty($filled_vacancies)) { ?>

        <?php foreach($filled_vacancies as $vacancy) { ?>


                <?php if(isset($parent_employee_sid)) { ?>

                    <?php if($parent_employee_sid == $vacancy['parent_employee_sid']) { ?>

                        <div class="col-xs-2">
                            <div class="thumbnail">
                                <figure>
                                    <?php if($vacancy['profile_picture'] != '') { ?>
                                        <img class="img-responsive img-circle" src="<?php echo AWS_S3_BUCKET_URL . $vacancy['profile_picture']; ?>">
                                    <?php }  else { ?>
                                        <img class="img-responsive img-circle" src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                    <?php } ?>
                                </figure>
                                <div class="caption">
                                    <p class="employee-name"><?php echo ucwords($vacancy['employee_first_name'] . ' ' . $vacancy['employee_last_name'])?></p>
                                    <small><?php echo $position['position_name']; ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <?php $sub_positions = $position['sub_positions']; ?>
                            <?php if(!empty($sub_positions)) { ?>
                                <div class="row">
                                    <?php foreach($sub_positions as $sub_position) { ?>

                                        <?php $view_data = array('position' => $sub_position, 'parent_employee_sid' => $vacancy['employee_sid']); ?>
                                        <?php $this->load->view('organizational_hierarchy/partials/position_tree_partial', $view_data); ?>

                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
    <?php } ?>
<?php } ?>




<div class="col-xs-12">

</div>

