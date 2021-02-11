<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php 

    $applicantTag = '<li>{{applicant_name}}</li>';
    $firstnameTag = '<li>{{first_name}}</li>';
    $lastnameTag  = '<li>{{last_name}}</li>';
    $dateTag      = '<li>{{date}}</li>';
    $emailTag     = '<li>{{email}}</li>';
    $phoneTag     = '<li>{{phone}}</li>';
    $titleTag     = '<li>{{job_title}}</li>';
    $affiliateTag = '<li>{{affiliate_name}}</li>';
    $linksTag     = '<li>{{links}}</li>';
    $messageTag   = '<li>{{message}}</li>';
    $urlTag       = '<li>{{url}}</li>';
    $participantTag = '<li>{{participant_name}}</li>';
    $eventDateTag   = '<li>{{event_date}}</li>';
    $eventTypeTag   = '<li>{{event_type}}</li>';
    $startTimeTag   = '<li>{{start_time}}</li>';
    $eventTitleTag  = '<li>{{event_title}}</li>';
    $usernameTag    = '<li>{{username}}</li>';
    $createPasswordTag = '<li>{{create_password_link}}</li>';
    $contactNameTag = '<li>{{contact-name}}</li>';
    $titleOTag = '<li>{{title}}</li>';
    //
    $tagRows = '';
    if($data['group'] == 'portal_email_templates')
        $tagRows = $applicantTag.$firstnameTag.$lastnameTag.$dateTag.$emailTag.$titleTag;
    //
    if($data['group'] == 'alerts')
        $tagRows = $participantTag.$eventTypeTag.$eventTitleTag.$eventDateTag.$startTimeTag;
    //
    if($data['group'] == 'listing')
        $tagRows = $applicantTag.$titleTag.$urlTag;
    //
    if($data['group'] == 'user')
        $tagRows = $affiliateTag.$usernameTag.$createPasswordTag;
    //
    if($data['group'] == 'other')
        $tagRows = $contactNameTag.$titleOTag;
    //
    if($data['group'] == 'super_admin_templates')
        $tagRows = $firstnameTag.$lastnameTag.$emailTag.$phoneTag;

?>
<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-envelope-square"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="edit-email-template">
                                        <p>Fields marked with an asterisk (<span class="hr-required">*</span>) are mandatory</p>
                                        <div class="edit-template-from-main" >
                                            <?php echo form_open_multipart(''); ?>
                                            <ul>
                                                <li>
                                                    <label>Template Name <span class="hr-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <?php echo form_input(array('class' => 'hr-form-fileds', 'type' => 'text', 'name' => 'name'), set_value('name', $data['name'])) ?>
                                                        <?php echo form_error('name'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Group Name<span class="hr-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <?php echo form_dropdown('group', $group_options, set_value('group', $data['group']), ' class="invoice-fields"'); ?>
                                                        <?php echo form_error('group'); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>From Name</label>
                                                    <div class="hr-fields-wrap">
                                                        <?php echo form_input(array('class' => 'hr-form-fileds', 'name' => 'form_name'), set_value('from_name', $data['from_name'])); ?>
                                                    </div>
                                                </li>				
                                                <li>
                                                    <label>From Email</label>
                                                    <div class="hr-fields-wrap">
                                                        <?php echo form_input(array('class' => 'hr-form-fileds', 'name' => 'from_email'), set_value('from_email', $data['from_email'])); ?>
                                                    </div>
                                                </li>								
                                                <li>
                                                    <label>CC</label>
                                                    <div class="hr-fields-wrap">
                                                        <?php echo form_input(array('class' => 'hr-form-fileds', 'name' => 'cc'), set_value('cc', $data['cc'])); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Subject</label>
                                                    <div class="hr-fields-wrap">
                                                        <?php echo form_input(array('class' => 'hr-form-fileds', 'name' => 'subject'), set_value('subject', $data['subject'])); ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Email Status</label>
                                                    <div class="hr-fields-wrap">
                                                        <?=form_dropdown('status', array(0 => 'InActive', 1 => 'Active'), set_value('status', $data['status']), ' class="invoice-fields"');?>
                                                    </div>
                                                </li>						
                                                <li>
                                                    <label>Attachment</label>
                                                    <div class="hr-fields-wrap"> 
                                                        <?php if (!empty($data['file'])) { ?>
                                                            <div id="remove_image" class="logo-box" style="height: 100px;">
                                                                <div >
                                                                    <div class="close-btn">

                                                                        <a href="javascript:;" id="<?=$data['sid']?>" onclick="image_remove(this.id)"><img src="<?= base_url() ?>assets/images/btn-close.png">

                                                                            <div class="tooltip">Remove logo</div>
                                                                        </a>
                                                                    </div>
                                                                    <a 
                                                                    style="margin-top: 10px;"
                                                                    class="btn btn-info btn-sm btn-block" 
                                                                    onclick="fLaunchModal(this);" 
                                                                    data-preview-url="<?=AWS_S3_BUCKET_URL.$data['file'];?>" 
                                                                    data-download-url="<?=AWS_S3_BUCKET_URL.$data['file'];?>" 
                                                                    data-document-title="<?=$data['file'];?>" 
                                                                    data-file-name="<?=$data['file'];?>" 
                                                                    >Preview</a>
                                                                    
                                                                </div>
                                                            </div>

                                                        <?php } ?>
                                                        <input type="hidden" value="<?= $data['sid'] ?>" name="old_image">  
                                                    </div>
                                                    <div class="hr-fields-wrap">  
                                                        <input type="file" name="file"/>
                                                        <small class="hr-file-zise">(max. 512 M)</small>
                                                    </div>





                                                </li>
                                               
                                                <li>
                                                    <div class="row">
                                                        <div class="col-sm-2 col-xs-12">
                                                            <label>Text</label>
                                                        </div>
                                                        <div class="col-sm-7 col-xs-12">
                                                            <div class="hr-fields-wrap" style="width: 92%;">
                                                                <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                                <textarea class="ckeditor" name="text" rows="8" style="width: 100%;" ><?php echo set_value('text', $data['text']); ?></textarea>
                                                                <?php //echo form_input(array('class'=>'ckeditor','name'=>'text','type'=>'textarea'), set_value('text',$data['text']));  ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 col-xs-12">
                                                            <div class="offer-letter-help-widget pull-right" style="top: 0;">
                                                                <div class="how-it-works-insturction">
                                                                    <strong class="text-left text-inverse">How it's Works :</strong>
                                                                    <p class="how-works-attr">1. Add template name</p>
                                                                    <p class="how-works-attr">2. Add template subject</p>
                                                                    <p class="how-works-attr">3. Add template body</p>
                                                                    <p class="how-works-attr">4. Add data from tags below</p>
                                                                    <p class="how-works-attr">5. Save the template</p>
                                                                </div>
                                                                <?php if($data['group'] != 'super_admin_templates') { ?>
                                                                <div class="tags-area pull-left">
                                                                    <strong>Company Information Tags :</strong>
                                                                    <ul class="tags">
                                                                        <li>{{company_name}}</li>
                                                                        <li>{{company_address}}</li>
                                                                        <li>{{company_phone}}</li>
                                                                        <li>{{career_site_url}}</li>
                                                                    </ul>

                                                                </div>
                                                                <?php } ?>
                                                                <?php if($tagRows != ''){ ?>
                                                                <div class="tags-area pull-left">
                                                                    <br />
                                                                    <strong>Tags :</strong>
                                                                    <ul class="tags">
                                                                        <?=$tagRows;?>
                                                                    </ul>
                                                                </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>				
                                                <li>
                                                    <input type="hidden" name="sid" value="<?php echo $data['sid']; ?>">
                                                    <?php echo form_submit(array('class' => 'search-btn', 'type' => 'submit', 'name' => 'action'), 'Apply'); ?>
                                                    <?php echo form_submit(array('class' => 'search-btn', 'type' => 'submit', 'name' => 'action'), 'Save'); ?>
                                                    <a href="<?=base_url('manage_admin/email_templates/email_templates_view/'.( $data['group'] ).'');?>" class="btn search-btn btn-default">Cancel</a>
                                                </li>
                                            </ul>
                                            <?php echo form_close(); ?>
                                        </div>
                                        <!--                                        <h3>You can use the following variables in this Email Template:</h3>
                                                                                <div class="hr-email-tpl-vars">
                                                                                    <ul>
                                                                                        <li><strong>Global Variables:</strong>
                                                                                            <div class="et-vars">
                                        
                                                                                                <span class="et-var-val">{$GLOBALS.user_site_url} - {tr}Website URL{/tr}</span><br>
                                                                                                <span class="et-var-val">{$GLOBALS.settings.site_title} - {tr}Website Title{/tr}</span><br>
                                                                                                <span class="et-var-val">{$GLOBALS.settings.notification_email}  - {tr}System Email{/tr}</span>
                                        
                                                                                            </div>
                                                                                        </li>
                                                                                        <li><strong>User Variables</strong>:
                                                                                            <div class="et-vars">
                                                                                                <span class="hr-spec-vars-title">{$user} - array containing the following variables:</span>
                                                                                                <div class="specific-vars" style="display: none;">
                                                                                                    <strong>Job Seeker</strong>
                                                                                                    <ul>
                                                                                                        <li>.username</li>
                                                                                                        <li>.email</li>
                                                                                                        <li>.featured</li>
                                                                                                        <li>.sendmail</li>
                                                                                                        <li>.FirstName</li>
                                                                                                        <li>.LastName</li>
                                                                                                        <li>.PhoneNumber</li>
                                                                                                        <li>.TypeofJobInterestedIn</li>
                                                                                                        <li>.HowDidYouHearAboutUs</li>
                                                                                                        <li>.id</li>
                                                                                                        <li>.isJobg8</li>
                                                                                                        <li>.group.id</li>
                                                                                                        <li>.group.caption</li>
                                                                                                    </ul>
                                                                                                    <strong>Employer</strong>
                                                                                                    <ul>
                                                                                                        <li>.username</li>
                                                                                                        <li>.email</li>
                                                                                                        <li>.featured</li>
                                                                                                        <li>.sendmail</li>
                                                                                                        <li>.CompanyName</li>
                                                                                                        <li>.ContactName</li>
                                                                                                        <li>.WebSite</li>
                                                                                                        <li>.PhoneNumber</li>
                                                                                                        <li>.CompanyDescription</li>
                                                                                                        <li>.video.file_url</li>
                                                                                                        <li>.video.file_name</li>
                                                                                                        <li>.video.saved_file_name</li>
                                                                                                        <li>.video.file_id</li>
                                                                                                        <li>.Logo.file_url</li>
                                                                                                        <li>.Logo.file_name</li>
                                                                                                        <li>.Logo.thumb_file_url</li>
                                                                                                        <li>.Logo.thumb_file_name</li>
                                                                                                        <li>.id</li>
                                                                                                        <li>.isJobg8</li>
                                                                                                        <li>.group.id</li>
                                                                                                        <li>.group.caption</li>
                                                                                                    </ul>
                                                                                                </div>
                                                                                            </div>
                                                                                        </li>	
                                                                                    </ul>
                                                                                </div>-->
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

<script>
    function image_remove(id) {
        url = "<?= base_url() ?>manage_admin/email_templates/remove_image";
        alertify.confirm('Confirmation', "Are you sure you want to delete the Template Image?",
        function () {
            $('#remove_image').hide();
            $.post(url, {action:'remove_logo',sid: id})
            .done(function (data) {
                document.getElementById("delete_image").value = '1';
            });
            alertify.success('Image removed.');
        },
        function () {

        });
    }

    function fLaunchModal(source) {
        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');
        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);
        var modal_content = '';
        var footer_content = '';
        var iframe_url = '';
        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                case 'DOC':
                case 'DOCX':
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'JPG':
                case 'JPE':
                case 'JPEG':
                case 'PNG':
                case 'GIF':
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                break;
                default : //using google docs
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
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("show");
        $('#document_modal').on("shown.bs.modal", function () {
        
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
            }
        });
        $('.modal-backdrop').hide();
    }


</script>


<div id="document_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
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


<style>
    .tags-area strong{ padding: 0 10px;}
    .offer-letter-help-widget{ padding-bottom: 10px; }
    .tags-area ul.tags{ padding: 0 0; }
    .tags-area ul.tags li{ width: auto; background-color: #f8f8f8; border: 1px solid #d9d8d5;border-radius: 50px;display: inline-block;height: auto !important;margin: 10px 0 0 10px !important;overflow: hidden;padding: 7px;text-align: center; }
</style>
<!-- 
    TODO
    Copy magic quotes
    Created on: 29-04-2019
-->
<!--
<script>
    $(function(){
        $('.tags-area ul li').click(function(event) {
            var inp = document.createElement('input');
            document.body.appendChild(inp);
            inp.value = $(this).text();
            inp.select();
            document.execCommand('copy',false);
            inp.remove();

            alertify.notify('Copied', 'success', 2, function(){  console.log('dismissed'); });
        });
    })
</script>

-->