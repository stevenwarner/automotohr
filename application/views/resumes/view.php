<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a href="<?php echo $back_url; ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Resumes</a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="btn-panel text-right">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <a href="<?php echo base_url('resume_database/save/' . $resume['sid']); ?>" class="btn btn-success">Save This Candidate</a>

                                                    <?php if(!empty($resume['resume_files'])) { ?>
                                                        <?php foreach($resume['resume_files'] as $key => $resume_file) {?>
                                                            <a href="http://www.automotosocial.com/display-resume/<?php echo $resume['sid']?>/?filename=<?php echo $resume_file['saved_file_name']; ?>" class="btn btn-warning">Candidate Resume <?php echo str_pad($key + 1, 2);?></a>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <a target="_blank" href="<?php echo base_url('resume_database/print_resume/' . $resume['sid']); ?>" class="btn btn-info">Print</a>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="resumes-view">
                                            <div class="list-group">
                                                <div class="list-group-item">
                                                    <div class="media">
                                                        <div class="media-left">
                                                        <?php if(!empty($resume['ProfilePictureDetail'])) { ?>
                                                            <img class="media-object"  src="http://automotosocial.com/files/pictures/<?php echo $resume['ProfilePictureDetail']['saved_file_name']; ?>">
                                                        <?php } else { ?>
                                                            <img class="media-object"  src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                        <?php } ?>
                                                        </div>
                                                        <div class="media-body">
                                                        <?php $temp = (!empty($resume['user_info']) ? $resume['user_info']['FirstName'] . ' ' . $resume['user_info']['LastName'] : ''); ?>
                                                            <h3 class="media-heading">
                                                            <?php echo $temp;?>
                                                            <small>
                                                                <span><?php echo ($resume['user_info']['Location_Address'] != '' ? ucwords($resume['user_info']['Location_Address']) . ', ' : ''); ?></span>
                                                                <span><?php echo ($resume['Location_City'] != '' ? ucwords($resume['Location_City']) . ', ' : ''); ?></span>
                                                                <span>
                                                                    <?php if(isset($active_states[$resume['Location_Country']])) { ?>
                                                                        <?php foreach($active_states[$resume['Location_Country']] as $state) {  ?>
                                                                            <?php if(isset($state['sid']) && $state['sid'] == $resume['Location_State']) { ?>
                                                                                <?php echo $state['state_name'] . ', '; ?>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </span>
                                                                <span><?php echo ($resume['Location_ZipCode'] != '' ? ucwords($resume['Location_ZipCode']) . ', ' : ''); ?></span>
                                                                <span><?php echo ($resume['Location_Country'] == 227 ? 'United States' : 'Canada'); ?></span>
                                                            </small>
                                                            </h3>
                                                            <?php $temp = (!empty($resume['user_info']) ? $resume['user_info']['email'] : ''); ?>
                                                            <i class="fa fa-envelope"></i>&nbsp;<span><?php echo $temp;?></span>,&nbsp;&nbsp;&nbsp;
                                                            <?php $temp = (!empty($resume['user_info']) ? $resume['user_info']['PhoneNumber'] : ''); ?>
                                                            <i class="fa fa-phone"></i>&nbsp;<span><?php echo $temp;?></span>
                                                                <p></p>
                                                                <p><strong>Categories</strong> :
                                                                    <?php $categories = array(); ?>
                                                                    <?php foreach($resume['JobCategoryDetail'] as $key => $category) { ?><?php $categories[] = $category['value']; ?>
                                                                    <?php } ?>
                                                                    <?php if(!empty($categories)) { ?>
                                                                        <?php echo implode(', ', $categories); ?>
                                                                    <?php } else { ?>
                                                                        <span>No Data Found</span>
                                                                    <?php } ?>
                                                                </p>
                                                                <p><strong>Occupations</strong> :
                                                                    <?php $occupations = array(); ?>
                                                                    <?php foreach($resume['OccupationDetail'] as $key => $occupation) { ?>
                                                                        <?php $occupations[] = $occupation['caption']; ?>
                                                                    <?php } ?>
                                                                    <?php if(!empty($occupations)) { ?>
                                                                        <?php echo implode(', ', $occupations); ?>
                                                                    <?php } else { ?>
                                                                        <span>No Data Found</span>
                                                                    <?php } ?>
                                                                </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="list-group-item">
                                                    <h3 class="list-group-item-heading">
                                                        <?php $temp = ($resume['Title'] != '' ? $resume['Title'] : ''); ?>
                                                        <?php echo $temp;?>
                                                    </h3>
                                                    <div class="list-group-item-text">
                                                        <?php $temp = ($resume['activation_date'] != '' ? convert_date_to_frontend_format($resume['activation_date']) : ''); ?>
                                                        Created: <?php echo $temp;?>
                                                    </div>
                                                </div>
                                                <div class="list-group-item">
                                                    <h3 class="list-group-item-heading">Objective:</h3>
                                                    <div class="list-group-item-text">
                                                        <?php $temp = ($resume['Objective'] != '' ? $resume['Objective'] : 'No Data Found'); ?>
                                                        <?php echo $temp;?>
                                                    </div>
                                                </div>


                                                <div class="list-group-item">
                                                    <h3 class="list-group-item-heading">Skills:</h3>
                                                    <div class="list-group-item-text">
                                                        <?php $temp = ($resume['Skills'] != '' ? $resume['Skills'] : 'No Data Found'); ?>
                                                        <?php echo $temp;?>
                                                    </div>
                                                </div>

                                                <?php if($resume['YouTube_Video'] != '') { ?>
                                                    <div class="list-group-item autoheight">
                                                        <div class="embed-responsive embed-responsive-16by9">
                                                            <iframe id="youtube_player" class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo  get_youtube_video_id_from_url($resume['YouTube_Video']); ?>"></iframe>
                                                        </div>
                                                    </div>
                                                <?php } ?>

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

