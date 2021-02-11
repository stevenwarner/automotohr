<div class="main-content" xmlns="http://www.w3.org/1999/html" id="mydiv">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-xs-12 col-sm-5">
                    <?php $this->load->view('resource_center/resource_center_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-8 col-xs-12 col-sm-7">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="page-header-area">

                        <span class="page-heading down-arrow">
                            <?php if($page_type == 'generated_doc') { ?>
                                <a href="<?php echo base_url('resource_center').'/'.$main_menu_segment.'/'.$sub_menu_segment.'#psm_'.$page_content['sub_heading_id'];?>" class="dashboard-link-btn"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back</a>
                            <?php } echo $title; ?>
                        </span>
                    </div>
                    <div class="full-width resource-center-content">
                        <div class="applicant-filter search-job-wrp">
                            <div class="row">                            
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="filter-form-wrp">
                                        <span>Search:</span>
                                        <div class="tracking-filter">
                                            <form method="GET" name="search_filter" id="search_filter" action="<?= base_url('resource_center_search') ?>">
                                                <div class="row">
                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10 custom-col">
                                                        <div class="hr-select-dropdown no-aarow">
                                                            <input type="text" name="key" value="" class="invoice-fields search-job" placeholder="Search Resource Center" required="required" data-rule-required='true'>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 custom-col">
                                                        <input type="submit" value="Search" class="form-btn">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="full-width resource-nav">
                            <?php   foreach($main_menu as $key => $menu) { //echo '<pre>'; print_r($menu); exit;
                                    $main_url = $menu['code']; 
                                    $button_class = 'btn-default';
                                    
                                    if($main_url == $segment1) {
                                        $button_class = 'btn-success';
                                    } ?>

                                    <div class="dropdown">
                                        <button class="btn <?php echo $button_class;?> dropdown-toggle" type="button" id="<?php echo $menu['code'];?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <i class="fa <?php echo $menu['fa_icon']; ?>"></i> <?php echo $menu['name']; ?>
                                            <span class="fa fa-angle-down"></span>
                                        </button>
                                        
                            <?php       if(!empty($sub_menu[$key])) {
                                            $sub_menus = $sub_menu[$key]; ?>
                                        
                                            <ul class="dropdown-menu" aria-labelledby="toppicsMenu">
                            <?php               foreach($sub_menus as $submenu) { ?>
                                                    <li><a href="<?php echo $submenu['link']; ?>"><?php echo $submenu['name'];?></a></li>
                            <?php               } ?>
                                            </ul>
                            <?php       } ?>
                                    </div>
                            <?php } ?>
                        </div>
                        
        <?php           if($active_link == 'resource_center') { ?>
                            <div class="full-width body-resource-center">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h3>Welcome to the AutomotoHR Resource Center</h3>
                                        <p>Welcome to your Human Resources Support Center, helping you to research and resolve the HR related workplace issues you face each and every day!</p>
                                        <p><b>Protect Your Business</b></p>
                                        <p>Your HR Support Center gives you somewhere to turn for compliance, legal and relationship HR questions, concerns and needs.
                                            The information here will help you to manage your most valuable asset: <b>Your Employees!!</b></p>
                                        <p>We encourage you to log into and review this resource area as often as possible as updates are made all of the time.</p>
                                        <p>In your Human Resources Support Center you will be able to read articles, download a handbook, forms, and job descriptions, and research laws and a database with answers to your employment, legal, and relationship questions and much more.</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="panel panel-default">
                                            <div class="panel-heading"><strong>Customize Documents</strong></div>
                                            <div class="panel-body">
                                                <p>This position supervises and directs the daily functions of the Commercial Accounts department and is responsible for maintaining positive relationship with Commercial Accounts customers, developing new business, and providing quality service.</p>
                                                <a href="javascript:;" class="btn btn-success">Learn More</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="panel panel-default">
                                            <div class="panel-heading"><strong>Featured Training</strong></div>
                                            <div class="panel-body">
                                                <p>This position supervises and directs the daily functions of the Commercial Accounts department and is responsible for maintaining positive relationship with Commercial Accounts customers, developing new business, and providing quality service.</p>
                                                <a href="javascript:;" class="btn btn-success">Learn More</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
        <?php           } ?>
                        
        <?php           if(!empty($page_content)) { ?>
                            <div class="full-width intro-main">
                                <h3><span><i class="fa <?php echo $intro_main_fa_icon; ?>"></i></span> <?php echo ucwords($segment1);?>
                                </h3>

        <?php                   if($page_type == 'generated_doc') {
                                    $font_awesome = '';

                                    if($page_content['doc_type'] == 'Generated') {
                                        $font_awesome = '<i class="fa fa-file-word-o"></i>';
                                    }

                                    $type = strtolower($page_content['type']);

                                        if(!empty($type)) {
                                            switch($type) {
                                                case 'doc':
                                                case 'docx':
                                                    $font_awesome = '<i class="fa fa-file-word-o"></i>';
                                                break;
                                                case 'pdf':
                                                    $font_awesome = '<i class="fa fa-file-pdf-o"></i>';
                                                break;
                                                case 'xls':
                                                case 'xlsx':
                                                    $font_awesome = '<i class="fa fa-file-excel-o"></i>';
                                                break;
                                                case 'ppt':
                                                case 'pptx':
                                                    $font_awesome = '<i class="fa fa-file-powerpoint-o"></i>';
                                                break;
                                                case 'zip':
                                                case 'rar':
                                                case '7z':
                                                    $font_awesome = '<i class="fa fa-file-zip-o"></i>';
                                                break;
                                                case 'txt':
                                                    $font_awesome = '<i class="fa fa-file-text"></i>';
                                                break;
                                                case 'png':
                                                case 'jpe':
                                                case 'jpeg':
                                                case 'tiff':
                                                case 'bmp':
                                                case 'raw':
                                                    $font_awesome = '<i class="fa fa-file-image-o"></i>';
                                                break;
                                                case 'avi':
                                                case 'flv':
                                                case 'wmv':
                                                case 'mov':
                                                case 'mp4':
                                                    $font_awesome = '<i class="fa fa-file-video-o"></i>';
                                                break;
                                            }
                                        }
                                    $sopy_btn = $page_content['doc_type'] == 'Generated' ? ('<a class="pull-right btn btn-success copy-to-dms" data-attr="'.$page_content['sid'].'" href="javascript:;">Copy To DMS</a>') : '';
                                    echo '<h3><span>'.$font_awesome.'</span>'.$page_content['title']. $sopy_btn . '</h3>';
                                } ?>

                                <p><?php echo $page_content['description']; ?></p>

        <?php                   if((isset($page_content['video_status']) && $page_content['video_status'] == 1) && ($page_content['video'] != '' || $page_content['video'] != NULL)) { ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $page_content['video']; ?>" allowfullscreen></iframe>
                                    </div>
        <?php                   } ?>

        <?php                   if((isset($page_content['banner_status']) && $page_content['banner_status'] == 1)  && ($page_content['banner_url'] != '' || $page_content['banner_url'] != NULL)) { ?>
                                    <div class="image-wrp well well-sm">
                                        <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $page_content['banner_url']; ?>" />
                                    </div>
        <?php                   } ?>
                            </div>

        <?php                   if($page_type == 'generated_doc' && $page_content['doc_type'] != 'Generated') { ?>
                                    <div class="table-responsive table-outer">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td>File Type</td>
                                                    <td class="file-icon"><?php echo $font_awesome; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>File Extension</td>
                                                    <td><?php echo ucfirst($page_content['type']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Copy</td>
                                                    <td><a class="btn btn-success copy-to-dms" href="javascript:;" data-attr="<?= $page_content['sid'];?>">Document Management System</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Download</td>
                                                    <td><a class="btn btn-success " href="<?=AWS_S3_BUCKET_URL.$page_content['file_code']?>" download="<?php echo $page_content['file_code']?>"><i class="fa fa-download"></i>Download</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Preview</td>
                                                    <td><a class="btn btn-success" href="javascript:;" onclick="fLaunchModal(this);" data-preview-url="<?=AWS_S3_BUCKET_URL.$page_content['file_code']?>" data-download-url="<?=AWS_S3_BUCKET_URL.$page_content['file_code']?>" data-file-name="<?php echo $page_content['title']; ?>" data-document-title="<?php echo $page_content['title']; ?>" data-preview-ext="<?php echo $page_content['type']?>">Preview</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Print</td>
                                                    <td>
                                                        <?php
                                                        $document_filename = !empty($page_content['file_code']) ? $page_content['file_code'] : '';
                                                        $document_file = pathinfo($document_filename);
                                                        $name = explode(".",$document_filename);
                                                        $url_segment_original = $name[0];
                                                        ?>
                                                        <?php if ($page_content['type'] == 'pdf') { ?>
                                                            <a target="_blank" href="<?php echo 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$url_segment_original.'.pdf' ?>" class="btn btn-success">Print</a>
                                                        <?php } else if ($page_content['type'] == 'xls') { ?>
                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Exls' ?>" class="btn btn-success">Print</a>
                                                        <?php } else if ($page_content['type'] == 'xlsx') { ?>
                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Exlsx' ?>" class="btn btn-success">Print</a>
                                                        <?php } else if ($page_content['type'] =='doc') { ?>
                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Edoc&wdAccPdf=0' ?>" class="btn btn-success">Print</a>
                                                        <?php } else if ($page_content['type'] == 'docx') { ?>
                                                            <a target="_blank" href="<?php echo 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$url_segment_original.'%2Edocx&wdAccPdf=0' ?>" class="btn btn-success">Print</a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

        <?php                   } ?>
        <?php               if(!empty($page_content['attachments'])) { //echo '<pre>'; print_r($page_content['attachments']); echo '</pre>';?>
                                <div class="panel-group-wrp questionaire-area">
                                    <div class="panel-group">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle collapsed" data-toggle="collapse" href="#attachments_1" aria-expanded="false">
                                                        <span class="glyphicon glyphicon-chevron-down"></span>
                                                        Attached Forms
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="attachments_1" class="panel-collapse collapse" aria-expanded="false">
                                                <div class="panel-body">
                                                    <div class="attachment-wrp full-width">
        <?php                                           foreach($page_content['attachments'] as $attachment) {
                                                            $font_awesome = '';

                                                            if($attachment['doc_type'] == 'Related') {
                                                                $type = strtolower($attachment['type']);

                                                                if(!empty($type)) {
                                                                    switch($type) {
                                                                        case 'doc':
                                                                        case 'docx':
                                                                            $font_awesome = '<i class="fa fa-file-word-o"></i>';
                                                                        break;
                                                                        case 'pdf':
                                                                            $font_awesome = '<i class="fa fa-file-pdf-o"></i>';
                                                                        break;
                                                                        case 'xls':
                                                                        case 'xlsx':
                                                                            $font_awesome = '<i class="fa fa-file-excel-o"></i>';
                                                                        break;
                                                                        case 'ppt':
                                                                        case 'pptx':
                                                                            $font_awesome = '<i class="fa fa-file-powerpoint-o"></i>';
                                                                        break;
                                                                        case 'zip':
                                                                        case 'rar':
                                                                        case '7z':
                                                                            $font_awesome = '<i class="fa fa-file-zip-o"></i>';
                                                                        break;
                                                                        case 'txt':
                                                                            $font_awesome = '<i class="fa fa-file-text"></i>';
                                                                        break;
                                                                        case 'png':
                                                                        case 'jpe':
                                                                        case 'jpeg':
                                                                        case 'tiff':
                                                                        case 'bmp':
                                                                        case 'raw':
                                                                            $font_awesome = '<i class="fa fa-file-image-o"></i>';
                                                                        break;
                                                                        case 'avi':
                                                                        case 'flv':
                                                                        case 'wmv':
                                                                        case 'mov':
                                                                        case 'mp4':
                                                                            $font_awesome = '<i class="fa fa-file-video-o"></i>';
                                                                        break;
                                                                    }
                                                                } ?>
                                                                <article class="file-attachment">
                                                                    <a href="<?php echo base_url('resource_center').'/'.$main_menu_segment.'/'.$sub_menu_segment.'/'.$attachment['file_url_code'];?>">
                                                                        <figure><?php echo $font_awesome;?></figure>
                                                                        <div class="text"><?php echo $attachment['file_name']; ?></div>
                                                                    </a>
                                                                    <div class="btn-action">
<!--                                                                        <a class="btn btn-xs btn-success" href="javascript:void(0);" onclick="fLaunchModal(this);" data-preview-url="<?=AWS_S3_BUCKET_URL.$attachment['file_code']?>" data-download-url="<?=AWS_S3_BUCKET_URL.$attachment['file_code']?>" data-file-name="<?php echo $attachment['file_name']; ?>" data-document-title="<?php echo $attachment['file_name']; ?>" data-preview-ext="<?php echo $attachment['type']?>">Preview</a>
                                                                        <a class="btn btn-xs btn-success" href="<?=AWS_S3_BUCKET_URL.$attachment['file_code']?>" download="<?php echo $attachment['file_name']?>">Download</a>-->
                                                                        <a class="btn btn-xs btn-success" href="<?php echo base_url('resource_center').'/'.$main_menu_segment.'/'.$sub_menu_segment.'/'.$attachment['file_url_code'];?>">More Details</a>
                                                                    </div>
                                                                </article>
        <?php                                               } else { ?>
                                                                <article class="file-attachment generated-document">
                                                                    <a href="<?php echo base_url('resource_center').'/'.$main_menu_segment.'/'.$sub_menu_segment.'/'.$psm_attachment['file_url_code'];?>">
                                                                        <figure><i class="fa fa-file-word-o"></i></figure>
                                                                        <div class="text"><?php echo $attachment['file_name']; ?></div>
                                                                    </a>
                                                                    <div class="btn-action">
                                                                        <a class="btn btn-xs btn-success" href="<?php echo base_url('resource_center').'/'.$main_menu_segment.'/'.$sub_menu_segment.'/'.$psm_attachment['file_url_code'];?>">More Details</a>
                                                                    </div>
                                                                </article>
        <?php                                               } ?>
        <?php                                           } ?>
                                                    </div>

        <?php                                       if(count($page_content['attachments']) > 8) { ?>
                                                        <div class="btn-wrp text-left">
                                                            <a href="javascript:;" class="btn btn-success btn-show-all">Show More</a>
                                                        </div>
        <?php                                       } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
        <?php               } ?>

        <?php               if(!empty($page_sub_menus)) { ?>
                            <div class="panel-group-wrp questionaire-area">
                                <div class="panel-group">
        <?php                   foreach($page_sub_menus as $psm) { ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="accordion-toggle collapsed" data-toggle="collapse" href="#psm_<?php echo $psm['sid'];?>" aria-expanded="false">
                                                    <span class="glyphicon glyphicon-chevron-down"></span>
                                                    <?php echo $psm['title'];?>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="psm_<?php echo $psm['sid'];?>" class="panel-collapse collapse" aria-expanded="false">
                                            <div class="panel-body">
                                                <p><?php echo $psm['description']; ?></p>

        <?php                                   if($psm['video_status'] == 1 && ($psm['video'] != '' || $psm['video'] != NULL)) { ?>
                                                    <div class="embed-responsive embed-responsive-16by9">
                                                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $psm['video']; ?>" allowfullscreen></iframe>
                                                    </div>
        <?php                                   } ?>

        <?php                                   if($psm['banner_status'] == 1 && ($psm['banner_url'] != '' || $psm['banner_url'] != NULL)) { ?>
                                                    <div class="image-wrp well well-sm">
                                                        <img class="img-responsive" src="<?php echo AWS_S3_BUCKET_URL . $psm['banner_url']; ?>" />
                                                    </div>
        <?php                                   } ?>

        <?php                               if(!empty($psm['attachments'])) { ?>
                                                <div class="attachment-wrp full-width">
        <?php                                       foreach($psm['attachments'] as $psm_attachment) {
                                                        $type = strtolower($psm_attachment['type']);

                                                        if(!empty($type)) {
                                                            switch($type) {
                                                                case 'doc':
                                                                case 'docx':
                                                                    $font_awesome = '<i class="fa fa-file-word-o"></i>';
                                                                break;
                                                                case 'pdf':
                                                                    $font_awesome = '<i class="fa fa-file-pdf-o"></i>';
                                                                break;
                                                                case 'xls':
                                                                case 'xlsx':
                                                                    $font_awesome = '<i class="fa fa-file-excel-o"></i>';
                                                                break;
                                                                case 'ppt':
                                                                case 'pptx':
                                                                    $font_awesome = '<i class="fa fa-file-powerpoint-o"></i>';
                                                                break;
                                                                case 'zip':
                                                                case 'rar':
                                                                case '7z':
                                                                    $font_awesome = '<i class="fa fa-file-zip-o"></i>';
                                                                break;
                                                                case 'txt':
                                                                    $font_awesome = '<i class="fa fa-file-text"></i>';
                                                                break;
                                                                case 'png':
                                                                case 'jpe':
                                                                case 'jpeg':
                                                                case 'tiff':
                                                                case 'bmp':
                                                                case 'raw':
                                                                    $font_awesome = '<i class="fa fa-file-image-o"></i>';
                                                                break;
                                                                case 'avi':
                                                                case 'flv':
                                                                case 'wmv':
                                                                case 'mov':
                                                                case 'mp4':
                                                                    $font_awesome = '<i class="fa fa-file-video-o"></i>';
                                                                break;
                                                            }
                                                        } ?>

        <?php                                           if($psm_attachment['doc_type'] == 'Related') { ?>
                                                            <article class="file-attachment">
                                                                <a href="<?php echo base_url('resource_center').'/'.$main_menu_segment.'/'.$sub_menu_segment.'/'.$psm_attachment['file_url_code'];?>">
                                                                    <figure><?php echo $font_awesome; ?></figure>
                                                                    <div class="text"><?php echo $psm_attachment['file_name']; ?></div>
                                                                </a>
                                                                <div class="btn-action">
<!--                                                                    <a class="btn btn-xs btn-success" href="javascript:void(0);" onclick="fLaunchModal(this);" data-preview-url="<?=AWS_S3_BUCKET_URL.$psm_attachment['file_code']?>" data-download-url="<?=AWS_S3_BUCKET_URL.$psm_attachment['file_code']?>" data-file-name="<?php echo $psm_attachment['file_name']; ?>" data-document-title="<?php echo $psm_attachment['file_name']; ?>" data-preview-ext="<?php echo $psm_attachment['type']?>">Preview</a>
                                                                    <a class="btn btn-xs btn-success" href="<?=AWS_S3_BUCKET_URL.$psm_attachment['file_code']?>" download="<?php echo $psm_attachment['file_name'];?>">Download</a>-->
                                                                    <a class="btn btn-xs btn-success" href="<?php echo base_url('resource_center').'/'.$main_menu_segment.'/'.$sub_menu_segment.'/'.$psm_attachment['file_url_code'];?>">More Details</a>
                                                                </div>
                                                            </article>
        <?php                                           } else { ?>
                                                            <article class="file-attachment generated-document">
                                                                <a href="<?php echo base_url('resource_center').'/'.$main_menu_segment.'/'.$sub_menu_segment.'/'.$psm_attachment['file_url_code'];?>">
                                                                    <figure><i class="fa fa-file-word-o"></i></figure>
                                                                    <div class="text"><?php echo $psm_attachment['file_name']; ?></div>
                                                                </a>
                                                                <div class="btn-action">
                                                                    <a class="btn btn-xs btn-success" href="<?php echo base_url('resource_center').'/'.$main_menu_segment.'/'.$sub_menu_segment.'/'.$psm_attachment['file_url_code'];?>">More Details</a>
                                                                </div>
                                                            </article>
        <?php                                           } ?>
        <?php                                       } ?>
                                                </div>
        <?php                               } ?>
        <?php                                   if(count($psm['attachments']) > 8) { ?>
                                                    <div class="btn-wrp text-left">
                                                        <a href="javascript:;" class="btn btn-success btn-show-all">Show More</a>
                                                    </div>
        <?php                                   } ?>
                                            </div>
                                        </div>
                                    </div>
        <?php                       } ?>
                                </div>
                            </div>
        <?php               } ?>
        <?php           } ?>


                    </div>

                    <?php if(sizeof($copied_record)>0){?>
                        <div class="table-responsive table-outer" style="margin-top: 50px;">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="col-xs-6 text-center">Copied By</th>
                                    <th class="col-xs-6 text-center">Copied On</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($copied_record as $cr){?>
                                    <tr>
                                        <td><?php echo $cr['copied_by_name'];?></td>
                                        <td class="text-center"><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $cr['copy_date'])->format('F j, Y h:i A');?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    <?php }?>

                </div>
            </div>
        </div>
    </div>
</div>

<div id="document_modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="document_modal_title">Modal title</h4>
            </div>
            <div id="document_modal_body" class="modal-body">
                ...
            </div>
            <div id="document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".btn-show-all").click(function () {
            if ($(this).text() == "Show More") {
                $(this).text("Show Less");
            } else {
                $(this).text("Show More");
            };
            $(this).parent().prev(".attachment-wrp").toggleClass("auto-h");
        });

        $('.collapse').on('shown.bs.collapse', function () {
            $(this).parent().find(".glyphicon-chevron-down").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".glyphicon-chevron-up").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");
        });
        
        $("#search_filter").validate();
        
        var url = document.location.toString();
        
        if ( url.match('#') ) {
            $('#'+url.split('#')[1]).addClass('in');
        }

        $('.copy-to-dms').click(function(){
            var file_id = $(this).attr('data-attr');
            alertify.confirm(
                'Confirm!',
                'Are you sure you want to copy this document in Company Document Management System?',
                function () {
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url('resource_center_copy_to');?>',
                        data:{
                            file_sid: file_id
                        },
                        success: function(data){
                            window.location.href = window.location.href;
                        },
                        error: function (){
                            alertify.error('Something went wrong.');
                        }
                    });
                },
                function () {
                    alertify.error('Cancelled');
                })
        });
    });
    
    function fLaunchModal(source) {
        var url_segment_original = '<?php echo isset($url_segment_original) ? $url_segment_original : '' ; ?>';
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        //var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var file_extension = $(source).attr('data-preview-ext');
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'pdf':
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'+url_segment_original+'.pdf" class="btn btn-success">Print</a>';
                    break;
                case 'doc':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'+url_segment_original+'%2Edoc&wdAccPdf=0" class="btn btn-success">Print</a>';
                    break;
                case 'docx':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'+url_segment_original+'%2Edocx&wdAccPdf=0" class="btn btn-success">Print</a>';
                    break;
                case 'xls':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'+url_segment_original+'%2Exls" class="btn btn-success">Print</a>';
                    break;
                case 'xlsx':
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    footer_print_btn = '<a target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'+url_segment_original+'%2Exlsx" class="btn btn-success">Print</a>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                    //console.log('in images check');
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                    break;
                default :
                    //console.log('in google docs check');
                    //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

            footer_content = '<a target="_blank" download="download" class="btn btn-success" href="' + document_download_url + '">Download</a>';
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_footer').append(footer_print_btn);
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function () {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
                //document.getElementById('preview_iframe').contentWindow.location.reload();
            }
        });
    }
</script>


<!-- Added on: 03-06-2019 -->
<script>
    $(function(){
        $('form[name="search_filter"]').submit(function(){
            $(this).find('input[name="key"]').val(
                $(this).find('input[name="key"]').val().trim().replace(/\s+/g, ' ')
            );
        });
    })
</script>