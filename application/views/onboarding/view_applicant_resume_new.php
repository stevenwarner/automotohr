<?php
if ($this->session->userdata('logged_in')) {
    $data['session'] = $this->session->userdata('logged_in');
    $company_sid = $data['session']['company_detail']['sid'];
    if(!isset($applicant_jobs)){
        $applicant_jobs = $this->application_tracking_system_model->get_single_applicant_all_jobs($id, $company_sid);
    } 
}

?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="application-header">
                                <article>
                                    <figure>
                                        <img src="<?php echo isset($user_info['pictures']) && $user_info['pictures'] != NULL && $user_info['pictures'] != '' ? AWS_S3_BUCKET_URL . $user_info['pictures'] : base_url('assets/images/default_pic.jpg'); ?>" alt="Profile Picture" />
                                    </figure>
                                    <div class="text">
                                        <h2><?php echo $user_info["first_name"]; ?> <?= $user_info["last_name"] ?></h2>
                                        <div class="start-rating">
                                            <input readonly="readonly" id="input-21b" value="<?php echo isset($user_average_rating) ? $user_average_rating : 0; ?>" type="number" name="rating" class="rating" min=0 max=5 step=0.2  data-size="xs" />
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo base_url('applicant_profile/' . $user_info['sid']); ?>">
                                        <i class="fa fa-chevron-left"></i>Applicant Profile</a>
                                    Resume Library
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="page-header full-width">
                            <h1 class="section-ttile"> Resume Library</h1>
                            <strong> Information:</strong> If you are unable to view the resume library, kindly reload the page.
                            <?php if(count($applicant_jobs) == 1){ ?>
                            <a class="btn btn-success float-right confirmation" href="javascript:;" src="<?php echo base_url("onboarding/send_applicant_resume_request/applicant") . "/" . $user_info["sid"] . "/" . $job_list_sid; ?>"><i class="fa fa-envelope"></i> Send Resume Request</a>
                            <?php } else { ?>
                                        <a class="btn btn-success float-right" href="javascript:0;" data-toggle="modal" data-target="#send_resume_request"><i class="fa fa-envelope"></i> Send Resume Request</a>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"> 
                        <?php if (!empty($resume_listing)) { ?>
                            <?php foreach ($resume_listing as $key => $job) { ?>
                                <?php 
                                    $colspan_id = str_replace(' ', '_', $job['job_name']);
                                ?>
                                <div class="accordion-colored-header header-bg-gray">
                                    <div class="panel-group" id="onboarding-configuration-accordion">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"
                                                       href="#<?php echo $colspan_id; ?>">
                                                        <span class="glyphicon glyphicon-plus js-main-gly"></span>
                                                        <?php 
                                                            echo $job['job_name'].' ( '.reset_datetime(array('datetime' => $job['last_update'], '_this' => $this)).' )';  
                                                        ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="<?php echo $colspan_id; ?>" class="panel-collapse collapse js-main-coll">
                                                <div class="panel-body">
                                                    <div class="table-responsive full-width">
                                                        <table class="table table-plane js-uncompleted-docs">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-lg-8">Document Name</th>
                                                                    <th class="col-lg-4 text-center" colspan="3">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($job['resumes'])) { ?>
                                                                    <?php foreach ($job['resumes'] as $key => $resume) { ?>
                                                                    	<?php
                                                                    		$iframe_url			= '';
                                                                    		$download_url 		= '';
                                                                    		$print_url 			= '';
                                                                            $resume_url 		= $resume['resume_url'];
                                                                            $resume_name        = pathinfo($resume_url)['filename'];
                                                                            $resume_extension   = pathinfo($resume_url)['extension'];

                                                                            if (in_array($resume_extension, ['pdf', 'csv'])) { 

                                                                                 $pdfSrc = copyObjectAWS($resume_url);

                                                                                $iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $pdfSrc . '&embedded=true';
                                                                                $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$resume_name.'.pdf';
                                                                            } else if (in_array($resume_extension, ['doc', 'docx'])) {
                                                                                $iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $resume_url);
                                                                                if ($resume_extension == 'doc') {
                                                                                	$print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$resume_name.'%2Edoc&wdAccPdf=0';
                                                                                }else {
                                                                                	$print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$resume_name.'%2Edocx&wdAccPdf=0';
                                                                                }
                                                                                
                                                                            } else {
                                                                                $iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL .(urldecode( $resume_url)) . '&embedded=true';
                                                                                $print_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL .(urldecode( $resume_url)) . '&embedded=true';
                                                                            }

                                                                            $download_url = base_url('hr_documents_management/download_upload_document'.'/'.(urldecode($resume_url)));
                                                                        ?>
                                                                        <tr>
                                                                            <td class="col-lg-8">
                                                                                <?php echo $resume['type']; ?>
                                                                            </td>
                                                                            <td>
                                                                                <a 	href="javascript:void(0);" 
	                                                                            	onclick="show_resume_popup(this);" 
	                                                                            	class="btn btn-success btn-sm btn-block" 
	                                                                            	data-preview-url="<?php echo $iframe_url; ?>"
	                                                                            	data-print-url="<?php echo $print_url; ?>"
	                                                                            	data-download-url="<?php echo $download_url; ?>"
	                                                                            >
                                                                                	<i class="fa fa-eye" aria-hidden="true"></i>
                                                                                    Preview
                                                                                </a>    
                                                                            </td>
                                                                            <td>
                                                                                <a target="_blank" href="<?php echo $print_url; ?>" class="btn btn-success btn-sm btn-block">
                                                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                                                    Print
                                                                                </a>
                                                                            </td>
                                                                            <td>
                                                                                <a target="_blank" href="<?php echo $download_url; ?>" class="btn btn-success btn-sm btn-block">
                                                                                	<i class="fa fa-download" aria-hidden="true"></i>
                                                                                    Download
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            <?php } ?>
                            <?php if (!empty($applicant_resume_list)) { ?>
                                <div class="accordion-colored-header header-bg-gray">
                                    <div class="panel-group" id="onboarding-configuration-accordion">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"
                                                       href="#applicant_resume">
                                                        <span class="glyphicon glyphicon-plus js-main-gly"></span>
                                                        <?php 
                                                            echo 'Applicant Resume';  
                                                        ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="applicant_resume" class="panel-collapse collapse js-main-coll">
                                                <div class="panel-body">
                                                	<div class="table-responsive full-width">
                                                        <table class="table table-plane js-uncompleted-docs">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-lg-8">Document Name</th>
                                                                    <th class="col-lg-4 text-center" colspan="3">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($applicant_resume_list as $applicant_key => $applicant_resume) { ?>
                                                                	<?php
                                                                		$iframe_url			= '';
                                                                		$download_url 		= '';
                                                                		$print_url 			= '';
                                                                        $resume_url 		= $applicant_resume['resume_url'];
                                                                        $resume_name        = pathinfo($resume_url)['filename'];
                                                                        $resume_extension   = pathinfo($resume_url)['extension'];

                                                                        if (in_array($resume_extension, ['pdf', 'csv'])) { 
                                                                            $iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $resume_url . '&embedded=true';
                                                                            $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$resume_name.'.pdf';
                                                                        } else if (in_array($resume_extension, ['doc', 'docx', 'ppt', 'pptx'])) {
                                                                            $iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $resume_url);
                                                                            if ($resume_extension == 'doc') {
                                                                            	$print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$resume_name.'%2Edoc&wdAccPdf=0';
                                                                            }else {
                                                                            	$print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$resume_name.'%2Edocx&wdAccPdf=0';
                                                                            }
                                                                            
                                                                        } else {
                                                                            $iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . (urldecode($resume_url)) . '&embedded=true';
                                                                            $print_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . (urldecode($resume_url)) . '&embedded=true';
                                                                        }

                                                                        $download_url = base_url('hr_documents_management/download_upload_document'.'/'.(urldecode($resume_url)));
                                                                    ?>
                                                                    <tr>
                                                                        <td class="col-lg-8">
                                                                            <?php echo $applicant_resume['type']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <a 	href="javascript:void(0);" 
                                                                            	onclick="show_resume_popup(this);" 
                                                                            	class="btn btn-success btn-sm btn-block" 
                                                                            	data-preview-url="<?php echo $iframe_url; ?>"
                                                                            	data-print-url="<?php echo $print_url; ?>"
                                                                            	data-download-url="<?php echo $download_url; ?>"
                                                                            >
                                                                            	<i class="fa fa-eye" aria-hidden="true"></i>
                                                                                Preview
                                                                            </a>    
                                                                        </td>
                                                                        <td>
                                                                            <a target="_blank" href="<?php echo $print_url; ?>" class="btn btn-success btn-sm btn-block">
                                                                                <i class="fa fa-print" aria-hidden="true"></i>
                                                                                Print
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            <a target="_blank" href="<?php echo $download_url; ?>" class="btn btn-success btn-sm btn-block">
                                                                            	<i class="fa fa-download" aria-hidden="true"></i>
                                                                                Download
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                            <?php } ?>  
                        <?php } else if (!empty($applicant_resume_list)) { ?>
                        	<div class="accordion-colored-header header-bg-gray">
                                <div class="panel-group" id="onboarding-configuration-accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#onboarding-configuration-accordion"
                                                   href="#applicant_resume">
                                                    <span class="glyphicon glyphicon-plus js-main-gly"></span>
                                                    <?php 
                                                        echo 'Applicant Resume';  
                                                    ?>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="applicant_resume" class="panel-collapse collapse js-main-coll">
                                            <div class="panel-body">
                                            	<div class="table-responsive full-width">
                                                    <table class="table table-plane js-uncompleted-docs">
                                                        <thead>
                                                            <tr>
                                                                <th class="col-lg-8">Document Name</th>
                                                                <th class="col-lg-4 text-center" colspan="3">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($applicant_resume_list as $applicant_key => $applicant_resume) { ?>
                                                            	<?php
                                                            		$iframe_url			= '';
                                                            		$download_url 		= '';
                                                            		$print_url 			= '';
                                                                    $resume_url 		= $applicant_resume['resume_url'];
                                                                    $resume_name        = pathinfo($resume_url)['filename'];
                                                                    $resume_extension   = pathinfo($resume_url)['extension'];

                                                                    if (in_array($resume_extension, ['pdf', 'csv'])) { 
                                                                        $iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $resume_url . '&embedded=true';
                                                                        $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$resume_name.'.pdf';
                                                                    } else if (in_array($resume_extension, ['doc', 'docx'])) {
                                                                        $iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $resume_url);
                                                                        if ($resume_extension == 'doc') {
                                                                        	$print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$resume_name.'%2Edoc&wdAccPdf=0';
                                                                        }else {
                                                                        	$print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$resume_name.'%2Edocx&wdAccPdf=0';
                                                                        }
                                                                        
                                                                    } else {
                                                                        $iframe_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . (urldecode($resume_url)) . '&embedded=true';
                                                                        $print_url = 'https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . (urldecode($resume_url)) . '&embedded=true';
                                                                    }

                                                                    $download_url = base_url('hr_documents_management/download_upload_document'.'/'.(urldecode($resume_url)));
                                                                ?>
                                                                <tr>
                                                                    <td class="col-lg-8">
                                                                        <?php echo $applicant_resume['type']; ?>
                                                                    </td>
                                                                    <td>
                                                                        <a 	href="javascript:void(0);" 
                                                                        	onclick="show_resume_popup(this);" 
                                                                        	class="btn btn-success btn-sm btn-block" 
                                                                        	data-preview-url="<?php echo $iframe_url; ?>"
                                                                        	data-print-url="<?php echo $print_url; ?>"
                                                                        	data-download-url="<?php echo $download_url; ?>"
                                                                        >
                                                                        	<i class="fa fa-eye" aria-hidden="true"></i>
                                                                            Preview
                                                                        </a>    
                                                                    </td>
                                                                    <td>
                                                                        <a target="_blank" href="<?php echo $print_url; ?>" class="btn btn-success btn-sm btn-block">
                                                                            <i class="fa fa-print" aria-hidden="true"></i>
                                                                            Print
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        <a target="_blank" href="<?php echo $download_url; ?>" class="btn btn-success btn-sm btn-block">
                                                                        	<i class="fa fa-download" aria-hidden="true"></i>
                                                                            Download
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        <?php } ?>
                    </div>                         
                </div>
                <?php if ($user_type == 'applicant') { ?>
                    <?php $this->load->view('manage_employer/application_tracking_system/profile_right_menu_applicant'); ?>
                <?php } elseif($user_type == 'employee'){
                    $this->load->view('manage_employer/employee_management/profile_right_menu_employee_new');
                } ?>
            </div>
        </div>
    </div>
</div>
<!-- Main End -->

<!-- Resume Modal Start -->
<div id="show_applicant_resume" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="resume_modal_title"></h4>
            </div>
            <div class="modal-body">
				<div class="embed-responsive embed-responsive-4by3">
                    <div id="resume-pop-up-iframe-container" style="display:none;">
                        <div id="resume-pop-up-iframe-holder" class="embed-responsive-item">
                        </div>
                    </div>
                </div> 
            </div>
            <div class="modal-footer" id="resume_modal_footer">
            	
            </div>
        </div>
    </div>
</div>
<!-- Resume Modal End -->

<script>
    $(document).ready(function () {
        $('#view_0').trigger('click');
        $('.js-main-coll').on('shown.bs.collapse', function (e) {
            e.stopPropagation();
            $(this).parent().find(".js-main-gly").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".js-main-gly").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });

        $('.js-child-coll').on('shown.bs.collapse', function (e) {
            e.stopPropagation();
            $(this).parent().find(".js-child-gly").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function () {
            $(this).parent().find(".js-child-gly").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });
    });



    $('.confirmation').on('click', function () {
        // var url = $(this).attr('src');
        alertify.confirm(
        'Are you sure?',
        'Are You Sure You Want to Send Resume Request to This Applicant?',
        function () {
            $("#send_resume_request_form").submit();
            // window.location.replace(url);
        },
        function () {
            alertify.error('Cancelled!');
        });
        
    });

    function show_resume_popup (source) {
        var resume_preview_url    	= $(source).attr('data-preview-url');
        var resume_print_url    	= $(source).attr('data-print-url');
        var resume_download_url    	= $(source).attr('data-download-url');

        $('#show_applicant_resume').modal('show');
        $("#resume_modal_title").html("Resume Preview");

        $('#resume-pop-up-iframe-container').show();
        var resume_content = $("<iframe />")
        .attr("id", "resume-pop-up-iframe")
        .attr("class", "uploaded-file-preview")
        .attr("src", resume_preview_url);

        footer_print_button    = '<a target="_blank" class="btn btn-success" href="'+resume_print_url+'">Print</a>';
        footer_download_button = '<a target="_blank" class="btn btn-success" href="'+resume_download_url+'">Download</a>';

        $("#resume-pop-up-iframe-holder").append(resume_content);
        $('#resume_modal_footer').html(footer_print_button);
        $('#resume_modal_footer').append(footer_download_button);

        loadIframe(
            resume_preview_url,
            '#resume-pop-up-iframe',
            true
        );
    }


    $('#show_applicant_resume').on('hidden.bs.modal', function () {
       	$("#resume-pop-up-iframe").remove();
        $('#resume-pop-up-iframe-container').hide();
    });
</script>


<?php $this->load->view('iframeLoader'); ?>