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
                                    <a href="<?php echo base_url('dashboard'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp resumes-dashboard">
                                <div class="panel panel-default">
                                    <div class="panel-heading" href="#search_filters" data-toggle="collapse">
                                        <h3 class="panel-title">Filters</h3>
                                    </div>
                                    <div id="search_filters" class="panel-body collapse in universal-form-style-v2">
                                        <ul class="row">
                                            <form id="form_search_filters" method="post">
                                                <li>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                                                        <label>Keywords</label>
                                                        <?php $temp = urldecode($this->uri->segment(2));?>
                                                        <?php $temp = ( $temp == 'all' ? '' : $temp );?>
                                                        <input type="text" value="<?php echo $temp; ?>" class="invoice-fields" id="keywords" name="keywords" />
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                                        <label>Posted Within</label>
                                                        <?php $temp = urldecode($this->uri->segment(3));?>
                                                        <?php $temp = ( $temp == 'all' ? '' : $temp );?>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="posted_within" name="posted_within">
                                                                <option <?php echo ( $temp == '0' ? ' selected="selected" ' : ''); ?> value="0">Any Date</option>
                                                                <option <?php echo ( $temp == '30' ? ' selected="selected" ' : ''); ?> value="30">Last 30 Days</option>
                                                                <option <?php echo ( $temp == '15' ? ' selected="selected" ' : ''); ?> value="15">Last 15 Days</option>
                                                                <option <?php echo ( $temp == '7' ? ' selected="selected" ' : ''); ?> value="7">Last 07 Days</option>
                                                            </select>
                                                        </div>     
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="col-sm-3">
                                                        <label>Country</label>
                                                        <?php $temp = urldecode($this->uri->segment(4));?>
                                                        <?php $temp = ( $temp == 'all' ? '' : $temp );?>
                                                        <?php $selected_state = (urldecode($this->uri->segment(5)) != '' ? urldecode($this->uri->segment(5)) : 0 );?>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="location_country" name="location_country" onchange="getStates(this.value, <?php echo $states; ?>, <?php echo $selected_state?>); ">
                                                                <option <?php echo ( $temp == '' ? ' selected="selected" ' : ''); ?> value="">Please Select</option>
                                                                <option <?php echo ( $temp == '227' ? ' selected="selected" ' : ''); ?> value="227">United States</option>
                                                                <option <?php echo ( $temp == '38' ? ' selected="selected" ' : ''); ?> value="38">Canada</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label>State</label>
                                                        <div class="hr-select-dropdown">
                                                            <select class="invoice-fields" id="state" name="location_state">
                                                                <option >Please Select</option>
                                                                <option >Please Select Country</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label>City</label>
                                                        <?php $temp = urldecode($this->uri->segment(6));?>
                                                        <?php $temp = ( $temp == 'all' ? '' : $temp );?>
                                                        <input type="text" class="invoice-fields" id="location_city" name="location_city" value="<?php echo $temp; ?>" />
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label>Zipcode</label>
                                                        <?php $temp = urldecode($this->uri->segment(7));?>
                                                        <?php $temp = ( $temp == 'all' ? '' : $temp );?>
                                                        <input type="text" class="invoice-fields" id="location_zipcode" name="location_zipcode" value="<?php echo $temp; ?>" />
                                                    </div>
                                                </li>
                                                <li class="autoheight">
                                                    <div class="col-sm-12 text-center">
                                                        <a href="<?php echo base_url('resume_database'); ?>" class="btn btn-warning">Clear Search</a>
                                                    
                                                        <button type="button" onclick="fPerformSearch();" class="btn btn-success">Search Resume(s)</button>
                                                    </div>
                                                </li>
                                            </form>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php echo $page_links; ?>
                                        <div class="applicant-box-wrp" id="show_no_jobs">
                                            <?php   if (empty($resumes)) { ?>
                                            <?php       if($is_first_request == 0) { ?>
                                                            <span class="applicant-not-found">No Resumes found!</span>
                                            <?php       } else { ?>
                                                            <span class="applicant-not-found">Please enter a search Criteria.</span>
                                            <?php       } ?>
                                            <?php   } else { ?>
                                                <form method="POST">
                                                    <?php foreach ($resumes as $resume) { ?>
                                                        <article id="manual_row_<?php echo $resume["sid"]; ?>" class="applicant-box">

                                                            <div class="box-head">
                                                                <div class="row date-bar">
                                                                    <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                                                                        <time class="date-applied pull-left">Date Applied: <?php echo convert_date_to_frontend_format($resume['activation_date']); ?></time>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3">
                                                                        <?php if(!empty($resume['resume_files'])) { ?>
                                                                            <?php foreach($resume['resume_files'] as $key => $resume_file) {?>
                                                                                <a href="javascript:void(0);" onclick="fLaunchModal(this);" class="pull-right aplicant-documents" data-preview-url="http://www.automotosocial.com/display-resume/<?php echo $resume['sid']?>/?filename=<?php echo $resume_file['saved_file_name']; ?>" data-download-url="http://www.automotosocial.com/display-resume/<?php echo $resume['sid']?>/?filename=<?php echo $resume_file['saved_file_name']; ?>" data-document-title="Resume"><i class="fa fa-file-text-o"></i><span class="btn-tooltip">Resume</span></a>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="applicant-info">
                                                                <figure>
                                                                    <?php if(!empty($resume['ProfilePictureDetail'])) { ?>
                                                                        <img src="http://automotosocial.com/files/pictures/<?php echo $resume['ProfilePictureDetail']['saved_file_name']; ?>">
                                                                    <?php } else { ?>
                                                                        <img src="<?= base_url() ?>assets/images/img-applicant.jpg">
                                                                    <?php } ?>
                                                                </figure>
                                                                <div class="text">
                                                                    <p>
                                                                        <a href="<?php echo base_url('resume_database/view/' . $resume['sid']); ?>">
                                                                            <?php echo (!empty($resume['user_info']) ? ucwords($resume['user_info']['FirstName'] . ' ' . $resume['user_info']['LastName']) : ''); ?>
                                                                        </a>
                                                                    </p>
                                                                    <ul class="phone-number">
                                                                        <?php if(!empty($resume['user_info']) && $resume['user_info']['email'] != '') { ?>
                                                                            <a href="mailto:<?php echo $resume['user_info']['email']; ?>"><i class="fa fa-envelope"></i>&nbsp;&nbsp;<?php echo $resume['user_info']['email']; ?></a>
                                                                        <?php } ?>
                                                                    </ul>
                                                                    <ul class="phone-number">
                                                                        <?php if(!empty($resume['user_info']) && $resume['user_info']['PhoneNumber'] != '') { ?>
                                                                            <a href="tel:<?php echo $resume['user_info']['PhoneNumber']; ?>"><i class="fa fa-phone"></i>&nbsp;&nbsp;<?php echo $resume['user_info']['PhoneNumber']; ?></a>
                                                                        <?php } ?>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="applicant-job-description">
                                                                <div class="theText">
                                                                    <?php $job_cats = array();?>
                                                                    <?php if(!empty($resume['JobCategoryDetail'])){ ?>
                                                                        <?php foreach($resume['JobCategoryDetail'] as $JobCategory) { ?>
                                                                            <?php $job_cats[] =  ucwords($JobCategory['value']); ?>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                    <?php echo implode(', ', $job_cats); ?>
                                                                </div>                                                                
                                                                <div class="text address-text">
                                                                    <span>Location</span>
                                                                    <p>
                                                                        <?php echo ($resume['Location_City'] != '' ? ucwords($resume['Location_City']) . ', ' : ''); ?>
                                                                        
                                                                            <?php if(isset($active_states[$resume['Location_Country']])) { ?>
                                                                                <?php foreach($active_states[$resume['Location_Country']] as $state) {  ?>
                                                                                    <?php if(isset($state['sid']) && $state['sid'] == $resume['Location_State']) { ?>
                                                                                        <?php echo $state['state_name'] . ', '; ?>
                                                                                    <?php } ?>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        
                                                                        <?php echo ($resume['Location_ZipCode'] != '' ? ucwords($resume['Location_ZipCode']) . ', ' : ''); ?>
                                                                        <?php echo ($resume['Location_Country'] == 227 ? 'United States' : 'Canada'); ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </article>
                                                    <?php } ?>
                                                </form>
                                            <?php } ?>
                                        </div>
                                        <?php echo $page_links; ?>
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

<!-- Modal -->
<div id="document_modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
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

<script>
    $(document).ready(function () {
        $('#location_country').trigger('change');
    });

    function fPerformSearch(){
        var sKeywords = $('#keywords').val();
        var sPostedWithin = $('#posted_within').val();
        var sLocationCountry = $('#location_country').val();
        var sLocationState = $('#state').val();
        var sLocationCity = $('#location_city').val();
        var sLocationZipcode = $('#location_zipcode').val();

        if(sKeywords == ''){
            sKeywords = 'all';
        }

        if(sPostedWithin == 0){
            sPostedWithin = 'all';
        }

        if(sLocationCountry == 0 || sLocationCountry == ''){
            sLocationCountry = 'all';
        }

        if(sLocationState == 0 || sLocationState == ''){
            sLocationState = 'all';
        }

        if(sLocationCity == ''){
            sLocationCity = 'all';
        }

        if(sLocationZipcode == ''){
            sLocationZipcode = 'all';
        }

        var myUrl = '<?php echo base_url('resume_database'); ?>' + '/'
            + encodeURI(sKeywords) + '/'
            + encodeURI(sPostedWithin) + '/'
            + encodeURI(sLocationCountry) + '/'
            + encodeURI(sLocationState) + '/'
            + encodeURI(sLocationCity) + '/'
            + encodeURI(sLocationZipcode) + '/1';

        window.location = myUrl;
    }

    function getStates(val, states, selected) {
        var html = '';
        if (val == '') {
            $('#state').html('<option value="">Select State</option><option value="">Please Select your country</option>');
        } else {
            allstates = states[val];
            html += '<option value="">Select State</option>';
            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                var selected_text = ' selected="selected" ';

                if(id == selected){
                    html += '<option ' + selected_text + ' value="' + id + '">' + name + '</option>';
                }else{
                    html += '<option value="' + id + '">' + name + '</option>';
                }
            }
            $('#state').html(html);
            $('#state').trigger('change');
        }
    }



    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var type = document_preview_url.split(".");
        var file_type = type[type.length - 1];
        var modal_content = '';
        var footer_content = '';
        var iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';

        if (document_preview_url != '') {
            if (file_type == 'jpg' || file_type == 'jpe' || file_type == 'jpeg' || file_type == 'png' || file_type == 'gif'){
                modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
            } else {
                modal_content = '<iframe id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
            }

            footer_content = '<a class="submit-btn" href="' + document_download_url + '">Download</a>';
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("toggle");

        if (document_preview_url != '') {
            document.getElementById('preview_iframe').contentWindow.location = iframe_url;
        }
    }
</script>