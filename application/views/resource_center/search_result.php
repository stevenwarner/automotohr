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
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <?php echo $title;?>
                        </span>
                    </div>
                    <div class="full-width resource-center-content">
                        <div class="applicant-filter search-job-wrp">
                            <div class="row">                            
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="filter-form-wrp">
                                        <span>Search:</span>
                                        <div class="tracking-filter">
                                            <form method="GET" name="search_filter" action="<?= base_url('resource_center_search') ?>">
                                                <div class="row">
                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10 custom-col">
                                                        <div class="hr-select-dropdown no-aarow">
                                                            <input type="text" name="key" value="<?php echo $key; ?>" class="invoice-fields search-job" placeholder="Search Resource Center">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 custom-col">
<!--                                                        <a class="form-btn" href="#" id="search-btn">Search</a>-->
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
                            <?php           } ?>
                                    </div>
                            <?php } ?>
                        </div>
                        
                        <div class="full-width intro-main">
                            <h3 style="margin-top: 0;">
                                <span><i class="fa fa-search"></i></span> Search Results (<?php echo $search_count;?>)
                            </h3>
                        </div>
                        <div class="full-width searched-results">
                            <div class="full-width tabs-resource-search">
                                <ul class="nav nav-tabs nav-justified indigo">
                                    <li class="active">
                                        <a href="#search_content" data-toggle="tab">Search Content (<?php echo count($search_content);?>)</a>
                                    </li>
                                    <li>
                                        <a href="#search_attachments" data-toggle="tab">Search Attachments (<?php echo count($search_files);?>)</a>
                                    </li>
                                </ul>
                                <div class="full-width tab-content">
                                    <div class="tab-pane active" id="search_content">
                                <?php   if(empty($search_content)) {
                                            echo '<span class="no-data">No Content Found! Please try different search keyword. </span>';
                                        } else {
                                            foreach($search_content as $search_key => $search_value) { ?>
                                                <article class="searched-item">
                                                    <?php $description = trim($search_value['description']);
                                                    
                                                          if($description == '' || $description == NULL) {
                                                              echo '<p>Press View Details for more information</p>';
                                                          } else {
                                                              echo '<p>'.substr($description, 0, 100).' ...</p>';
                                                          } ?>
                                                    <div class="view-details">
                                                        <a href="<?php echo base_url('resource_center').'/'.$search_value['parent'].'/'.$search_value['sub_url_code']; ?>">View Details<?php //echo $search_value['title'];?></a>
                                                    </div>
                                                </article>
                                            <?php }
                                        } ?>
                                    </div>
                                    <div class="tab-pane" id="search_attachments">
                                <?php   if(empty($search_files)) {
                                            echo '<span class="no-data">No Attachments Found! Please try different search keyword. </span>';
                                        } else { 
                                            foreach($search_files as $files_key => $files_values) { ?>
                                                <article class="searched-item">
                                                    <h3><?php echo $files_values['file_name'];?></h3>
                                                    <?php $description = trim($files_values['word_content']);
                                                    
                                                          if($description == '' || $description == NULL) {
                                                              echo '<p>Press View Details for more information</p>';
                                                          } else {
                                                              echo '<p>'.substr($description, 0, 100).' ...</p>';
                                                          }
                                                          
                                                          $parent_id = $files_values['parent_id'];
                                                          $sub_menu_id = $files_values['sub_menu_id'];
                                                          $parent_url_code = $parent_urlcodes[$parent_id];
                                                          $submenu_urlcode = $submenu_urlcodes[$sub_menu_id];
                                                          $file_url_code = $files_values['file_url_code']; ?>
                                                    <div class="view-details">
                                                        <a href="<?php echo base_url('resource_center').'/'.$parent_url_code.'/'.$submenu_urlcode.'/'.$file_url_code; ?>">View Details<?php //echo $search_value['title'];?></a>
                                                    </div>
                                                </article>
                                            <?php }
                                        } ?>
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
<!--<script type="text/javascript">
    $(document).ready(function () {

    });
</script>-->

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