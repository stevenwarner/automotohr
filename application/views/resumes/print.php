<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo STORE_NAME; ?> : <?php echo $title ?></title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ?>assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ?>assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ?>assets/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo  base_url() ?>assets/css/responsive.css">
    <link rel="shortcut icon" href="<?php echo  base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>

    <script type="text/javascript" src="<?php echo  base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?php echo  base_url() ?>assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo  base_url() ?>assets/js/functions.js"></script>

</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="resumes-view">
                    <div class="list-group">
                        <div class="list-group-item">
                            <div class="media">
                                <div class="media-left">
                                    <?php if(!empty($resume['ProfilePictureDetail'])) { ?>
                                        <img class="media-object"  src="http://automotosocial.com/files/pictures/<?php echo $resume['ProfilePictureDetail']['saved_file_name']; ?>">
                                    <?php } else { ?>
                                        <img class="media-object"  src="<?php echo  base_url() ?>assets/images/img-applicant.jpg">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>